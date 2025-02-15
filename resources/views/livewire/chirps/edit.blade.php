<?php

use App\Models\Chirp;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {

    // this is to get the edited chirp
    public Chirp $chirp;

    #[Validate('required|string|max:255')]
    public string $message = '';

    public function mount(){
        $this->message = $this->chirp->message;
    }

    public function update(){
        //to authorize user whether they can update it or not (only the user with appropriate premission can update the chirp)
        //this->authorize is a laravel builtin functionality that used to check if the current user is able to(by checking permissions using policies that we can define) update the chirp or not. If the user is able, the action will be done otherwise laravel returns 403 forbidden http response.
        /*
        Process to create and do the authorization
        1. Define Policy : ChirpPolicy
        2. Implement the authorization logic in the policy class
        3. Register the policy
        4. Call the authorize action: this->authorize()
        */
        $this->authorize('update', $this->chirp);

        $validated = $this->validate();

        $this->chirp->update($validated);

        //dispatch event when sucess update
        $this->dispatch('chirp-updated');
    }

    public function cancel(){
        $this->dispatch('chirp-edit-canceled');
    }
}; ?>

<div>
    <form wire:submit="update">
        <textarea 
            wire:model="message"
            class="block w-full border-gray-300 focus:border-indigo-300 ocus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        </textarea>

        <x-input-error 
            :messages="$errors->get('message')"
            class="mt-2" />
        <x-primary-button class="mt-4">
            {{ __('Save') }}
        </x-primary-button>
        <button 
            class="mt-4"
            wire:click.prevent="cancel">
            Cancel
        </button>
    </form>
</div>

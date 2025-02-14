<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {

    #[Validate('required|string|max:255')]
    public $message = '';

    public function store(){
        //validate the message field
        $validated = $this->validate();

        // dd($validated);

        try{
            auth()->user()->chirps()->create($validated);
        }catch(Exception $e){
            dd($e->getMessage());
        }

        //reset the message
        $this->message = '';

        //dispatch event everytime user create new message
        $this->dispatch('chirp-created');
    }
}; ?>

<div>
    <form wire:submit="store"> 
        <textarea
            wire:model="message"
            placeholder="{{ __('What\'s on your mind?') }}"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        ></textarea>
 
        <x-input-error :messages="$errors->get('message')" class="mt-2" />
        <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
    </form>
</div>

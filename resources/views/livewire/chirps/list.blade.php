<?php

use App\Models\Chirp;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {

    public Collection $chirps;
    public ?Chirp $editing = null;

    public function mount(){
        //using eager load when retreive all chirps and retreive the user id
        $this->chirps = Chirp::with('user')
            ->latest()
            ->get();
        $this->getChirps();
    }

    //listen the dispatch event
    #[On('chirp-created')]
    public function getChirps(){
        $this->chirps = Chirp::with('user')
            ->latest()
            ->get();
    }

    //method to edit chirp
    public function edit(Chirp $chirp){
        $this->editing = $chirp;
        $this->getChirps();
    }

    //Listen the dispatch event from chirps.edit.blade.php
    #[On('chirp-edit-canceled')]
    #[On('chirp-updated')]
    public function disableEditing(){
        $this->editing = null;
        $this->getChirps();
    }

    //get makassar timezone
    public function convertTimezone($chirp){
        return Carbon::parse($chirp->created_at)->setTimezone('Asia/Makassar');
    }
}; ?>

<div class="mt-6 bg-white shadow-sm rounded-lg divide-y"> 
    @foreach ($chirps as $chirp)
        <div class="p-6 flex space-x-2" wire:key="{{ $chirp->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-800">{{ $chirp->user->name }}</span>
                        <small class="ml-2 text-sm text-gray-600">{{ $this->convertTimezone($chirp)->format('j M Y, H:i a') }}</small>
                        <!-- check if the chirp is updated or not, if updated show the text edited -->
                        @unless($chirp->created_at->eq($chirp->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                        @endunless
                    </div>
                    <!-- to set so that user can only edit their own chirps  -->
                    @if($chirp->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link wire:click="edit({{ $chirp->id }})">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>
                @if($chirp->is($editing))
                    <!-- chirps.edit is the editing form -->
                    <livewire:chirps.edit :chirp="$chirp" :key="$chirp->id" />
                @else
                    <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>
                @endif
            </div>
        </div>
    @endforeach 
</div>
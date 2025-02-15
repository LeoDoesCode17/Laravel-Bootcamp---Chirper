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

}; ?>

<div>
    //
</div>

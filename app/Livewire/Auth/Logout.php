<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    function mount()
    {
        auth()->logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}

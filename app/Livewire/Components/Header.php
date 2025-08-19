<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        return view('livewire.components.header');
    }

    function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}

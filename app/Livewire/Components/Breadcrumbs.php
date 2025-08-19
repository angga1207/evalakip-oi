<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Breadcrumbs extends Component
{
    public $breadcrumbs = [];
    public $addButton = null;

    function mount($breadcrumbs = [], $addButton = null)
    {
        if ($addButton) {
            $this->addButton = $addButton;
        }
        // dd($addButton);

        // Ensure breadcrumbs is a collection
        if (!is_array($breadcrumbs)) {
            $breadcrumbs = [];
        }
        $this->breadcrumbs = collect($breadcrumbs);
    }

    public function render()
    {
        return view('livewire.components.breadcrumbs');
    }
}

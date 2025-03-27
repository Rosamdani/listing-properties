<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Buy extends Component
{
    #[Layout('components.layouts.app2', ['title' => 'Buy'])]
    public function render()
    {
        return view('livewire.pages.buy');
    }
}

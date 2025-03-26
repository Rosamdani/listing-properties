<?php

namespace App\Livewire\Feature;

use Livewire\Component;

class Search extends Component
{
    public $selectedType;

    public function mount()
    {
        $this->selectedType = 'buy';
    }

    public function buy()
    {
        $this->selectedType = 'buy';
    }

    public function rent()
    {
        $this->selectedType = 'rent';
    }
    
    public function render()
    {
        return view('livewire.feature.search');
    }
}

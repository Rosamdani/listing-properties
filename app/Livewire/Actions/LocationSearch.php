<?php

namespace App\Livewire\Actions;

use Livewire\Component;

class LocationSearch extends Component
{
    public $search = '';
    public $searchResults = [];
    public $selectedLocation = null;
    public $showDropdown = false;
    public $popularLocations = [];
    public $showCurrentLocationLoader = false;

    public function mount()
    {
        $this->popularLocations = [
            ['id' => 1, 'name' => 'Jakarta Selatan', 'type' => 'city'],
            ['id' => 2, 'name' => 'Jakarta Pusat', 'type' => 'city'],
            ['id' => 3, 'name' => 'Bandung', 'type' => 'city'],
            ['id' => 4, 'name' => 'Surabaya', 'type' => 'city'],
            ['id' => 5, 'name' => 'Tangerang', 'type' => 'city'],
            ['id' => 6, 'name' => 'Bekasi', 'type' => 'city'],
            ['id' => 7, 'name' => 'Depok', 'type' => 'city'],
            ['id' => 8, 'name' => 'Bogor', 'type' => 'city'],
        ];

        // Jika menggunakan database:
        // $this->popularLocations = Location::where('is_popular', true)
        //                               ->orderBy('name')
        //                               ->take(8)
        //                               ->get()
        //                               ->toArray();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = collect($this->popularLocations)
            ->filter(function ($location) {
                return stripos($location['name'], $this->search) !== false;
            })
            ->values()
            ->toArray();

        // Jika menggunakan database:
        // $this->searchResults = Location::where('name', 'like', '%' . $this->search . '%')
        //                            ->orWhere('parent_name', 'like', '%' . $this->search . '%')
        //                            ->orderBy('is_popular', 'desc')
        //                            ->orderBy('name')
        //                            ->limit(10)
        //                            ->get()
        //                            ->toArray();

        $this->showDropdown = true;
    }

    public function selectLocation($id, $name, $type)
    {
        $this->selectedLocation = [
            'id' => $id,
            'name' => $name,
            'type' => $type
        ];
        
        $this->search = $name;
        $this->showDropdown = false;
        
        // Emit event untuk komponen lain yang mungkin perlu tahu lokasi yang dipilih
        $this->emit('locationSelected', $id, $name, $type);
    }

    public function useCurrentLocation()
    {
        $this->showCurrentLocationLoader = true;
        // Proses yang sebenarnya akan dilakukan oleh JavaScript
        // untuk mendapatkan koordinat dengan Geolocation API
    }

    public function setCurrentLocation($address, $lat, $lng)
    {
        // Dipanggil oleh JavaScript setelah mendapatkan lokasi pengguna
        $this->selectedLocation = [
            'id' => 'current',
            'name' => $address,
            'type' => 'current_location'
        ];
        
        $this->search = $address;
        $this->showDropdown = false;
        $this->showCurrentLocationLoader = false;
        
        // Emit event dengan data lokasi
        $this->emit('currentLocationSelected', $address, $lat, $lng);
    }

    public function resetLocation()
    {
        $this->selectedLocation = null;
        $this->search = '';
        $this->searchResults = [];
        $this->showDropdown = false;
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown && empty($this->searchResults) && strlen($this->search) < 2) {
            $this->searchResults = $this->popularLocations;
        }
    }
    public function render()
    {
        return view('livewire.actions.location-search');
    }
}

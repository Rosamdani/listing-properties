<?php

use Livewire\Volt\Component;

new class extends Component {
    public $search;
    public $type;
    public $status;
    public $minPrice;
    public $maxPrice;
    public $bedrooms;
    public $location;
    public $activeTab;
    public $propertyTypes;
    public $propertyStatuses;
    public $filtersExpanded = false;
    public $mobileFiltersVisible = false;
    public $activeFilters = 0;

    public function mount($search, $type, $status, $minPrice, $maxPrice, $bedrooms, $location, $activeTab, $propertyTypes, $propertyStatuses)
    {
        $this->search = $search;
        $this->type = $type;
        $this->status = $status;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->bedrooms = $bedrooms;
        $this->location = $location;
        $this->activeTab = $activeTab;
        $this->propertyTypes = $propertyTypes;
        $this->propertyStatuses = $propertyStatuses;

        // Count active filters
        $this->countActiveFilters();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('setActiveTab', $tab);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->type = '';
        $this->status = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->bedrooms = '';
        $this->location = '';

        $this->dispatch('resetFilters');
        $this->countActiveFilters();
    }

    public function updatedSearch()
    {
        \Illuminate\Support\Facades\Log::info('Search updated to: ' . $this->search);
        $this->dispatch('filterUpdated', [
            'field' => 'search',
            'value' => $this->search,
        ]);
        $this->countActiveFilters();
    }

    public function updatedType($value)
    {
        \Illuminate\Support\Facades\Log::info('Type updated to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'type',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function updatedStatus($value)
    {
        \Illuminate\Support\Facades\Log::info('Status updated to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'status',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function updatedMinPrice($value)
    {
        \Illuminate\Support\Facades\Log::info('MinPrice updated to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'minPrice',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function updatedMaxPrice($value)
    {
        \Illuminate\Support\Facades\Log::info('MaxPrice updated to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'maxPrice',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function updatedBedrooms($value)
    {
        \Illuminate\Support\Facades\Log::info('Bedrooms updated to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'bedrooms',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function updatedLocation($value)
    {
        \Illuminate\Support\Facades\Log::info('Location updated to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'location',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function setLocation($location)
    {
        $this->location = $location;
        \Illuminate\Support\Facades\Log::info('Location set to: ' . $location);
        $this->dispatch('filterUpdated', [
            'field' => 'location',
            'value' => $location,
        ]);
        $this->countActiveFilters();
        $this->mobileFiltersVisible = false;
    }

    public function setType($value)
    {
        $this->type = $value;
        \Illuminate\Support\Facades\Log::info('Type set to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'type',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function setBedrooms($value)
    {
        $this->bedrooms = $value;
        \Illuminate\Support\Facades\Log::info('Bedrooms set to: ' . $value);
        $this->dispatch('filterUpdated', [
            'field' => 'bedrooms',
            'value' => $value,
        ]);
        $this->countActiveFilters();
    }

    public function toggleFiltersExpanded()
    {
        $this->filtersExpanded = !$this->filtersExpanded;
    }

    public function toggleMobileFilters()
    {
        $this->mobileFiltersVisible = !$this->mobileFiltersVisible;
    }

    private function countActiveFilters()
    {
        $this->activeFilters = 0;

        if (!empty($this->search)) {
            $this->activeFilters++;
        }
        if (!empty($this->type)) {
            $this->activeFilters++;
        }
        if (!empty($this->status)) {
            $this->activeFilters++;
        }
        if (!empty($this->minPrice)) {
            $this->activeFilters++;
        }
        if (!empty($this->maxPrice)) {
            $this->activeFilters++;
        }
        if (!empty($this->bedrooms)) {
            $this->activeFilters++;
        }
        if (!empty($this->location)) {
            $this->activeFilters++;
        }
    }
}; ?>

<div class="wrapper">
    <div class="property-filters">
        <!-- Tab Buttons -->
        <div class="tab-btns">
            <button class="tab-btn {{ $activeTab === 'buy' ? 'active-btn' : '' }}" wire:click="setActiveTab('buy')">
                <i class="fas fa-home me-2 d-none d-sm-inline"></i>Beli
            </button>
            <button class="tab-btn {{ $activeTab === 'rent' ? 'active-btn' : '' }}" wire:click="setActiveTab('rent')">
                <i class="fas fa-key me-2 d-none d-sm-inline"></i>Sewa
            </button>
            <button class="tab-btn {{ $activeTab === 'all' ? 'active-btn' : '' }}" wire:click="setActiveTab('all')">
                <i class="fas fa-th-large me-2 d-none d-sm-inline"></i>Semua
            </button>

            <!-- Mobile Filters Toggle -->
            <button class="mobile-filters-toggle" wire:click="toggleMobileFilters">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
                @if ($activeFilters > 0)
                    <span class="filter-badge">{{ $activeFilters }}</span>
                @endif
            </button>
        </div>

        <!-- Desktop Filters Row -->
        <div class="filters-row desktop-filters {{ $filtersExpanded ? 'expanded' : '' }}">
            <!-- Search Input -->
            <div class="filter-item search-filter">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Cari properti..."
                        wire:model.live.debounce.300ms="search">
                    @if ($search)
                        <button class="search-clear-btn" wire:click="$set('search', '')">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Location Dropdown -->
            <div class="filter-item dropdown-filter">
                <button class="dropdown-toggle {{ $location ? 'active-filter' : '' }}" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ $location ? Str::limit($location, 15) : 'Lokasi' }}
                </button>
                <div class="dropdown-menu">
                    <div class="px-3 py-2">
                        <label>Masukkan lokasi</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Cari lokasi..."
                                wire:model.live.debounce.300ms="location">
                            @if ($location)
                                <button class="btn btn-outline-secondary" wire:click="$set('location', '')">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="popular-locations">
                        <div class="px-3 py-1">
                            <small class="text-muted">Lokasi Populer</small>
                        </div>
                        <button class="dropdown-item" wire:click="setLocation('Jakarta')">Jakarta</button>
                        <button class="dropdown-item" wire:click="setLocation('Bali')">Bali</button>
                        <button class="dropdown-item" wire:click="setLocation('Bandung')">Bandung</button>
                        <button class="dropdown-item" wire:click="setLocation('Surabaya')">Surabaya</button>
                        <button class="dropdown-item" wire:click="setLocation('Yogyakarta')">Yogyakarta</button>
                    </div>
                </div>
            </div>

            <!-- Type Dropdown -->
            <div class="filter-item dropdown-filter">
                <button class="dropdown-toggle {{ $type ? 'active-filter' : '' }}" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-building me-2"></i>
                    {{ $type ? str_replace('_', ' ', ucfirst($type)) : 'Tipe' }}
                </button>
                <div class="dropdown-menu">
                    <div class="px-3 py-1">
                        <small class="text-muted">Pilih tipe properti</small>
                    </div>
                    @foreach ($propertyTypes as $propertyType)
                        <button class="dropdown-item {{ $type === $propertyType->value ? 'active' : '' }}"
                            wire:click="setType('{{ $propertyType->value }}')">
                            {{ $propertyType->label() }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Price Range Dropdown -->
            <div class="filter-item dropdown-filter">
                <button class="dropdown-toggle {{ $minPrice || $maxPrice ? 'active-filter' : '' }}" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-tag me-2"></i>
                    @if ($minPrice && $maxPrice)
                        {{ 'Rp ' . number_format($minPrice / 1000000000, 1) . 'M - ' . number_format($maxPrice / 1000000000, 1) . 'M' }}
                    @elseif($minPrice)
                        {{ '> Rp ' . number_format($minPrice / 1000000000, 1) . 'M' }}
                    @elseif($maxPrice)
                        {{ '< Rp ' . number_format($maxPrice / 1000000000, 1) . 'M' }}
                    @else
                        Harga
                    @endif
                </button>
                <div class="dropdown-menu price-dropdown">
                    <div class="px-3 py-2">
                        <label>Harga Minimum</label>
                        <select class="form-select mb-3" wire:model.live="minPrice">
                            <option value="">Tidak ada minimum</option>
                            <option value="500000000">Rp 500 Juta</option>
                            <option value="1000000000">Rp 1 Miliar</option>
                            <option value="2000000000">Rp 2 Miliar</option>
                            <option value="5000000000">Rp 5 Miliar</option>
                        </select>

                        <label>Harga Maksimum</label>
                        <select class="form-select" wire:model.live="maxPrice">
                            <option value="">Tidak ada maksimum</option>
                            <option value="1000000000">Rp 1 Miliar</option>
                            <option value="2000000000">Rp 2 Miliar</option>
                            <option value="5000000000">Rp 5 Miliar</option>
                            <option value="10000000000">Rp 10 Miliar</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bedrooms Dropdown -->
            <div class="filter-item dropdown-filter">
                <button class="dropdown-toggle {{ $bedrooms ? 'active-filter' : '' }}" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bed me-2"></i>
                    {{ $bedrooms ? $bedrooms . '+ Kamar' : 'Kamar Tidur' }}
                </button>
                <div class="dropdown-menu">
                    <div class="px-3 py-1">
                        <small class="text-muted">Jumlah kamar tidur minimal</small>
                    </div>
                    <button class="dropdown-item {{ $bedrooms === '' ? 'active' : '' }}"
                        wire:click="setBedrooms('')">Semua</button>
                    <button class="dropdown-item {{ $bedrooms === '1' ? 'active' : '' }}"
                        wire:click="setBedrooms('1')">1+ Kamar</button>
                    <button class="dropdown-item {{ $bedrooms === '2' ? 'active' : '' }}"
                        wire:click="setBedrooms('2')">2+ Kamar</button>
                    <button class="dropdown-item {{ $bedrooms === '3' ? 'active' : '' }}"
                        wire:click="setBedrooms('3')">3+ Kamar</button>
                    <button class="dropdown-item {{ $bedrooms === '4' ? 'active' : '' }}"
                        wire:click="setBedrooms('4')">4+ Kamar</button>
                    <button class="dropdown-item {{ $bedrooms === '5' ? 'active' : '' }}"
                        wire:click="setBedrooms('5')">5+ Kamar</button>
                </div>
            </div>

            <!-- More Filters Dropdown -->
            <div class="filter-item dropdown-filter">
                <button class="dropdown-toggle {{ $status ? 'active-filter' : '' }}" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-sliders-h me-2"></i>
                    Filter Lainnya
                </button>
                <div class="dropdown-menu">
                    @if ($activeTab === 'all')
                        <div class="px-3 py-2">
                            <label>Status</label>
                            <select class="form-select mb-2" wire:model.live="status">
                                <option value="">Semua Status</option>
                                @foreach ($propertyStatuses as $propertyStatus)
                                    <option value="{{ $propertyStatus->value }}">{{ $propertyStatus->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dropdown-divider"></div>
                    @endif
                    <!-- Fitur tambahan -->
                    <div class="px-3 py-2">
                        <p class="text-muted small mb-0">Fitur tambahan akan segera hadir.</p>
                    </div>
                </div>
            </div>

            <!-- Reset Button -->
            <div class="filter-item">
                <button class="reset-btn" wire:click="resetFilters" @if ($activeFilters == 0) disabled @endif>
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
            </div>

            <!-- Toggle Button for Expanding/Collapsing -->
            <div class="filter-item toggle-expand-btn">
                <button wire:click="toggleFiltersExpanded">
                    @if ($filtersExpanded)
                        <i class="fas fa-chevron-up"></i>
                    @else
                        <i class="fas fa-chevron-down"></i>
                    @endif
                </button>
            </div>
        </div>

        <!-- Mobile Filters Panel -->
        <div class="mobile-filters-panel {{ $mobileFiltersVisible ? 'visible' : '' }}">
            <div class="mobile-filters-header">
                <h5>Filter Properti</h5>
                <button class="close-filters-btn" wire:click="toggleMobileFilters">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mobile-filters-body">
                <!-- Search -->
                <div class="filter-section">
                    <label>Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari properti..."
                            wire:model.live.debounce.300ms="search">
                    </div>
                </div>

                <!-- Location -->
                <div class="filter-section">
                    <label>Lokasi</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" class="form-control" placeholder="Masukkan lokasi"
                            wire:model.live.debounce.300ms="location">
                    </div>
                    <div class="popular-locations">
                        <small class="text-muted">Lokasi Populer:</small>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <button class="badge-btn" wire:click="setLocation('Jakarta')">Jakarta</button>
                            <button class="badge-btn" wire:click="setLocation('Bali')">Bali</button>
                            <button class="badge-btn" wire:click="setLocation('Bandung')">Bandung</button>
                            <button class="badge-btn" wire:click="setLocation('Surabaya')">Surabaya</button>
                        </div>
                    </div>
                </div>

                <!-- Property Type -->
                <div class="filter-section">
                    <label>Tipe Properti</label>
                    <select class="form-select" wire:model.live="type">
                        <option value="">Semua Tipe</option>
                        @foreach ($propertyTypes as $propertyType)
                            <option value="{{ $propertyType->value }}">{{ $propertyType->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="filter-section">
                    <label>Rentang Harga</label>
                    <div class="row">
                        <div class="col-6">
                            <label class="small">Minimum</label>
                            <select class="form-select" wire:model.live="minPrice">
                                <option value="">Tidak ada min</option>
                                <option value="500000000">Rp 500 Juta</option>
                                <option value="1000000000">Rp 1 Miliar</option>
                                <option value="2000000000">Rp 2 Miliar</option>
                                <option value="5000000000">Rp 5 Miliar</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small">Maksimum</label>
                            <select class="form-select" wire:model.live="maxPrice">
                                <option value="">Tidak ada max</option>
                                <option value="1000000000">Rp 1 Miliar</option>
                                <option value="2000000000">Rp 2 Miliar</option>
                                <option value="5000000000">Rp 5 Miliar</option>
                                <option value="10000000000">Rp 10 Miliar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bedrooms -->
                <div class="filter-section">
                    <label>Kamar Tidur</label>
                    <div class="bedroom-buttons">
                        <button class="bedroom-btn {{ $bedrooms === '' ? 'active' : '' }}"
                            wire:click="setBedrooms('')">Semua</button>
                        <button class="bedroom-btn {{ $bedrooms === '1' ? 'active' : '' }}"
                            wire:click="setBedrooms('1')">1+</button>
                        <button class="bedroom-btn {{ $bedrooms === '2' ? 'active' : '' }}"
                            wire:click="setBedrooms('2')">2+</button>
                        <button class="bedroom-btn {{ $bedrooms === '3' ? 'active' : '' }}"
                            wire:click="setBedrooms('3')">3+</button>
                        <button class="bedroom-btn {{ $bedrooms === '4' ? 'active' : '' }}"
                            wire:click="setBedrooms('4')">4+</button>
                        <button class="bedroom-btn {{ $bedrooms === '5' ? 'active' : '' }}"
                            wire:click="setBedrooms('5')">5+</button>
                    </div>
                </div>

                <!-- Status (only if All tab is active) -->
                @if ($activeTab === 'all')
                    <div class="filter-section">
                        <label>Status</label>
                        <select class="form-select" wire:model.live="status">
                            <option value="">Semua Status</option>
                            @foreach ($propertyStatuses as $propertyStatus)
                                <option value="{{ $propertyStatus->value }}">{{ $propertyStatus->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <div class="mobile-filters-footer">
                <button class="btn btn-secondary w-100" wire:click="resetFilters"
                    @if ($activeFilters == 0) disabled @endif>
                    <i class="fas fa-undo me-1"></i> Reset Filter
                </button>
                <button class="btn btn-primary w-100" wire:click="toggleMobileFilters">
                    Terapkan Filter <span class="ms-1 badge bg-light text-dark">{{ $activeFilters }}</span>
                </button>
            </div>
        </div>
    </div>
</div>


@push('styles')
    <style>
        .wrapper {
            height: auto;
            padding: 10px 0;
        }

        .property-filters {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .tab-btns {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 8px 15px;
            margin-right: 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #555;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn:hover {
            background-color: #f5f5f5;
        }

        .tab-btn.active-btn {
            background-color: #ffc70b;
            color: white;
        }

        .mobile-filters-toggle {
            display: none;
            margin-left: auto;
            background: #f5f5f5;
            border: none;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 14px;
            align-items: center;
            cursor: pointer;
            position: relative;
        }

        .mobile-filters-toggle i {
            margin-right: 5px;
        }

        .filter-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4040;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filters-row {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            overflow-x: auto;
            padding: 10px 15px;
            scrollbar-width: thin;
        }

        .filters-row::-webkit-scrollbar {
            height: 4px;
        }

        .filters-row::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 4px;
        }

        .filter-item {
            padding: 8px 10px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .search-filter {
            flex: 1;
            min-width: 200px;
        }

        .search-input-group {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .search-filter input {
            padding-left: 35px;
            border-radius: 20px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .search-filter input:focus {
            box-shadow: 0 0 0 3px rgba(255, 199, 11, 0.25);
            border-color: #ffc70b;
        }

        .search-clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
        }

        .dropdown-filter .dropdown-toggle {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
            color: #555;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropdown-filter .dropdown-toggle.active-filter {
            background-color: #fff3d3;
            border-color: #ffc70b;
            color: #333;
        }

        .dropdown-filter .dropdown-toggle:hover,
        .dropdown-filter .dropdown-toggle:focus {
            background-color: #f0f0f0;
        }

        .dropdown-filter .dropdown-menu {
            min-width: 220px;
            padding: 10px 0;
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .price-dropdown {
            min-width: 250px;
        }

        .reset-btn {
            background-color: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reset-btn:hover:not(:disabled) {
            background-color: #f44336;
            color: white;
            border-color: #f44336;
        }

        .reset-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .toggle-expand-btn {
            display: none;
        }

        .toggle-expand-btn button {
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            padding: 5px;
        }

        .dropdown-item.active {
            background-color: #ffc70b;
            color: #fff;
        }

        /* Mobile Filters Panel */
        .mobile-filters-panel {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 350px;
            background-color: white;
            z-index: 1050;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .mobile-filters-panel.visible {
            transform: translateX(0);
        }

        .mobile-filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .mobile-filters-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .close-filters-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
        }

        .mobile-filters-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        .mobile-filters-footer {
            padding: 15px;
            border-top: 1px solid #eee;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-section {
            margin-bottom: 20px;
        }

        .filter-section label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .badge-btn {
            background: #f5f5f5;
            border: none;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            color: #555;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .badge-btn:hover {
            background: #ffc70b;
            color: white;
        }

        .bedroom-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .bedroom-btn {
            flex: 1;
            min-width: 50px;
            background: #f5f5f5;
            border: none;
            padding: 8px;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .bedroom-btn.active {
            background: #ffc70b;
            color: white;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }

        .overlay.visible {
            opacity: 1;
            visibility: visible;
        }
    </style>
@endpush

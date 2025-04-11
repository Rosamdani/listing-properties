<div class="w-100 d-flex gap-3 justify-content-between filter-container">
    <div class="d-flex gap-2 flex-wrap">
        <!-- Price Filter -->
        <div class="dropdown filter-dropdown"> 
            <button type="button" 
                    class="btn btn-white rounded-pill dropdown-toggle filter-btn"
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"> 
                {{ __('Harga') }}<span class="caret ms-1"></span> 
            </button> 
            <div class="dropdown-menu shadow-sm p-3" style="width: 300px;"> 
                <form> 
                    <div class="form-group mb-3"> 
                        <label for="minPrice" class="form-label fw-semibold"> 
                            {{ __('Min Harga') }} 
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span> 
                            <input type="number" class="form-control" 
                                    id="minPrice" 
                                    placeholder="0"
                                    wire:model.defer="filters.minPrice"> 
                        </div>
                    </div> 
                    <div class="form-group mb-3"> 
                        <label for="maxPrice" class="form-label fw-semibold"> 
                            {{ __('Max Harga') }} 
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span> 
                            <input type="number" class="form-control" 
                                    id="maxPrice" 
                                    placeholder="Tidak ada batas"
                                    wire:model.defer="filters.maxPrice"> 
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button" wire:click="applyFilter('price')">Terapkan</button>
                    </div>
                </form> 
            </div> 
        </div>
        
        <!-- Rooms Filter -->
        <div class="dropdown filter-dropdown"> 
            <button type="button" 
                    class="btn btn-white rounded-pill dropdown-toggle filter-btn"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"> 
                {{ __('Jumlah Kamar') }}<span class="caret ms-1"></span> 
            </button> 
            <div class="dropdown-menu shadow-sm p-3" style="width: fit-content;"> 
                <div class="mb-2 fw-semibold">{{ __('Kamar Tidur') }}</div>
                <div class="btn-group d-flex flex-wrap mb-3" role="group">
                    @foreach(range(0, 5) as $i)
                        <input type="radio" class="btn-check" name="bedrooms" id="bedroom{{ $i }}" value="{{ $i }}" wire:model.defer="filters.bedrooms" {{ $i == 0 ? 'autocomplete="off"' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm" for="bedroom{{ $i }}">{{ $i == 5 ? '5+' : $i }}</label>
                    @endforeach
                </div>
                
                <div class="mb-2 fw-semibold">{{ __('Kamar Mandi') }}</div>
                <div class="btn-group d-flex flex-wrap mb-3" role="group">
                    @foreach(range(0, 5) as $i)
                        <input type="radio" class="btn-check" name="bathrooms" id="bathroom{{ $i }}" value="{{ $i }}" wire:model.defer="filters.bathrooms" {{ $i == 0 ? 'autocomplete="off"' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm" for="bathroom{{ $i }}">{{ $i == 5 ? '5+' : $i }}</label>
                    @endforeach
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="button" wire:click="applyFilter('rooms')">Terapkan</button>
                </div>
            </div> 
        </div>
        
        <!-- Property Type Filter -->
        <div class="dropdown filter-dropdown"> 
            <button type="button" 
                    class="btn btn-white rounded-pill dropdown-toggle filter-btn"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"> 
                {{ __('Tipe Properti') }}<span class="caret ms-1"></span> 
            </button> 
            <div class="dropdown-menu shadow-sm p-3" style="width: 300px;"> 
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="rumah" id="propertyTypeRumah" wire:model.defer="filters.propertyTypes">
                    <label class="form-check-label d-flex align-items-center" for="propertyTypeRumah">
                        <i class="flaticon-home me-2"></i> {{ __('Rumah') }}
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="apartemen" id="propertyTypeApartemen" wire:model.defer="filters.propertyTypes">
                    <label class="form-check-label d-flex align-items-center" for="propertyTypeApartemen">
                        <i class="flaticon-building me-2"></i> {{ __('Apartemen') }}
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="ruko" id="propertyTypeRuko" wire:model.defer="filters.propertyTypes">
                    <label class="form-check-label d-flex align-items-center" for="propertyTypeRuko">
                        <i class="flaticon-store me-2"></i> {{ __('Ruko') }}
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="tanah" id="propertyTypeTanah" wire:model.defer="filters.propertyTypes">
                    <label class="form-check-label d-flex align-items-center" for="propertyTypeTanah">
                        <i class="flaticon-land me-2"></i> {{ __('Tanah') }}
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="kantor" id="propertyTypeKantor" wire:model.defer="filters.propertyTypes">
                    <label class="form-check-label d-flex align-items-center" for="propertyTypeKantor">
                        <i class="flaticon-office me-2"></i> {{ __('Kantor') }}
                    </label>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="button" wire:click="applyFilter('propertyType')">Terapkan</button>
                </div>
            </div> 
        </div>
        
        <!-- All Filters Button -->
        <button class="btn btn-white rounded-pill filter-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilters" aria-controls="offcanvasFilters">
            <i class="fas fa-sliders-h me-1"></i> {{ __('Semua Filter') }}
        </button>
    
        <!-- Save Search Button -->
        <button class="btn btn-primary rounded-pill px-4 save-search-btn" type="button">
            <i class="far fa-bookmark me-1"></i> {{ __('Simpan Pencarian') }}
        </button>
    </div>
    
    <!-- View Toggle -->
    <div class="view-toggle">
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="viewType" id="viewTypeSplit" wire:model="viewType" value="split" autocomplete="off" checked>
            <label class="btn btn-light" for="viewTypeSplit">
                <i class="fas fa-columns me-1"></i> Split
            </label>

            <input type="radio" class="btn-check" name="viewType" id="viewTypeMap" wire:model="viewType" value="map" autocomplete="off">
            <label class="btn btn-light" for="viewTypeMap">
                <i class="fas fa-map-marked-alt me-1"></i> Map
            </label>

            <input type="radio" class="btn-check" name="viewType" id="viewTypeList" wire:model="viewType" value="list" autocomplete="off">
            <label class="btn btn-light" for="viewTypeList">
                <i class="fas fa-list me-1"></i> List
            </label>
        </div>
    </div>
</div>

@push('scripts')
<!-- Offcanvas All Filters (Redfin-like) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilters" aria-labelledby="offcanvasFiltersLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="offcanvasFiltersLabel">{{ __('Semua Filter') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="container-fluid p-0">
            <form wire:submit.prevent="applyAllFilters">
                <!-- Price Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Harga') }}</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="minPriceAll" class="form-label">{{ __('Min Harga') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="minPriceAll" placeholder="0" wire:model.defer="filters.minPrice">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="maxPriceAll" class="form-label">{{ __('Max Harga') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="maxPriceAll" placeholder="Tidak ada batas" wire:model.defer="filters.maxPrice">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Property Type Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Tipe Properti') }}</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="rumah" id="allPropertyTypeRumah" wire:model.defer="filters.propertyTypes">
                                <label class="form-check-label" for="allPropertyTypeRumah">
                                    <i class="flaticon-home me-2"></i> {{ __('Rumah') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="apartemen" id="allPropertyTypeApartemen" wire:model.defer="filters.propertyTypes">
                                <label class="form-check-label" for="allPropertyTypeApartemen">
                                    <i class="flaticon-building me-2"></i> {{ __('Apartemen') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="ruko" id="allPropertyTypeRuko" wire:model.defer="filters.propertyTypes">
                                <label class="form-check-label" for="allPropertyTypeRuko">
                                    <i class="flaticon-store me-2"></i> {{ __('Ruko') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="tanah" id="allPropertyTypeTanah" wire:model.defer="filters.propertyTypes">
                                <label class="form-check-label" for="allPropertyTypeTanah">
                                    <i class="flaticon-land me-2"></i> {{ __('Tanah') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="kantor" id="allPropertyTypeKantor" wire:model.defer="filters.propertyTypes">
                                <label class="form-check-label" for="allPropertyTypeKantor">
                                    <i class="flaticon-office me-2"></i> {{ __('Kantor') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rooms Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Kamar') }}</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Kamar Tidur') }}</label>
                        <div class="btn-group d-flex flex-wrap" role="group">
                            @foreach(range(0, 5) as $i)
                                <input type="radio" class="btn-check" name="allBedrooms" id="allBedroom{{ $i }}" value="{{ $i }}" wire:model.defer="filters.bedrooms">
                                <label class="btn btn-outline-secondary" for="allBedroom{{ $i }}">{{ $i == 5 ? '5+' : $i }}</label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Kamar Mandi') }}</label>
                        <div class="btn-group d-flex flex-wrap" role="group">
                            @foreach(range(0, 5) as $i)
                                <input type="radio" class="btn-check" name="allBathrooms" id="allBathroom{{ $i }}" value="{{ $i }}" wire:model.defer="filters.bathrooms">
                                <label class="btn btn-outline-secondary" for="allBathroom{{ $i }}">{{ $i == 5 ? '5+' : $i }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Size Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Ukuran') }}</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="minSizeAll" class="form-label">{{ __('Min (m²)') }}</label>
                                <input type="number" class="form-control" id="minSizeAll" placeholder="0" wire:model.defer="filters.minSize">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="maxSizeAll" class="form-label">{{ __('Max (m²)') }}</label>
                                <input type="number" class="form-control" id="maxSizeAll" placeholder="Tidak ada batas" wire:model.defer="filters.maxSize">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Year Built Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Tahun Dibangun') }}</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="minYearAll" class="form-label">{{ __('Dari') }}</label>
                                <select class="form-select" id="minYearAll" wire:model.defer="filters.minYear">
                                    <option value="">Kapan saja</option>
                                    @foreach(range(date('Y'), 1950, -5) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="maxYearAll" class="form-label">{{ __('Sampai') }}</label>
                                <select class="form-select" id="maxYearAll" wire:model.defer="filters.maxYear">
                                    <option value="">Kapan saja</option>
                                    @foreach(range(date('Y'), 1950, -5) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Features Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Fitur') }}</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="ac" id="featureAc" wire:model.defer="filters.features">
                                <label class="form-check-label" for="featureAc">
                                    {{ __('AC') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="pool" id="featurePool" wire:model.defer="filters.features">
                                <label class="form-check-label" for="featurePool">
                                    {{ __('Kolam Renang') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="garage" id="featureGarage" wire:model.defer="filters.features">
                                <label class="form-check-label" for="featureGarage">
                                    {{ __('Garasi') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="furnished" id="featureFurnished" wire:model.defer="filters.features">
                                <label class="form-check-label" for="featureFurnished">
                                    {{ __('Furnished') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="security" id="featureSecurity" wire:model.defer="filters.features">
                                <label class="form-check-label" for="featureSecurity">
                                    {{ __('Keamanan 24 Jam') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="petfriendly" id="featurePet" wire:model.defer="filters.features">
                                <label class="form-check-label" for="featurePet">
                                    {{ __('Pet Friendly') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Listing Status Section -->
                <div class="filter-section mb-4">
                    <h6 class="fw-bold mb-3">{{ __('Status Listing') }}</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="sale" id="statusSale" wire:model.defer="filters.status">
                        <label class="form-check-label" for="statusSale">
                            {{ __('Dijual') }}
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="rent" id="statusRent" wire:model.defer="filters.status">
                        <label class="form-check-label" for="statusRent">
                            {{ __('Disewa') }}
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="new" id="statusNew" wire:model.defer="filters.status">
                        <label class="form-check-label" for="statusNew">
                            {{ __('Listing Baru') }}
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas-footer p-3 border-top">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">
                {{ __('Reset') }}
            </button>
            <button type="button" class="btn btn-primary" wire:click="applyAllFilters">
                {{ __('Terapkan Filter') }}
            </button>
        </div>
    </div>
</div>
@endpush

@push('styles')
<style>
    /* Filter container styling */
    .filter-container {
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    /* Filter button styling */
    .filter-btn {
        font-weight: 500;
        border: 1px solid #dee2e6;
        padding: 8px 16px;
        transition: all 0.2s ease;
    }
    
    .filter-btn:hover {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }
    
    /* Dropdown styling */
    .filter-dropdown .dropdown-menu {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    /* Save search button styling */
    .save-search-btn {
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    /* View toggle button styling */
    .view-toggle .btn {
        border: 1px solid #dee2e6;
        font-weight: 500;
    }
    
    .view-toggle .btn-check:checked + .btn {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    /* Offcanvas styling */
    .offcanvas {
        width: 650px;
        max-width: 90vw;
    }
    
    .filter-section {
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 16px;
    }
    
    .filter-section:last-child {
        border-bottom: none;
    }
    
    /* Form control styling */
    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
    }
    
    /* Button group styling for filter options */
    .btn-group .btn {
        padding: 0.375rem 0.75rem;
    }
    
    .btn-group .btn-check:checked + .btn-outline-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .filter-container {
            flex-wrap: wrap;
        }
        
        .view-toggle {
            margin-top: 10px;
            width: 100%;
        }
        
        .view-toggle .btn-group {
            width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .offcanvas {
            width: 100%;
        }
    }
</style>
@endpush
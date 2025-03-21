<?php

use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Models\Properties;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;

    // Tambahkan tema pagination
    protected $paginationTheme = 'bootstrap';

    #[Url]
    public $search = '';

    #[Url]
    public $type = '';

    #[Url]
    public $status = '';

    #[Url]
    public $minPrice = '';

    #[Url]
    public $maxPrice = '';

    #[Url]
    public $bedrooms = '';

    #[Url]
    public $location = '';

    // Tambahkan variabel untuk melacak tab aktif
    #[Url]
    public $activeTab = 'buy'; // default tab adalah 'buy'

    // Tambahkan variabel untuk melacak halaman pagination
    #[Url]
    public $page = 1;

    #[Url]
    public $bounds = null;

    // Sort option
    #[Url]
    public $sort = 'latest';

    // Tambahkan variabel untuk melacak apakah filter bounds aktif
    public $boundsFilterActive = false;

    // Current view state
    public $currentView = 'split'; // split, map, list

    // Loading state
    public $isLoading = false;

    public function getPropertyTypesProperty()
    {
        return PropertyType::cases();
    }

    public function getPropertyStatusesProperty()
    {
        return PropertyStatus::cases();
    }

    public function getFilteredQueryProperty()
    {
        \Illuminate\Support\Facades\Log::info('Building filtered query with filters:', [
            'search' => $this->search,
            'type' => $this->type,
            'status' => $this->status,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'bedrooms' => $this->bedrooms,
            'location' => $this->location,
            'activeTab' => $this->activeTab,
            'sort' => $this->sort,
        ]);

        $query = Properties::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->activeTab, function ($query) {
                if ($this->activeTab === 'buy') {
                    $query->where('status', PropertyStatus::FOR_SALE->value);
                } elseif ($this->activeTab === 'rent') {
                    $query->where('status', PropertyStatus::FOR_RENT->value);
                }
            })
            ->when($this->status && $this->activeTab === 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->minPrice, function ($query) {
                $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function ($query) {
                $query->where('price', '<=', $this->maxPrice);
            })
            ->when($this->bedrooms, function ($query) {
                $query->where('bedrooms', '>=', $this->bedrooms);
            })
            ->when($this->location, function ($query) {
                // Coba cari properti dengan lokasi yang cocok
                $query->where(function ($q) {
                    // Cari di lokasi langsung
                    $q->where('location', 'like', '%' . $this->location . '%')
                        // Atau cari di provinsi (bagian terakhir dari lokasi)
                        ->orWhereRaw('LOWER(TRIM(SUBSTRING_INDEX(location, ",", -1))) LIKE ?', ['%' . strtolower(trim($this->location)) . '%']);
                });
            })
            ->when($this->boundsFilterActive && $this->bounds, function ($query) {
                // Periksa apakah bounds sudah dalam bentuk array atau masih string JSON
                $bounds = is_string($this->bounds) ? json_decode($this->bounds, true) : $this->bounds;

                if ($bounds && is_array($bounds)) {
                    $query->whereBetween('latitude', [$bounds['south'], $bounds['north']])->whereBetween('longitude', [$bounds['west'], $bounds['east']]);
                }
            });

        // Apply sorting
        switch ($this->sort) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        return $query;
    }

    public function getMapPropertiesProperty()
    {
        $properties = $this->filteredQuery
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(500) // Limit to 500 properties for performance
            ->get();

        $mappedProperties = $properties
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'name' => $property->name,
                    'lat' => $property->latitude,
                    'lng' => $property->longitude,
                    'price' => $property->price,
                    'formatted_price' => $property->formatted_price ?? number_format($property->price, 0, ',', '.'),
                    'status' => $property->status,
                    'type' => $property->type,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'area' => $property->area,
                    'thumbnail' => $property->thumbnail,
                    'slug' => $property->slug,
                    'location' => $property->location,
                ];
            })
            ->toArray();

        \Illuminate\Support\Facades\Log::info('Map properties prepared:', [
            'count' => count($mappedProperties),
        ]);

        return $mappedProperties;
    }

    public function getPropertiesProperty()
    {
        // Start with loading state
        $this->isLoading = true;

        \Illuminate\Support\Facades\Log::info('Getting properties with pagination...');
        $paginator = $this->filteredQuery->paginate(6);

        $result = [
            'data' => $paginator->items(),
            'links' => $paginator->links()->elements[0] ?? [],
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
        ];

        \Illuminate\Support\Facades\Log::info('Properties prepared:', [
            'count' => count($result['data']),
            'total' => $result['total'],
        ]);

        // End loading state
        $this->isLoading = false;

        return $result;
    }

    // Metode untuk mengatur tab aktif
    #[Livewire\Attributes\On('setActiveTab')]
    public function setActiveTab($tab)
    {
        \Illuminate\Support\Facades\Log::info('Active tab set to: ' . $tab);
        $this->activeTab = $tab;
        $this->status = '';
        $this->resetPage();
        $this->dispatch('resetMapView');
        $this->dispatch('propertiesUpdated');
    }

    #[Livewire\Attributes\On('resetFilters')]
    public function resetFilters()
    {
        \Illuminate\Support\Facades\Log::info('Resetting all filters');
        $this->search = '';
        $this->type = '';
        $this->status = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->bedrooms = '';
        $this->location = '';
        $this->resetPage();
        $this->boundsFilterActive = false;
        $this->bounds = null;

        $this->dispatch('resetMapView');
        $this->dispatch('propertiesUpdated');
    }

    #[Livewire\Attributes\On('setLocation')]
    public function setLocation($params = null)
    {
        // Pastikan $params adalah array dan ekstrak location dan provinceCode
        if (is_array($params)) {
            $location = $params['location'] ?? '';
            $provinceCode = $params['provinceCode'] ?? '';
        } else {
            $location = $params ?? '';
            $provinceCode = '';
        }

        // Pastikan lokasi tidak kosong
        if (!empty($location)) {
            // Normalisasi lokasi (hapus spasi berlebih)
            $location = trim($location);

            // Set lokasi dan reset filter bounds
            $this->location = $location;
            $this->boundsFilterActive = false;
            $this->resetPage();

            // Log untuk debugging
            \Illuminate\Support\Facades\Log::info('Filter lokasi diatur ke: ' . $location . ', Kode Provinsi: ' . $provinceCode);

            // Dispatch event untuk zoom ke provinsi jika ada kode provinsi
            if (!empty($provinceCode)) {
                $this->dispatch('zoomToProvince', ['provinceCode' => $provinceCode]);
            }
        }

        $this->dispatch('propertiesUpdated');
    }

    #[Livewire\Attributes\On('sortPropertiesBy')]
    public function sortProperties($option)
    {
        \Illuminate\Support\Facades\Log::info('Sorting properties by: ' . $option);
        $this->sort = $option;
        $this->resetPage();
        $this->dispatch('propertiesUpdated');
    }

    public function toggleView($view)
    {
        \Illuminate\Support\Facades\Log::info('View toggled to: ' . $view);
        $this->currentView = $view;
    }

    // KEY FIX: Add event listener for filterUpdated from property-filters component
    #[Livewire\Attributes\On('filterUpdated')]
    public function handleFilterUpdate($params)
    {
        $field = $params['field'] ?? null;
        $value = $params['value'] ?? null;

        \Illuminate\Support\Facades\Log::info('Handling filter update:', [
            'field' => $field,
            'value' => $value,
        ]);

        if ($field && isset($value)) {
            // Update nilai filter
            $this->{$field} = $value;

            // Reset halaman pagination
            $this->resetPage();

            // Dispatch event untuk memperbarui properti di peta dan list
            $this->dispatch('propertiesUpdated');

            // Dispatch event to update filter status for UI feedback
            $this->dispatch('updateFilterStatus', !empty($value), $field === 'search' ? 'Pencarian: ' . $value : ($field === 'location' ? 'Lokasi: ' . $value : null));
        }
    }

    // Tambahkan method untuk navigasi pagination dengan Livewire
    #[Livewire\Attributes\On('gotoPage')]
    public function gotoPage($page)
    {
        \Illuminate\Support\Facades\Log::info('Going to page: ' . $page);

        if (is_numeric($page)) {
            $this->page = (int) $page;
        } else {
            // Handle "Previous" and "Next" links
            if (strpos($page, 'Previous') !== false) {
                $this->page = max(1, $this->page - 1);
            } elseif (strpos($page, 'Next') !== false) {
                $this->page = min($this->getPropertiesProperty()['last_page'], $this->page + 1);
            }
        }
        $this->dispatch('propertiesUpdated');
    }

    #[Livewire\Attributes\On('updateMapBounds')]
    public function updateMapBounds($bounds)
    {
        $this->bounds = $bounds;
    }

    #[Livewire\Attributes\On('filterByBounds')]
    public function filterByBounds($params)
    {
        // Ekstrak bounds dari parameter
        $bounds = $params['bounds'] ?? null;

        \Illuminate\Support\Facades\Log::info('Filtering by bounds:', [
            'bounds' => $bounds,
        ]);

        if ($bounds) {
            $this->bounds = $bounds;
            $this->boundsFilterActive = true;
            $this->dispatch('propertiesUpdated');
        }
    }

    #[Livewire\Attributes\On('resetFilter')]
    public function resetFilter()
    {
        \Illuminate\Support\Facades\Log::info('Resetting bounds filter');
        $this->boundsFilterActive = false;
        $this->dispatch('propertiesUpdated');
    }

    protected function findProvinceCodeAndZoom($locationName)
    {
        // Ini adalah fungsi sederhana untuk mencoba menemukan kode provinsi
        // Dalam implementasi nyata, Anda mungkin perlu query database atau API
        $provinceMapping = [
            'jakarta' => '31',
            'bali' => '51',
            'bandung' => '32', // Ini sebenarnya kode Jawa Barat
            'surabaya' => '35', // Ini sebenarnya kode Jawa Timur
            'yogyakarta' => '34',
            'jawa barat' => '32',
            'jawa tengah' => '33',
            'jawa timur' => '35',
            'sumatera utara' => '12',
            'sumatera selatan' => '16',
            'kalimantan timur' => '64',
            'sulawesi selatan' => '73',
        ];

        // Normalisasi nama lokasi
        $normalizedName = strtolower(trim($locationName));

        // Coba temukan kode provinsi yang cocok
        foreach ($provinceMapping as $provinceName => $provinceCode) {
            if (strpos($normalizedName, $provinceName) !== false) {
                // Jika ditemukan, dispatch event untuk zoom ke provinsi
                $this->dispatch('zoomToProvince', ['provinceCode' => $provinceCode]);
                \Illuminate\Support\Facades\Log::info("Menemukan kode provinsi untuk {$locationName}: {$provinceCode}");
                return;
            }
        }

        \Illuminate\Support\Facades\Log::info("Tidak dapat menemukan kode provinsi untuk: {$locationName}");
    }

    // Refresh method to force re-rendering
    public function refreshProperties()
    {
        \Illuminate\Support\Facades\Log::info('Manually refreshing properties');
        $this->dispatch('propertiesUpdated');
    }
}; ?>

<div class="property-page-container" x-data="{ mobileMapVisible: false }">
    <div class="filter-container">
        <!-- Filter Component -->
        <livewire:pages.properties.components.property-filters :search="$search" :type="$type" :status="$status"
            :min-price="$minPrice" :max-price="$maxPrice" :bedrooms="$bedrooms" :location="$location" :active-tab="$activeTab" :property-types="$this->propertyTypes"
            :property-statuses="$this->propertyStatuses" />

        @if ($boundsFilterActive)
            <div class="bounds-filter-indicator">
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-map-marker-alt me-2"></i> Menampilkan properti di area yang terlihat pada
                        peta</span>
                    <button wire:click="resetFilter" class="btn btn-sm btn-outline-secondary">Hapus Filter Area</button>
                </div>
            </div>
        @endif

        <div class="view-toggle-buttons d-md-none">
            <div class="btn-group" role="group">
                <button type="button" class="btn {{ $currentView === 'list' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="toggleView('list')">
                    <i class="fas fa-list"></i> List
                </button>
                <button type="button" class="btn {{ $currentView === 'map' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="toggleView('map')">
                    <i class="fas fa-map-marked-alt"></i> Map
                </button>
            </div>
        </div>
    </div>

    <div class="content-container {{ $currentView }}">
        <div class="map-container {{ $currentView === 'list' ? 'd-none d-md-block' : '' }}">
            <!-- Map Component -->
            <livewire:pages.properties.components.property-map :properties="$this->mapProperties"
                wire:key="map-component-{{ $search }}-{{ $type }}-{{ $bedrooms }}-{{ $location }}" />
        </div>

        <div class="list-container {{ $currentView === 'map' ? 'd-none d-md-block' : '' }}">
            <!-- List Component -->
            <livewire:pages.properties.components.property-list :properties="$this->properties"
                wire:key="list-component-{{ $search }}-{{ $type }}-{{ $bedrooms }}-{{ $location }}-{{ $page }}-{{ $sort }}" />
        </div>
    </div>

    <!-- Debug Info (remove in production) -->
    <div class="debug-panel">
        <details>
            <summary>Debug Information</summary>
            <ul>
                <li>Search: {{ $search }}</li>
                <li>Type: {{ $type }}</li>
                <li>Status: {{ $status }}</li>
                <li>MinPrice: {{ $minPrice }}</li>
                <li>MaxPrice: {{ $maxPrice }}</li>
                <li>Bedrooms: {{ $bedrooms }}</li>
                <li>Location: {{ $location }}</li>
                <li>Page: {{ $page }}</li>
                <li>Sort: {{ $sort }}</li>
                <li>Properties Count: {{ isset($this->properties['total']) ? $this->properties['total'] : 'N/A' }}</li>
            </ul>
            <button wire:click="refreshProperties" class="btn btn-sm btn-secondary">Force Refresh</button>
        </details>
    </div>

    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle-view d-md-none" x-show="$wire.currentView === 'list'" wire:click="toggleView('map')">
        <i class="fas fa-map-marked-alt"></i>
    </button>
    <button class="mobile-toggle-view d-md-none" x-show="$wire.currentView === 'map'" wire:click="toggleView('list')">
        <i class="fas fa-list"></i>
    </button>
</div>

@push('styles')
    <style>
        /* Full page layout */
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .property-page-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
        }

        .filter-container {
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            z-index: 1000;
        }

        .bounds-filter-indicator {
            margin-top: 10px;
        }

        .content-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .content-container.split .map-container,
        .content-container.split .list-container {
            width: 50%;
            flex: 0 0 50%;
        }

        .content-container.map .map-container {
            width: 100%;
            flex: 0 0 100%;
        }

        .content-container.list .list-container {
            width: 100%;
            flex: 0 0 100%;
        }

        .map-container {
            height: 100%;
            position: relative;
            transition: all 0.3s ease;
        }

        .list-container {
            height: 100%;
            overflow-y: auto;
            padding: 15px;
            scrollbar-width: thin;
            transition: all 0.3s ease;
        }

        .list-container::-webkit-scrollbar {
            width: 6px;
        }

        .list-container::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 3px;
        }

        .list-container::-webkit-scrollbar-track {
            background-color: #f5f5f5;
        }

        #map {
            height: 100% !important;
            width: 100%;
        }

        /* Toggle Buttons for mobile */
        .view-toggle-buttons {
            margin-top: 10px;
            text-align: center;
        }

        .view-toggle-buttons .btn-group {
            width: 100%;
        }

        .view-toggle-buttons .btn {
            flex: 1;
            padding: 10px;
            font-weight: 600;
        }

        .mobile-toggle-view {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ffc70b;
            color: white;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .mobile-toggle-view:hover,
        .mobile-toggle-view:focus {
            background-color: #e6b300;
            transform: scale(1.1);
        }

        /* Debug panel */
        .debug-panel {
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            font-size: 12px;
            z-index: 1000;
            max-width: 300px;
            max-height: 300px;
            overflow-y: auto;
        }

        .debug-panel summary {
            font-weight: bold;
            cursor: pointer;
        }

        .debug-panel ul {
            padding-left: 15px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Hide footer on property pages */
        footer,
        .page-title,
        .page-banner,
        .hero-section {
            display: none !important;
        }

        /* Responsive Styles */
        @media (min-width: 1400px) {
            .content-container.split .map-container {
                width: 60%;
                flex: 0 0 60%;
            }

            .content-container.split .list-container {
                width: 40%;
                flex: 0 0 40%;
            }
        }

        @media (min-width: 1200px) and (max-width: 1399px) {
            .content-container.split .map-container {
                width: 55%;
                flex: 0 0 55%;
            }

            .content-container.split .list-container {
                width: 45%;
                flex: 0 0 45%;
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {

            .content-container.split .map-container,
            .content-container.split .list-container {
                width: 50%;
                flex: 0 0 50%;
            }
        }

        @media (max-width: 992px) {
            .property-page-container {
                height: calc(100vh - 60px);
                /* Adjust for header height */
            }

            .content-container {
                flex-direction: column;
                height: auto;
            }

            .content-container.split .map-container,
            .content-container.split .list-container {
                width: 100%;
                flex: 0 0 100%;
            }

            .content-container.split .map-container {
                height: 40vh;
            }

            .content-container.split .list-container {
                height: calc(60vh - 130px);
            }
        }

        @media (max-width: 768px) {
            .property-page-container {
                height: calc(100vh - 56px);
                /* Adjust for smaller header height */
            }

            .filter-container {
                padding: 10px;
            }

            .content-container.list .map-container,
            .content-container.map .list-container {
                display: none;
            }

            .content-container.list .list-container,
            .content-container.map .map-container {
                height: calc(100vh - 170px);
                /* Adjust for filter container and header */
                overflow-y: auto;
            }

            .debug-panel {
                max-width: 150px;
                font-size: 10px;
            }
        }

        @media (max-width: 576px) {
            .filter-container {
                padding: 5px;
            }

            .list-container {
                padding: 10px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    Livewire.dispatch('dispatch', {
                        name: 'toggleView',
                        data: 'split'
                    });
                }
            });

            Livewire.on('propertiesUpdated', function() {
                console.log('Properties updated event received');

                setTimeout(function() {
                    window.dispatchEvent(new Event('resize'));
                }, 300);
            });

            Livewire.on('filterUpdated', function(params) {
                console.log('Filter updated:', params);
            });

            Livewire.on('viewToggled', function(view) {
                console.log('View toggled to:', view);

                if (view === 'map') {
                    setTimeout(function() {
                        window.dispatchEvent(new Event('resize'));
                    }, 300);
                }
            });
        });
    </script>
@endpush

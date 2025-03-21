<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $properties;
    public $isFiltering = false;
    public $currentFilter = null;
    public $loading = false;
    public $highlightedProperty = null;
    public $viewMode = 'grid'; // grid or list
    public $sortOption = 'latest'; // latest, price-asc, price-desc

    public function mount($properties)
    {
        $this->properties = $properties;
        \Illuminate\Support\Facades\Log::info('Property list mounted with properties:', [
            'count' => count($properties['data'] ?? []),
            'total' => $properties['total'] ?? 0,
        ]);
    }

    public function loadingState()
    {
        $this->loading = true;
    }

    #[On('updateFilterStatus')]
    public function updateFilterStatus($status, $filter = null)
    {
        \Illuminate\Support\Facades\Log::info('Filter status updated:', [
            'status' => $status,
            'filter' => $filter,
        ]);

        $this->isFiltering = $status;
        $this->currentFilter = $filter;
    }

    #[On('highlightProperty')]
    public function highlightProperty($propertyId)
    {
        $this->highlightedProperty = $propertyId;
    }

    #[On('resetHighlight')]
    public function resetHighlight()
    {
        $this->highlightedProperty = null;
    }

    // Add listener for filtered properties
    #[On('propertiesUpdated')]
    public function handlePropertiesUpdated()
    {
        \Illuminate\Support\Facades\Log::info('Properties updated event received in list component');
        $this->loading = true;
    }

    // Add listener for filterUpdated event
    #[On('filterUpdated')]
    public function handleFilterUpdated($params)
    {
        \Illuminate\Support\Facades\Log::info('Filter updated in list component:', $params);
        // This will be handled by the parent component, but we can show loading state
        $this->loading = true;
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function setSortOption($option)
    {
        $this->sortOption = $option;
        \Illuminate\Support\Facades\Log::info('Sort option set to: ' . $option);
        $this->dispatch('sortPropertiesBy', $option);
    }

    public function focusOnProperty($id, $lat, $lng)
    {
        \Illuminate\Support\Facades\Log::info('Focus on property:', [
            'id' => $id,
            'lat' => $lat,
            'lng' => $lng,
        ]);

        $this->dispatch('focusOnProperty', [
            'id' => $id,
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }
}; ?>

<div>
    <div class="loading-overlay" wire:loading>
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @if ($isFiltering && $currentFilter)
        <div class="alert alert-info mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div><i class="fas fa-filter me-2"></i> Menampilkan properti dengan filter:
                    <strong>{{ $currentFilter }}</strong>
                </div>
                <button type="button" class="btn-close" wire:click="$dispatch('resetFilters')"></button>
            </div>
        </div>
    @endif

    <div class="list-controls mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="results-count">
                @if (isset($properties['total']))
                    <span class="text-muted">
                        Menampilkan {{ $properties['from'] ?? 0 }} - {{ $properties['to'] ?? 0 }} dari
                        <strong>{{ $properties['total'] ?? 0 }}</strong> properti
                    </span>
                @endif
            </div>
            <div class="d-flex align-items-center">
                <div class="sort-options me-3">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i>
                            @if ($sortOption === 'latest')
                                Terbaru
                            @elseif($sortOption === 'price-asc')
                                Harga: Rendah ke Tinggi
                            @elseif($sortOption === 'price-desc')
                                Harga: Tinggi ke Rendah
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ $sortOption === 'latest' ? 'active' : '' }}" href="#"
                                    wire:click.prevent="setSortOption('latest')">
                                    <i class="fas fa-clock me-2"></i> Terbaru
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sortOption === 'price-asc' ? 'active' : '' }}"
                                    href="#" wire:click.prevent="setSortOption('price-asc')">
                                    <i class="fas fa-sort-amount-down-alt me-2"></i> Harga: Rendah ke Tinggi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sortOption === 'price-desc' ? 'active' : '' }}"
                                    href="#" wire:click.prevent="setSortOption('price-desc')">
                                    <i class="fas fa-sort-amount-up me-2"></i> Harga: Tinggi ke Rendah
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="view-options">
                    <div class="btn-group btn-group-sm">
                        <button type="button"
                            class="btn {{ $viewMode === 'grid' ? 'btn-secondary' : 'btn-outline-secondary' }}"
                            wire:click="toggleViewMode" title="Grid view">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button"
                            class="btn {{ $viewMode === 'list' ? 'btn-secondary' : 'btn-outline-secondary' }}"
                            wire:click="toggleViewMode" title="List view">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix {{ $viewMode === 'list' ? 'list-view-container' : '' }}">
        @if (isset($properties['data']))
            @forelse($properties['data'] as $property)
                <div class="property-item {{ $viewMode === 'grid' ? 'col-lg-6 col-md-6 col-sm-12' : 'col-12' }} mb-4"
                    x-data="{}"
                    x-on:mouseenter="$dispatch('property-hover', {id: '{{ $property['id'] }}', lat: '{{ $property['latitude'] }}', lng: '{{ $property['longitude'] }}'})"
                    x-on:mouseleave="$dispatch('property-unhover')">
                    <div
                        class="property-block {{ $viewMode === 'grid' ? 'property-grid' : 'property-list' }} {{ $highlightedProperty === $property['id'] ? 'property-highlighted' : '' }}">
                        <div class="property-image">
                            @if ($property['featured'])
                                <div class="property-badge property-badge-featured">Featured</div>
                            @endif
                            <div
                                class="property-badge property-badge-status {{ $property['status'] === 'for_sale' ? 'property-badge-sale' : 'property-badge-rent' }}">
                                {{ $property['status'] === 'for_sale' ? 'Dijual' : 'Disewa' }}
                            </div>
                            <a href="{{ route('property.show', $property['slug']) }}" class="property-image-link">
                                <img src="{{ $property['thumbnail'] ?? asset('assets/images/placeholder.jpg') }}"
                                    alt="{{ $property['name'] }}" class="property-img" />
                                <div class="property-overlay">
                                    <button class="btn-view-on-map"
                                        wire:click.prevent="focusOnProperty('{{ $property['id'] }}', {{ $property['latitude'] }}, {{ $property['longitude'] }})">
                                        <i class="fas fa-map-marker-alt"></i> Lihat di Peta
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="property-content">
                            <div class="property-location">
                                <i class="fas fa-map-marker-alt"></i> {{ $property['location'] }}
                            </div>
                            <h4 class="property-title">
                                <a href="{{ route('property.show', $property['slug']) }}">{{ $property['name'] }}</a>
                            </h4>
                            <div class="property-price">
                                Rp {{ number_format($property['price'], 0, ',', '.') }}
                                @if ($property['status'] === 'for_rent')
                                    <span class="price-period">/bulan</span>
                                @endif
                            </div>
                            <ul class="property-features">
                                <li><i class="fas fa-bed"></i> <span>{{ $property['bedrooms'] }}</span>
                                    {{ __('Kmr') }}</li>
                                <li><i class="fas fa-bath"></i> <span>{{ $property['bathrooms'] }}</span>
                                    {{ __('Kmr Mnd') }}</li>
                                <li><i class="fas fa-ruler-combined"></i>
                                    <span>{{ number_format($property['area']) }}</span> m²
                                </li>
                            </ul>
                            @if ($viewMode === 'list')
                                <div class="property-description">
                                    <p>{{ Str::limit($property['description'] ?? 'No description available', 150) }}
                                    </p>
                                </div>
                            @endif
                            <div class="property-actions">
                                <a class="btn-property-details" href="{{ route('property.show', $property['slug']) }}">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                                <button class="btn-property-locate"
                                    wire:click.prevent="focusOnProperty('{{ $property['id'] }}', {{ $property['latitude'] }}, {{ $property['longitude'] }})">
                                    <i class="fas fa-map-marker-alt"></i> Lokasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Tidak ada properti yang ditemukan. Coba sesuaikan filter
                        Anda.
                    </div>
                </div>
            @endforelse

            <!-- Pagination -->
            @if (isset($properties['links']) && count($properties['links']) > 3)
                <div class="mt-4">
                    <nav>
                        <ul class="pagination justify-content-center">
                            @foreach ($properties['links'] as $link)
                                <li
                                    class="page-item {{ $link['active'] ? 'active' : '' }} {{ !$link['url'] ? 'disabled' : '' }}">
                                    <a class="page-link" href="#"
                                        wire:click.prevent="$dispatch('gotoPage', '{{ $link['label'] }}')">{!! $link['label'] !!}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            @endif
        @else
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Format data properti tidak valid.
                </div>
            </div>
        @endif
    </div>

    <!-- Property List Footer Banner -->
    <div class="property-list-footer mt-5">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-section">
                    <h5><i class="fas fa-info-circle me-2"></i> Tentang Kami</h5>
                    <p>Herd Property adalah platform properti terkemuka di Indonesia yang menyediakan layanan jual
                        beli dan sewa properti dengan pengalaman terbaik.</p>
                    <p class="mb-0">
                        <a href="#" class="footer-link">
                            <i class="fas fa-angle-right me-1"></i> Pelajari lebih lanjut
                        </a>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-section">
                    <h5><i class="fas fa-phone-alt me-2"></i> Kontak</h5>
                    <ul class="list-unstyled contact-list">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Sudirman No. 123, Jakarta</li>
                        <li><i class="fas fa-phone me-2"></i> +62 812-3456-7890</li>
                        <li><i class="fas fa-envelope me-2"></i> info@herdproperty.com</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="footer-section">
                    <h5><i class="fas fa-share-alt me-2"></i> Ikuti Kami</h5>
                    <div class="social-icons">
                        <a href="#" class="social-icon" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <div class="newsletter mt-3">
                        <h6><i class="fas fa-paper-plane me-2"></i> Berlangganan Newsletter</h6>
                        <div class="input-group mt-2">
                            <input type="email" class="form-control" placeholder="Email Anda">
                            <button class="btn btn-warning" type="button">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-3">
            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <div>&copy; {{ date('Y') }} Herd Property. All rights reserved.</div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms of Service</a> |
                    <a href="#">FAQ</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Loading Overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            backdrop-filter: blur(2px);
        }

        /* List Controls */
        .list-controls {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-group .btn {
            border-radius: 4px;
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .dropdown-menu .dropdown-item.active {
            background-color: #ffc70b;
            color: white;
        }

        /* Property Item Styles */
        .property-item {
            transition: transform 0.3s ease;
        }

        .property-block {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .property-block:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .property-highlighted {
            box-shadow: 0 0 0 3px #ffc70b, 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .property-image {
            position: relative;
            overflow: hidden;
        }

        .property-grid .property-image {
            height: 200px;
        }

        .property-list .property-image {
            width: 35%;
            float: left;
            height: 100%;
            min-height: 220px;
        }

        .property-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .property-image-link {
            display: block;
            height: 100%;
        }

        .property-block:hover .property-img {
            transform: scale(1.1);
        }

        .property-overlay {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            padding: 20px 15px 15px;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .property-block:hover .property-overlay {
            bottom: 0;
            opacity: 1;
        }

        .btn-view-on-map {
            background-color: #ffffff;
            color: #333;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-view-on-map:hover {
            background-color: #ffc70b;
            color: white;
        }

        .property-badge {
            position: absolute;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            z-index: 10;
            border-radius: 3px;
        }

        .property-badge-featured {
            top: 10px;
            left: 10px;
            background-color: #ffc70b;
            color: white;
        }

        .property-badge-status {
            top: 10px;
            right: 10px;
            color: white;
        }

        .property-badge-sale {
            background-color: #4CAF50;
        }

        .property-badge-rent {
            background-color: #2196F3;
        }

        .property-grid .property-content {
            padding: 15px;
        }

        .property-list .property-content {
            width: 65%;
            float: left;
            padding: 20px;
        }

        .property-location {
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .property-location i {
            color: #ffc70b;
            margin-right: 5px;
        }

        .property-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
        }

        .property-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .property-title a:hover {
            color: #ffc70b;
        }

        .property-price {
            font-size: 18px;
            font-weight: bold;
            color: #ffc70b;
            margin-bottom: 10px;
        }

        .price-period {
            font-size: 14px;
            font-weight: normal;
            color: #999;
        }

        .property-features {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0 0 15px;
            justify-content: space-between;
        }

        .property-grid .property-features {
            border-top: 1px solid #eee;
            padding-top: 12px;
        }

        .property-features li {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .property-features li i {
            margin-right: 5px;
            color: #ffc70b;
            font-size: 16px;
        }

        .property-features li span {
            font-weight: bold;
            margin-right: 4px;
        }

        .property-description {
            margin-bottom: 15px;
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .property-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-property-details,
        .btn-property-locate {
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-property-details {
            background-color: #ffc70b;
            color: white;
            border: none;
        }

        .btn-property-details:hover {
            background-color: #e6b300;
            transform: translateY(-2px);
        }

        .btn-property-locate {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-property-locate:hover {
            background-color: #eee;
            border-color: #ccc;
            transform: translateY(-2px);
        }

        .btn-property-details i,
        .btn-property-locate i {
            margin-right: 5px;
        }

        /* List View Specific Styles */
        .list-view-container .property-block {
            display: flex;
            flex-wrap: wrap;
        }

        .list-view-container .property-block:after {
            content: '';
            display: table;
            clear: both;
        }

        /* Footer Styles */
        .property-list-footer {
            border-top: 1px solid #eee;
            padding-top: 30px;
            color: #666;
        }

        .footer-section {
            height: 100%;
        }

        .footer-section h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid #ffc70b;
            padding-bottom: 8px;
            display: inline-block;
        }

        .contact-list li {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .contact-list li i {
            color: #ffc70b;
            width: 20px;
        }

        .social-icons {
            display: flex;
            gap: 10px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f5f5f5;
            color: #666;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            background-color: #ffc70b;
            color: white;
            transform: translateY(-3px);
        }

        .newsletter .input-group .form-control {
            border-radius: 4px 0 0 4px;
            border: 1px solid #ddd;
        }

        .newsletter .input-group .btn {
            border-radius: 0 4px 4px 0;
        }

        .footer-bottom {
            font-size: 14px;
        }

        .footer-links a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #ffc70b;
        }

        .footer-link {
            color: #ffc70b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: #e6b300;
            text-decoration: underline;
        }

        /* Pagination Styles */
        .pagination .page-item.active .page-link {
            background-color: #ffc70b;
            border-color: #ffc70b;
        }

        .pagination .page-link {
            color: #333;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa;
            color: #ffc70b;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .list-controls {
                flex-direction: column;
            }

            .results-count {
                margin-bottom: 10px;
            }

            .list-view-container .property-list .property-image {
                width: 40%;
            }

            .list-view-container .property-list .property-content {
                width: 60%;
            }
        }

        @media (max-width: 768px) {
            .property-item {
                margin-bottom: 15px;
            }

            .list-view-container .property-list .property-image {
                width: 100%;
                float: none;
                height: 200px;
            }

            .list-view-container .property-list .property-content {
                width: 100%;
                float: none;
            }

            .property-features {
                flex-wrap: wrap;
                gap: 10px;
            }

            .property-features li {
                flex: 0 0 calc(50% - 5px);
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                margin-top: 10px;
            }
        }

        @media (max-width: 576px) {
            .property-title {
                font-size: 16px;
            }

            .property-price {
                font-size: 16px;
            }

            .property-badge {
                font-size: 10px;
                padding: 4px 8px;
            }

            .property-features li {
                font-size: 12px;
            }

            .property-actions {
                flex-direction: column;
            }

            .btn-property-details,
            .btn-property-locate {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:init', function() {
            // Highlight property on map when hovering over property card
            document.addEventListener('property-hover', function(e) {
                Livewire.dispatch('highlightPropertyOnMap',
                    e.detail.id,
                    e.detail.lat,
                    e.detail.lng
                );
            });

            // Remove highlight when mouse leaves property card
            document.addEventListener('property-unhover', function() {
                Livewire.dispatch('resetPropertyHighlight');
            });
        });
    </script>
@endpush

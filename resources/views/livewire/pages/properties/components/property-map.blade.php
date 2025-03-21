<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $properties = [];
    public $centerLat = -2.5489;
    public $centerLng = 118.0149;
    public $zoom = 5;
    public $activePropertyId = null;

    public function mount($properties)
    {
        $this->properties = collect($properties)
            ->filter(function ($property) {
                return isset($property['lat']) && isset($property['lng']) && is_numeric($property['lat']) && is_numeric($property['lng']);
            })
            ->toArray();
    }

    public function getPropertiesJsonProperty()
    {
        $properties = collect($this->properties)->toArray();

        foreach ($properties as &$property) {
            $property['lat'] = (float) ($property['lat'] ?? 0);
            $property['lng'] = (float) ($property['lng'] ?? 0);
            $property['id'] = (string) ($property['id'] ?? '');
        }

        return json_encode($properties);
    }

    public function getMapSettingsProperty()
    {
        return json_encode([
            'center' => [
                'lat' => $this->centerLat,
                'lng' => $this->centerLng,
            ],
            'zoom' => $this->zoom,
            'activePropertyId' => $this->activePropertyId,
        ]);
    }

    public function updateMapBounds($bounds)
    {
        $this->dispatch('updateMapBounds', $bounds);
    }

    #[On('setLocation')]
    public function setLocation($params = null)
    {
        // Forward event to parent component
        $this->dispatch('setLocation', $params);
    }

    #[On('highlightProperty')]
    public function highlightProperty($propertyId)
    {
        $this->activePropertyId = $propertyId;
    }

    #[On('resetHighlight')]
    public function resetHighlight()
    {
        $this->activePropertyId = null;
    }

    #[On('focusOnProperty')]
    public function focusOnProperty($params)
    {
        if (isset($params['lat']) && isset($params['lng'])) {
            $this->centerLat = $params['lat'];
            $this->centerLng = $params['lng'];
            $this->zoom = 15;
            $this->activePropertyId = $params['id'] ?? null;

            $this->dispatch('map-focus-changed', [
                'lat' => $this->centerLat,
                'lng' => $this->centerLng,
                'zoom' => $this->zoom,
                'id' => $this->activePropertyId,
            ]);
        }
    }
}; ?>

<div wire:ignore class="map-wrapper">
    <div id="map" style="height: 100%;" wire:ignore data-properties='{{ $this->propertiesJson }}'
        data-settings='{{ $this->mapSettings }}'></div>

    <div class="map-controls">
        <button class="map-control-btn map-reset-btn" title="Reset map view" onclick="resetMapView()">
            <i class="fas fa-home"></i>
        </button>
        <button class="map-control-btn map-zoom-in-btn" title="Zoom in" onclick="zoomInMap()">
            <i class="fas fa-plus"></i>
        </button>
        <button class="map-control-btn map-zoom-out-btn" title="Zoom out" onclick="zoomOutMap()">
            <i class="fas fa-minus"></i>
        </button>
        <button class="map-control-btn map-locate-btn" title="My location" onclick="findMyLocation()">
            <i class="fas fa-location-arrow"></i>
        </button>
    </div>

    <div class="map-legend">
        <div class="legend-item">
            <span class="legend-marker legend-sale"></span>
            <span>Dijual</span>
        </div>
        <div class="legend-item">
            <span class="legend-marker legend-rent"></span>
            <span>Disewa</span>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .property-marker {
            background: #ffc70b;
            border-radius: 4px;
            color: #fff;
            text-align: center;
            font-weight: bold;
            display: flex;
            align-items: center;
            padding: 4px 8px;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            will-change: transform;
        }

        .property-marker-sale {
            background: #4CAF50;
        }

        .property-marker-rent {
            background: #2196F3;
        }

        .property-marker-active {
            transform: scale(1.2);
            z-index: 1000 !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.4);
        }

        .property-marker:hover {
            transform: scale(1.1);
        }

        .property-marker-popup {
            max-width: 280px;
        }

        .property-marker-popup img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .property-marker-popup img:hover {
            filter: brightness(1.1);
        }

        .property-marker-popup h5 {
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        .property-marker-popup .price {
            color: #ffc70b;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .property-marker-popup .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .property-marker-popup .detail-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .property-marker-popup .detail-item i {
            margin-bottom: 4px;
            color: #555;
        }

        .property-marker-popup .btn {
            width: 100%;
            background-color: #ffc70b;
            border: none;
            color: #fff;
            padding: 8px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            display: block;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .property-marker-popup .btn:hover {
            background-color: #e6b300;
            transform: translateY(-2px);
        }

        .property-marker-popup .filter-btn {
            width: 100%;
            background-color: #4CAF50;
            margin-top: 8px;
            border: none;
            color: #fff;
            padding: 8px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            display: block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .property-marker-popup .filter-btn:hover {
            background-color: #3d8b40;
            transform: translateY(-2px);
        }

        .map-wrapper {
            height: 100%;
            width: 100%;
            position: relative;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Map Controls */
        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 400;
        }

        .map-control-btn {
            width: 36px;
            height: 36px;
            border-radius: 4px;
            background-color: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .map-control-btn:hover {
            background-color: #f5f5f5;
        }

        /* Map Legend */
        .map-legend {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: white;
            padding: 8px 12px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 400;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-item:last-child {
            margin-bottom: 0;
        }

        .legend-marker {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            margin-right: 8px;
        }

        .legend-sale {
            background-color: #4CAF50;
        }

        .legend-rent {
            background-color: #2196F3;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // These will be defined in the global script below
        // They're referenced by the control buttons
        function resetMapView() {
            if (window.propertyMap) {
                window.propertyMap.resetView();
            }
        }

        function zoomInMap() {
            if (window.propertyMap) {
                window.propertyMap.zoomIn();
            }
        }

        function zoomOutMap() {
            if (window.propertyMap) {
                window.propertyMap.zoomOut();
            }
        }

        function findMyLocation() {
            if (window.propertyMap) {
                window.propertyMap.locateUser();
            }
        }
    </script>

    <script>
        function initMap() {
            document.dispatchEvent(new Event('google-maps-loaded'));
        }
    </script>

    <script>
        document.addEventListener('livewire:init', function() {
            const mapComponent = Livewire.first(); // Atau lebih spesifik selector jika ada beberapa komponen

            if (mapComponent) {
                let lastLat = null;
                let lastLng = null;

                Livewire.hook('message.processed', (message, component) => {
                    if (component === mapComponent) {
                        // Cek perubahan koordinat
                        if (mapComponent.centerLat !== lastLat || mapComponent.centerLng !== lastLng) {
                            lastLat = mapComponent.centerLat;
                            lastLng = mapComponent.centerLng;

                            console.log('Coordinate change detected:', lastLat, lastLng);

                            // Trigger manual update jika peta sudah ada
                            if (window.propertyMap && window.propertyMap.map) {
                                window.propertyMap.map.setCenter({
                                    lat: parseFloat(lastLat),
                                    lng: parseFloat(lastLng)
                                });
                                window.propertyMap.map.setZoom(mapComponent.zoom || 15);
                            }
                        }
                    }
                });
            }
        });

        document.addEventListener('livewire:init', function() {
            document.addEventListener('google-maps-loaded', function() {
                initializePropertyMap();
            });

            class PropertyMap {
                constructor(element, options = {}) {
                    if (!element) {
                        console.error('Map element not provided to PropertyMap constructor');
                        return;
                    }

                    this.mapElement = element;
                    this.options = Object.assign({
                        center: {
                            lat: -2.5489,
                            lng: 118.0149
                        },
                        zoom: 5,
                        minZoom: 4,
                        maxZoom: 18
                    }, options);

                    this.markers = [];
                    this.infoWindows = [];
                    this.activeInfoWindow = null;
                    this.activeMarker = null;
                    this.bounds = null;
                    this.isManualZoom = false;
                    this.debounceTimer = null;
                    this.map = null;
                    this.markerClusterer = null;

                    this.initMap();
                }


                initMap() {
                    try {
                        if (!this.mapElement) {
                            console.error('Map element not available');
                            return;
                        }

                        // Check if Google Maps API is loaded
                        if (typeof google === 'undefined' || !google.maps) {
                            console.warn('Google Maps API not loaded yet, will try again when loaded');
                            return;
                        }

                        this.map = new google.maps.Map(this.mapElement, {
                            center: this.options.center,
                            zoom: this.options.zoom,
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            mapTypeControl: true,
                            streetViewControl: false,
                            fullscreenControl: true,
                            zoomControl: false,
                            gestureHandling: 'greedy',
                            styles: [
                                // Style tetap sama...
                            ]
                        });

                        // Initialize bounds object
                        this.bounds = new google.maps.LatLngBounds();

                        // Cek MarkerClusterer dengan pendekatan berbeda
                        if (typeof MarkerClusterer !== 'undefined') {
                            this.initializeMarkerClusterer();
                        } else {
                            console.log('MarkerClusterer not ready, waiting...');
                            const checkInterval = setInterval(() => {
                                if (typeof MarkerClusterer !== 'undefined') {
                                    clearInterval(checkInterval);
                                    this.initializeMarkerClusterer();
                                }
                            }, 200);

                            // Beri batas waktu untuk mencegah interval berjalan selamanya
                            setTimeout(() => {
                                clearInterval(checkInterval);
                                if (!this.markerClusterer) {
                                    console.error('MarkerClusterer still not available after timeout');
                                }
                            }, 10000);
                        }

                        console.log('Map initialized successfully');
                    } catch (error) {
                        console.error('Error initializing map:', error);
                    }
                }

                initializeMarkerClusterer() {
                    try {
                        if (!this.map) return;

                        console.log('Initializing MarkerClusterer...');

                        // Verify MarkerClusterer is available
                        if (typeof MarkerClusterer === 'undefined') {
                            console.error('MarkerClusterer not available');
                            return;
                        }

                        // Create new instance with correct parameters
                        const options = {
                            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
                            maxZoom: 14,
                            gridSize: 50
                        };

                        try {
                            // First try with the modern syntax
                            this.markerClusterer = new MarkerClusterer(this.map, [], options);
                        } catch (e) {
                            console.warn('Error with modern MarkerClusterer syntax, trying older version...',
                                e);
                            // Try fallback syntax
                            this.markerClusterer = new MarkerClusterer(this.map, [], options);
                        }

                        console.log('MarkerClusterer initialized successfully');

                        // Setup event listeners after map is initialized
                        this.setupEventListeners();

                        // Load properties if data is available
                        this.loadProperties();
                    } catch (error) {
                        console.error('Error initializing marker clusterer:', error);
                    }
                }

                setupEventListeners() {
                    try {
                        if (!this.map) {
                            console.error('Map not initialized in setupEventListeners');
                            return;
                        }

                        // Add event listeners for map interactions
                        this.map.addListener('idle', () => this.onMapIdle());
                        this.map.addListener('zoom_changed', () => this.onZoomChanged());

                        // Add resize listener
                        google.maps.event.addDomListener(window, 'resize', () => {
                            if (!this.map) return;

                            const center = this.map.getCenter();
                            google.maps.event.trigger(this.map, 'resize');
                            this.map.setCenter(center);
                        });

                        // Setup Livewire events
                        if (typeof Livewire !== 'undefined') {
                            Livewire.on('propertiesUpdated', () => this.loadProperties());
                            Livewire.on('resetMapView', () => this.resetView());
                            Livewire.on('highlightPropertyOnMap', (propertyId, lat, lng) => {
                                this.highlightMarker(propertyId, lat, lng);
                            });
                            Livewire.on('resetPropertyHighlight', () => {
                                this.resetHighlight();
                            });
                            Livewire.on('zoomToProvince', (params) => {
                                this.zoomToProvince(params.provinceCode);
                            });
                        } else {
                            console.warn('Livewire not defined, event listeners not added');
                        }

                        // Make methods available globally for control buttons
                        window.propertyMap = this;

                        console.log('Event listeners set up successfully');
                    } catch (error) {
                        console.error('Error setting up event listeners:', error);
                    }
                }

                onMapIdle() {
                    try {
                        if (!this.map) return;

                        // Update bounds for filtering
                        const bounds = this.map.getBounds();
                        if (!bounds) return;

                        const ne = bounds.getNorthEast();
                        const sw = bounds.getSouthWest();

                        const boundsObj = {
                            north: ne.lat(),
                            south: sw.lat(),
                            east: ne.lng(),
                            west: sw.lng()
                        };

                        // Don't trigger bounds updates during manual operations
                        if (!this.isManualZoom && typeof Livewire !== 'undefined') {
                            // Call Livewire method to update bounds
                            const wireIdElement = this.mapElement.closest('[wire\\:id]');
                            if (!wireIdElement) {
                                console.warn('Could not find Livewire element');
                                return;
                            }

                            const wireId = wireIdElement.getAttribute('wire:id');
                            if (wireId) {
                                Livewire.find(wireId).call('updateMapBounds', boundsObj);

                                // If zoom level is large enough, filter properties
                                if (this.map.getZoom() >= 14) {
                                    this.filterByBounds(boundsObj);
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error in onMapIdle:', error);
                    }
                }

                onZoomChanged() {
                    try {
                        if (!this.map) return;

                        // If zoom level drops below threshold, reset bounds filter
                        if (this.map.getZoom() < 14 && this.isManualZoom && typeof Livewire !== 'undefined') {
                            Livewire.dispatch('resetFilter');
                        }
                    } catch (error) {
                        console.error('Error in onZoomChanged:', error);
                    }
                }

                filterByBounds(bounds) {
                    try {
                        // Dispatch event to filter properties by bounds
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('filterByBounds', {
                                bounds: bounds
                            });
                        }
                    } catch (error) {
                        console.error('Error in filterByBounds:', error);
                    }
                }

                loadProperties() {
                    try {
                        if (!this.map || !this.mapElement) {
                            console.error('Map or map element not initialized in loadProperties');
                            return;
                        }

                        // Clear existing markers
                        this.clearMarkers();

                        // Get properties data from element attribute
                        const propertiesData = this.mapElement.getAttribute('data-properties');
                        if (!propertiesData) {
                            console.warn('No properties data found');
                            return;
                        }

                        let properties = [];
                        try {
                            // Logging for debug
                            console.log("Properties data length:", propertiesData.length);
                            console.log("Properties data (first 100 chars):", propertiesData.substring(0, 100) +
                                "...");

                            properties = JSON.parse(propertiesData || '[]');
                            console.log("Parsed properties count:", properties.length);

                            if (properties.length > 0) {
                                console.log("First property sample:", properties[0]);
                            }
                        } catch (e) {
                            console.error('Error parsing properties data:', e);
                            return;
                        }

                        // Reset bounds
                        this.bounds = new google.maps.LatLngBounds();

                        // Create markers for each property
                        if (properties.length > 0) {
                            properties.forEach(property => {
                                // Validate property coordinates
                                if (!property.lat || !property.lng || isNaN(parseFloat(property.lat)) ||
                                    isNaN(parseFloat(property.lng))) {
                                    console.warn('Property missing valid coordinates:', property.id);
                                    return;
                                }

                                const marker = this.createMarker(property);
                                if (marker) {
                                    this.markers.push(marker);

                                    // Extend bounds
                                    this.bounds.extend(new google.maps.LatLng(
                                        parseFloat(property.lat),
                                        parseFloat(property.lng)
                                    ));
                                }
                            });

                            // Add markers to clusterer
                            if (this.markerClusterer && this.markers.length > 0) {
                                this.markerClusterer.addMarkers(this.markers);
                                console.log(`Added ${this.markers.length} markers to clusterer`);
                            }

                            // Check if we should fit bounds
                            if (this.markers.length > 1) {
                                this.map.fitBounds(this.bounds);
                            }

                            // Get and apply active property ID from settings
                            const settingsData = this.mapElement.getAttribute('data-settings');
                            if (settingsData) {
                                try {
                                    const settings = JSON.parse(settingsData);
                                    if (settings.activePropertyId) {
                                        this.highlightMarkerById(settings.activePropertyId);
                                    }
                                } catch (e) {
                                    console.error('Error parsing settings data:', e);
                                }
                            }
                        } else {
                            console.warn('No valid properties found to display on map');
                        }
                    } catch (error) {
                        console.error('Error loading properties:', error);
                    }
                }

                createMarker(property) {
                    try {
                        if (!this.map) return null;

                        console.log(`Creating marker for property ID: ${property.id}`);

                        const priceText = this.formatPrice(property.price);
                        const isForSale = property.status === 'for_sale';

                        // Create a custom marker with the appropriate styling
                        const marker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(property.lat),
                                lng: parseFloat(property.lng)
                            },
                            map: this.map,
                            propertyId: property.id, // Store property ID for later reference
                            icon: {
                                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <rect width="40" height="40" fill="${isForSale ? '#4CAF50' : '#2196F3'}" rx="4"/>
                    <text x="20" y="25" text-anchor="middle" fill="white" font-size="12" font-weight="bold">${priceText}</text>
                </svg>
            `),
                                scaledSize: new google.maps.Size(40, 40),
                                anchor: new google.maps.Point(20, 20)
                            },
                            optimized: true, // Important for performance with many markers
                            zIndex: 1
                        });

                        // Create info window with property details
                        const infoWindow = new google.maps.InfoWindow({
                            content: this.createInfoWindowContent(property),
                            maxWidth: 300
                        });

                        // Store info window reference
                        this.infoWindows.push(infoWindow);

                        // Add click event to open info window
                        marker.addListener('click', () => {
                            // Close any open info window
                            if (this.activeInfoWindow) {
                                this.activeInfoWindow.close();
                            }

                            // Open this info window
                            infoWindow.open(this.map, marker);
                            this.activeInfoWindow = infoWindow;

                            // Highlight this marker
                            this.highlightMarker(property.id, property.lat, property.lng);
                        });

                        return marker;
                    } catch (error) {
                        console.error('Error creating marker for property:', property.id, error);
                        return null;
                    }
                }

                createInfoWindowContent(property) {
                    try {
                        const isForSale = property.status === 'for_sale';
                        const statusText = isForSale ? 'Dijual' : 'Disewa';

                        return `
                    <div class="property-marker-popup">
                        <img src="${property.thumbnail || '/assets/images/placeholder.jpg'}" alt="${property.name}" 
                             onclick="window.location.href='/property/${property.slug}'">
                        <h5>${property.name}</h5>
                        <div class="price">
                            Rp ${property.price.toLocaleString('id-ID')}
                            ${!isForSale ? '<span>/bulan</span>' : ''}
                        </div>
                        <div class="details">
                            <div class="detail-item">
                                <i class="fas fa-bed"></i>
                                <span>${property.bedrooms}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-bath"></i>
                                <span>${property.bathrooms}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span>${property.area} m²</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-tag"></i>
                                <span>${statusText}</span>
                            </div>
                        </div>
                        <a href="/property/${property.slug}" class="btn">Lihat Detail</a>
                        <button class="filter-btn" onclick="window.filterByLocation('${property.location}')">
                            Filter by ${property.location}
                        </button>
                    </div>
                `;
                    } catch (error) {
                        console.error('Error creating info window content:', error);
                        return '<div>Error loading property details</div>';
                    }
                }

                formatPrice(price) {
                    try {
                        if (price >= 1000000000) {
                            return Math.round(price / 1000000000) + 'M';
                        } else if (price >= 1000000) {
                            return Math.round(price / 1000000) + 'JT';
                        }
                        return price.toLocaleString('id-ID');
                    } catch (error) {
                        console.error('Error formatting price:', error);
                        return '0';
                    }
                }

                clearMarkers() {
                    try {
                        // Close any open info window
                        if (this.activeInfoWindow) {
                            this.activeInfoWindow.close();
                            this.activeInfoWindow = null;
                        }

                        // Clear active marker
                        this.activeMarker = null;

                        // Clear marker clusterer
                        if (this.markerClusterer) {
                            this.markerClusterer.clearMarkers();
                        }

                        // Clear markers
                        this.markers = [];
                        this.infoWindows = [];
                    } catch (error) {
                        console.error('Error clearing markers:', error);
                    }
                }

                highlightMarker(propertyId, lat, lng) {
                    try {
                        // Reset any previously highlighted marker
                        this.resetHighlight();

                        // Find the marker by property ID
                        const marker = this.markers.find(m => m.propertyId === propertyId);

                        if (marker) {
                            // Increase z-index to bring to front
                            marker.setZIndex(1000);

                            // Scale up the icon
                            const icon = marker.getIcon();
                            icon.scaledSize = new google.maps.Size(48, 48);
                            icon.anchor = new google.maps.Point(24, 24);
                            marker.setIcon(icon);

                            // Store as active marker
                            this.activeMarker = marker;

                            // Dispatch event to highlight property in list
                            if (typeof Livewire !== 'undefined') {
                                Livewire.dispatch('highlightProperty', propertyId);
                            }
                        }
                    } catch (error) {
                        console.error('Error highlighting marker:', error);
                    }
                }

                highlightMarkerById(propertyId) {
                    try {
                        const marker = this.markers.find(m => m.propertyId === propertyId);

                        if (marker) {
                            const position = marker.getPosition();
                            this.highlightMarker(propertyId, position.lat(), position.lng());
                        }
                    } catch (error) {
                        console.error('Error highlighting marker by ID:', error);
                    }
                }

                resetHighlight() {
                    try {
                        if (this.activeMarker) {
                            // Reset z-index
                            this.activeMarker.setZIndex(1);

                            // Reset icon size
                            const icon = this.activeMarker.getIcon();
                            icon.scaledSize = new google.maps.Size(40, 40);
                            icon.anchor = new google.maps.Point(20, 20);
                            this.activeMarker.setIcon(icon);

                            this.activeMarker = null;

                            // Dispatch event to unhighlight property in list
                            if (typeof Livewire !== 'undefined') {
                                Livewire.dispatch('resetHighlight');
                            }
                        }
                    } catch (error) {
                        console.error('Error resetting highlight:', error);
                    }
                }

                // Custom control methods
                resetView() {
                    try {
                        if (!this.map) return;

                        this.isManualZoom = true;

                        if (this.markers.length > 1 && this.bounds) {
                            this.map.fitBounds(this.bounds);
                        } else {
                            this.map.setCenter({
                                lat: -2.5489,
                                lng: 118.0149
                            });
                            this.map.setZoom(5);
                        }

                        // Close any open info window
                        if (this.activeInfoWindow) {
                            this.activeInfoWindow.close();
                            this.activeInfoWindow = null;
                        }

                        // Reset highlight
                        this.resetHighlight();

                        // Reset manual zoom flag after a delay
                        setTimeout(() => {
                            this.isManualZoom = false;
                        }, 1000);
                    } catch (error) {
                        console.error('Error resetting view:', error);
                    }
                }

                zoomIn() {
                    try {
                        if (!this.map) return;

                        const currentZoom = this.map.getZoom();
                        if (currentZoom < this.options.maxZoom) {
                            this.map.setZoom(currentZoom + 1);
                        }
                    } catch (error) {
                        console.error('Error zooming in:', error);
                    }
                }

                zoomOut() {
                    try {
                        if (!this.map) return;

                        const currentZoom = this.map.getZoom();
                        if (currentZoom > this.options.minZoom) {
                            this.map.setZoom(currentZoom - 1);
                        }
                    } catch (error) {
                        console.error('Error zooming out:', error);
                    }
                }

                locateUser() {
                    try {
                        if (!this.map || !this.mapElement || !navigator.geolocation) {
                            if (!navigator.geolocation) {
                                alert('Geolocation tidak didukung oleh browser Anda');
                            }
                            return;
                        }

                        // Show loading indicator
                        const loadingDiv = document.createElement('div');
                        loadingDiv.className = 'map-locating';
                        loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari lokasi Anda...';
                        this.mapElement.appendChild(loadingDiv);

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                try {
                                    // Remove loading indicator safely
                                    if (loadingDiv.parentNode) {
                                        loadingDiv.parentNode.removeChild(loadingDiv);
                                    }

                                    const userLocation = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude
                                    };

                                    // Center map on user's location
                                    this.map.setCenter(userLocation);
                                    this.map.setZoom(14);

                                    // Add a marker for user's location
                                    const userMarker = new google.maps.Marker({
                                        position: userLocation,
                                        map: this.map,
                                        icon: {
                                            path: google.maps.SymbolPath.CIRCLE,
                                            fillColor: '#4285F4',
                                            fillOpacity: 1,
                                            strokeColor: '#FFFFFF',
                                            strokeWeight: 2,
                                            scale: 10
                                        },
                                        title: 'Lokasi Anda'
                                    });

                                    // Add accuracy circle
                                    const accuracyCircle = new google.maps.Circle({
                                        map: this.map,
                                        center: userLocation,
                                        radius: position.coords.accuracy,
                                        fillColor: '#4285F4',
                                        fillOpacity: 0.2,
                                        strokeColor: '#4285F4',
                                        strokeOpacity: 0.5,
                                        strokeWeight: 1
                                    });

                                    // Remove after 10 seconds
                                    setTimeout(() => {
                                        userMarker.setMap(null);
                                        accuracyCircle.setMap(null);
                                    }, 10000);
                                } catch (error) {
                                    console.error('Error in geolocation success handler:', error);

                                    // Ensure loading indicator is removed even if error occurs
                                    if (loadingDiv.parentNode) {
                                        loadingDiv.parentNode.removeChild(loadingDiv);
                                    }
                                }
                            },
                            (error) => {
                                try {
                                    // Remove loading indicator safely
                                    if (loadingDiv.parentNode) {
                                        loadingDiv.parentNode.removeChild(loadingDiv);
                                    }

                                    // Show error message
                                    alert('Tidak dapat menemukan lokasi Anda: ' + error.message);
                                } catch (err) {
                                    console.error('Error in geolocation error handler:', err);
                                }
                            }, {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } catch (error) {
                        console.error('Error locating user:', error);
                    }
                }

                zoomToProvince(provinceCode) {
                    try {
                        if (!this.map) return;

                        // Define province center coordinates and zoom levels
                        const provinces = {
                            '31': {
                                center: {
                                    lat: -6.18233995,
                                    lng: 106.84287154
                                },
                                zoom: 11
                            }, // Jakarta
                            '51': {
                                center: {
                                    lat: -8.65629720,
                                    lng: 115.22209883
                                },
                                zoom: 10
                            }, // Bali
                            '32': {
                                center: {
                                    lat: -6.90389,
                                    lng: 107.61861
                                },
                                zoom: 10
                            }, // Jawa Barat (Bandung)
                            '35': {
                                center: {
                                    lat: -7.2575,
                                    lng: 112.7521
                                },
                                zoom: 10
                            }, // Jawa Timur (Surabaya)
                            '34': {
                                center: {
                                    lat: -7.7956,
                                    lng: 110.3695
                                },
                                zoom: 10
                            }, // Yogyakarta
                            '33': {
                                center: {
                                    lat: -7.1501,
                                    lng: 110.1403
                                },
                                zoom: 9
                            }, // Jawa Tengah
                            '12': {
                                center: {
                                    lat: 3.5952,
                                    lng: 98.6722
                                },
                                zoom: 9
                            }, // Sumatera Utara
                            '16': {
                                center: {
                                    lat: -2.9761,
                                    lng: 104.7754
                                },
                                zoom: 9
                            }, // Sumatera Selatan
                            '64': {
                                center: {
                                    lat: 0.5387,
                                    lng: 116.4194
                                },
                                zoom: 8
                            }, // Kalimantan Timur
                            '73': {
                                center: {
                                    lat: -5.1477,
                                    lng: 119.4327
                                },
                                zoom: 9
                            } // Sulawesi Selatan
                        };

                        if (provinces[provinceCode]) {
                            this.isManualZoom = true;

                            const province = provinces[provinceCode];
                            this.map.setCenter(province.center);
                            this.map.setZoom(province.zoom);

                            // Reset manual zoom flag after a delay
                            setTimeout(() => {
                                this.isManualZoom = false;
                            }, 1500);
                        }
                    } catch (error) {
                        console.error('Error zooming to province:', error);
                    }
                }

                focusOnProperty(params) {
                    console.log('focusOnProperty called with params:', params);

                    try {
                        if (params.id) {
                            console.log(`Focusing on property ID: ${params.id}`);
                        }

                        if (typeof params.lat === 'undefined' || typeof params.lng === 'undefined') {
                            console.error('Missing lat/lng in focusOnProperty:', params);
                            return;
                        }

                        // Convert to float to ensure valid coordinates
                        this.centerLat = parseFloat(params.lat);
                        this.centerLng = parseFloat(params.lng);
                        this.zoom = params.zoom || 15;
                        this.activePropertyId = params.id || null;

                        console.log(
                            `Set map center to: ${this.centerLat}, ${this.centerLng}, zoom: ${this.zoom}`);

                        // If map is already initialized
                        if (window.propertyMap && window.propertyMap.map) {
                            window.propertyMap.map.setCenter({
                                lat: this.centerLat,
                                lng: this.centerLng
                            });
                            window.propertyMap.map.setZoom(this.zoom);

                            // Highlight the marker
                            if (this.activePropertyId && window.propertyMap.highlightMarkerById) {
                                window.propertyMap.highlightMarkerById(this.activePropertyId);
                            }
                        }

                        // Dispatch event to notify other components
                        this.dispatch('map-focus-changed', {
                            lat: this.centerLat,
                            lng: this.centerLng,
                            zoom: this.zoom,
                            id: this.activePropertyId,
                        });
                    } catch (error) {
                        console.error('Error in focusOnProperty:', error);
                    }
                }
            }

            function loadMarkerClusterer(callback) {
                // Tambahkan flag untuk memastikan hanya dimuat sekali
                if (window.markerClustererLoading) {
                    // Jika sedang dimuat, tambahkan callback ke antrian
                    if (!window.markerClustererCallbacks) {
                        window.markerClustererCallbacks = [];
                    }
                    window.markerClustererCallbacks.push(callback);
                    return;
                }

                if (typeof MarkerClusterer !== 'undefined') {
                    console.log('MarkerClusterer already loaded');
                    callback();
                    return;
                }

                // Set flag loading
                window.markerClustererLoading = true;
                if (!window.markerClustererCallbacks) {
                    window.markerClustererCallbacks = [];
                }
                window.markerClustererCallbacks.push(callback);

                console.log('Loading MarkerClusterer script');
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/@googlemaps/markerclusterer@2.0.15/dist/index.min.js';
                script.onload = function() {
                    console.log('MarkerClusterer loaded successfully');
                    window.markerClustererLoading = false;

                    // Panggil semua callback yang menunggu
                    while (window.markerClustererCallbacks.length > 0) {
                        const cb = window.markerClustererCallbacks.shift();
                        cb();
                    }
                };
                script.onerror = function() {
                    console.error('Failed to load MarkerClusterer');
                    window.markerClustererLoading = false;
                };
                document.head.appendChild(script);
            }

            function initializePropertyMap() {
                // Gunakan variable untuk menghindari panggilan ulang yang tidak perlu
                if (window.mapInitialized) return;

                const mapElement = document.getElementById('map');
                if (!mapElement) {
                    console.warn('Map element not found, retrying in 500ms');
                    setTimeout(initializePropertyMap, 500);
                    return;
                }

                // Pastikan MarkerClusterer dimuat sebelum melanjutkan
                loadMarkerClusterer(() => {
                    try {
                        // Tandai map sebagai terinisialisasi
                        window.mapInitialized = true;

                        // Create a new PropertyMap instance
                        const map = new PropertyMap(mapElement);

                        // Simpan instance map di window untuk akses global
                        window.propertyMapInstance = map;

                        // Setup filter function
                        window.filterByLocation = function(location) {
                            if (!window.propertyMapInstance) return;
                            // kode filter tetap sama
                        };

                        console.log('Property map initialized successfully');
                    } catch (error) {
                        console.error('Error initializing property map:', error);
                        // Reset flag agar bisa mencoba lagi
                        window.mapInitialized = false;
                    }
                });
            }

            function safeInitialize() {
                try {
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', function() {
                            if (typeof google !== 'undefined' && google.maps) {
                                initializePropertyMap();
                            }
                        });
                    } else if (typeof google !== 'undefined' && google.maps) {
                        initializePropertyMap();
                    }
                } catch (error) {
                    console.error('Error in safe initialize:', error);
                }
            }

            safeInitialize();

            Livewire.on('map-focus-changed', (params) => {
                console.log('Received map-focus-changed event:', params);
                if (params.lat && params.lng) {
                    this.map.setCenter({
                        lat: parseFloat(params.lat),
                        lng: parseFloat(params.lng)
                    });
                    this.map.setZoom(params.zoom || 15);

                    if (params.id) {
                        setTimeout(() => {
                            this.highlightMarkerById(params.id);
                        }, 200);
                    }
                }
            });
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA01nmE3NaFHzzPWmQMQfaUwTqORLvzdFo&loading=async&callback=initMap">
    </script>
@endpush

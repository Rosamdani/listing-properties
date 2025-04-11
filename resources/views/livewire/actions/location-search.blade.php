<div class="property-search-form" x-data="{ 
    initGeolocation() { 
        this.$wire.useCurrentLocation(); 
        this.getLocation(); 
    }, 
    getLocation() { 
        if (navigator.geolocation) { 
            navigator.geolocation.getCurrentPosition(position => { 
                const lat = position.coords.latitude; 
                const lng = position.coords.longitude; 
                const address = `Lokasi Saat Ini (${lat.toFixed(4)}, ${lng.toFixed(4)})`; 
                this.$wire.setCurrentLocation(address, lat, lng); 
            }); 
        } 
    } 
}">
    <div class="form-group position-relative">
        <div class="input-group border rounded-pill overflow-hidden">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-0">
                    <i class="flaticon-search text-primary"></i>
                </span>
            </div>
            <input 
                id="autocompleteMap"
                type="text" 
                class="form-control border-0 search-input border-0 py-3"
                style="box-shadow: none;" 
                placeholder="Cari lokasi, area, atau alamat" 
            >
        </div>
    </div>
</div>
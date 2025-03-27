<div id="map" class="rounded" style="height: 80vh; width: 100%"></div>

@push('scripts')  
    <script>
        function initMap() {
            var uluru = {lat: -6.21462, lng: 106.84513};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: uluru
            });
            const datasetLayer = map.getDatasetFeatureLayer("042ae093-67ec-4235-8bb4-93b1201e63d7");

            datasetLayer.style = null;
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA01nmE3NaFHzzPWmQMQfaUwTqORLvzdFo&libraries=places&callback=initMap">
    </script>
@endpush
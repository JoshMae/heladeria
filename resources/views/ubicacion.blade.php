@extends('layouts.app')

@section('content')
    <div id="map" style="height: 500px; width: 100%;"></div>
@endsection

@section('scripts')
<script>
    window.initMap = function() {
        var storeLocation = { lat: 14.703469, lng: -90.576358 };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: storeLocation
        });
        var marker = new google.maps.Marker({
            position: storeLocation,
            map: map,
            title: 'Mi Tienda'
        });
    }
</script>
@endsection
<x-backoffice.layout.app-layout title="Lokasi Body Camera">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }

            .image-preview-container {
                border: 1px solid #ccc;
                border-radius: 5px;
                display: flex;
                justify-content: space-evenly;
                padding: 5px;
                flex-wrap: wrap;
            }
            
        </style>
    @endpush
    <x-backoffice.toolbar heading="Lokasi Body Camera" subheading="" breadcrumb="open-case-detail"
                          icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
        </div>
    </x-backoffice.toolbar>

    <head>


        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
            crossorigin=""/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" 
        crossorigin=""/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />


        <style>
            #mapid {
                height: 600px;
            }
        </style>
    
    </head>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body" >
                                <label class="fs-3 fw-bold">Lokasi Body Camera:</label> <hr>
                                    <div id="mapid" style="height: 600px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
            integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
            crossorigin=""></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

                
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
        integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
        crossorigin=""></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
        <script>
            $(document).ready(function() {
                var data = {!! json_encode($data) !!}

                var namaSatker = "{{ $satker->nama_satker }}"
                var latSatker = parseFloat("{{ $satker->lat }}")
                var longSatker = parseFloat("{{ $satker->long }}")
                var map = L.map('mapid').setView([latSatker, longSatker], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                var markers = L.markerClusterGroup();
                var camIcon = L.icon({
                    iconUrl: '{{ asset('assets/images/camera.png') }}',
                    iconSize: [38, 38], // size of the icon
                    iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
                    popupAnchor: [0, -38] // point from which the popup should open relative to the iconAnchor
                });
                var buildingIcon = L.icon({
                    iconUrl: '{{ asset('assets/images/building.png') }}',
                    iconSize: [38, 38], // size of the icon
                    iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
                    popupAnchor: [0, -38] // point from which the popup should open relative to the iconAnchor
                });
                

                data.forEach(el => {
                    if(el.lat && el.long){
                        var marker = L.marker([parseFloat(el.lat), parseFloat(el.long)], { icon: camIcon })
                            .bindPopup(el.device_name + '<br> lat: ' + parseFloat(el.lat) + ' long: ' + el.long);
                        markers.addLayer(marker); 
                    }
                });
                var marker = L.marker([latSatker, longSatker], {icon: buildingIcon})
                    .bindPopup(namaSatker);
                markers.addLayer(marker); 

                map.addLayer(markers);
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>

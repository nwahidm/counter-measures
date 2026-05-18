<x-backoffice.layout.app-layout title="Detail Body Camera">
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
    <x-backoffice.toolbar heading="Detail Body Camera" subheading="" breadcrumb="open-case-detail"
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
                            <div class="card mb-7">
                                <div class="card-body">
                                    <div class="card ">
                                        <div class="card-body">
                                            <label class="fs-3 fw-bold">DETAIL Body Camera:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Device name</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $data->device_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satker</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $satker->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Device URL</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">{{ $data->device_source_url }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Device Dahua Id</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">{{ $data->device_dahua_id }}</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body" >
                                    <div id="mapid" style="height: 400px"></div>
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
                var device_name = "{{ $data->device_name }}"
                var long = parseFloat("{{ $data->long }}")
                var lat = parseFloat("{{ $data->lat }}")
                var map = L.map('mapid').setView([lat, long], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                var markers = L.markerClusterGroup();

                var marker = L.marker([lat, long])
                    .bindPopup(device_name + '<br> lat: ' + lat + ' long: ' + long);
                markers.addLayer(marker);

                map.addLayer(markers);
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>

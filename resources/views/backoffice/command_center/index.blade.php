<x-backoffice.layout.app-layout title="Command Center">
    @push('css')
        <style>
            body, html {
                height: 100%;
                margin: 0;
                background-color: #f0f0f0;
            }

            .app-container {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                height: 100%;
                width: 100%;
                padding: 0;
            }

            .card {
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 1200px;
                margin: 20px;
                padding: 20px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .joystick-controls {
                display: grid;
                grid-template-columns: repeat(3, 60px);
                grid-template-rows: repeat(3, 60px);
                gap: 10px;
                align-items: center;
                justify-items: center;
                margin-left: 20px;
                padding-right: 20px; /* Add padding to the right */
            }

            .control-btn {
                width: 60px;
                height: 60px;
                font-size: 24px;
                text-align: center;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                transition: transform 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .control-btn:hover {
                background-color: #0056b3;
            }

            .control-btn:active {
                transform: scale(0.9);
            }

            .video-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                flex-grow: 1;
            }

            .control-buttons {
                display: flex;
                justify-content: center;
                margin-top: 15px;
            }

            .control-buttons button {
                margin: 0 5px;
            }

            #recordStatus {
                display: none;
                color: red;
                font-size: 18px;
                font-weight: bold;
                animation: blink 1s infinite;
                margin-left: 10px;
            }
            
        </style>
        <style>
            #mapid { min-height: 500px; }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @endpush

    <x-backoffice.toolbar heading="Dashboard Command Center" breadcrumb="interview-report" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
        </div>
    </x-backoffice.toolbar>

    <head>
        <title>PTZ Camera Control</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
            
        <link href="https://vjs.zencdn.net/8.12.0/video-js.css" rel="stylesheet" />


        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
            integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
            crossorigin=""/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" 
        integrity="sha512-RksmHcS2k1SQQF1zzWbEs7T3+mWe+KuzhoZ8pxdB1zFI8kOTDfsCBa2N+0NiJ3hufcrGeXyOUHk7wSB1upg5Tg==" 
        crossorigin=""/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
        <link href="{{asset('backend/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>


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
                    <div class="row g-5 g-xl-16 justify-content-center">
                        <div class="col-12">
                            <div class="card">
                                <div class="video col-8">
                                    <video
                                        id="my-video"
                                        class="video-js"
                                        controls
                                        preload="auto"
                                        data-setup="{}"
                                        width="640"
                                        height="480"
                                     >

                                        <!-- <source src="{{$command_center_cam_device->device_source_url}}" type="application/x-mpegURL"></source> -->
                                        Your browser does not support the video tag.
                                    </video>

                                    
                                    <div id="control-buttons" class="control-buttons">
                                        <button id="playButton" class="btn btn-primary">Play</button>
                                        <button id="pauseButton" class="btn btn-warning">Pause</button>
                                        <button id="stopButton" class="btn btn-danger">Stop</button>
                                        <button id="fullscreenButton" class="btn btn-success">Full Screen</button>
                                        <button id="recordButton" class="btn btn-secondary">Record</button>
                                        <span id="recordStatus">Recording...</span>
                                        
                                    </div>
                                </div>
                                <div class="video col-4">
                                    <div id="joystick-controls" class="joystick-controls col-4">
                                        <div></div>
                                        <button id="up" class="control-btn" onclick="moveCamera('up')"><i class="fas fa-arrow-up"></i></button>
                                        <div></div>
                                        <button id="left" class="control-btn" onclick="moveCamera('left')"><i class="fas fa-arrow-left"></i></button>
                                        <div></div>
                                        <button id="right" class="control-btn" onclick="moveCamera('right')"><i class="fas fa-arrow-right"></i></button>
                                        <div></div>
                                        <button id="down" class="control-btn" onclick="moveCamera('down')"><i class="fas fa-arrow-down"></i></button>
                                        <div></div>
                                    </div>
                                    <button class="btn btn-secondary" onclick="zoomCamera('zoomin')">Zoom In</button>
                                    <button class="btn btn-secondary" onclick="zoomCamera('zoomout')">Zoom Out</button>
                                </div>
                            </div>
                            <div class="card">
                                
                                <div class="table-responsive">
                                    <h4>History Video </h4>
                                    <table id="kt_datatable_both_scrolls" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                        <thead>
                                            <tr class="fw-semibold fs-6 text-gray-800">
                                                <th class="min-w-200px">No</th>
                                                <th class="min-w-150px">Histori Video</th>
                                                <th class="min-w-300px">Waktu Simpan</th>
                                                <th class="min-w-300px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($histori as $index => $video)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $video->video_record_path }}</td>
                                                <td>{{ $video->created_at }}</td>
                                                <td>
                                                    <a href="{{ Storage::url($video->video_record_path) }}" class="btn btn-info">Download</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body" id="mapid"></div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body py-5">
                                    <div class="table-responsive">
                                        <div class="text-end">
                                            <a class="btn btn-primary">Download Report</a>
                                        </div>
                                        {{ $dataTable->table(['class' => 'table table-striped table-row-bordered gy-5 gs-7 border rounded w-100 text-center', 'id' => 'data-table'], true) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
    @push('js')
    <script src="https://vjs.zencdn.net/8.12.0/video.min.js" defer></script>
        {{-- <script src="{{asset('backend/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script> --}}
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
            integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
            crossorigin=""></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

                
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
        integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
        crossorigin=""></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Play button functionality

                var player = videojs('my-video');
                var mediaRecorder;
                var recordedChunks = [];


                document.getElementById('playButton').addEventListener('click', function() {
                    player.src({
                        src: "{{$command_center_cam_device->device_source_url}}",
                                    type: 'application/x-mpegURL'
                    });
                    player.play();
                });

                // Pause button functionality
                document.getElementById('pauseButton').addEventListener('click', function() {
                    player.pause();
                });

                // Stop button functionality
                document.getElementById('stopButton').addEventListener('click', function() {
                    player.pause();
                    player.currentTime(0);
                });

                // Fullscreen button functionality
                document.getElementById('fullscreenButton').addEventListener('click', function() {
                    if (player.isFullscreen()) {
                        player.exitFullscreen();
                    } else {
                        player.requestFullscreen();
                    }
                });

                // Record button functionality
                document.getElementById('recordButton').addEventListener('click', function() {
                    if (mediaRecorder && mediaRecorder.state === "recording") {
                        mediaRecorder.stop();
                        this.textContent = 'Record';
                        document.getElementById('recordStatus').style.display = 'none';
                    } else {
                        startRecording();
                        this.textContent = 'Stop Recording';
                        document.getElementById('recordStatus').style.display = 'inline';
                    }
                });

                function startRecording() {
                    var stream = player.el().querySelector('video').captureStream();
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = handleDataAvailable;
                    mediaRecorder.start();
                }

                function handleDataAvailable(event) {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                        download();
                    }
                }

                function download() {
                    var blob = new Blob(recordedChunks, {
                        type: 'video/mp4'
                    });

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('commandcenter.upload.video') }}', true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.setRequestHeader('Content-Type', 'application/octet-stream'); // setting content type as octet-stream

                    xhr.onload = function () {
                        console.log(xhr.status);
                        if (xhr.status === 200) {
                            alert('Video uploaded successfully');
                        } else {
                            alert('Failed to upload video');
                        }
                    };

                    xhr.send(blob);
                    // var url = URL.createObjectURL(blob);
                    // var a = document.createElement('a');
                    // document.body.appendChild(a);
                    // a.style = 'display: none';
                    // a.href = url;
                    // a.download = 'recorded_video.mp4';
                    // a.click();
                    // window.URL.revokeObjectURL(url);
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $("#kt_datatable_both_scrolls").DataTable({
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var map = L.map('mapid').setView([-6.300641, 106.814095], {{ config('leaflet.zoom_level') }});

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var markers = L.markerClusterGroup();
                var carIcon1 = L.icon({
                    iconUrl: '{{ asset('assets/images/mercysprinter.png') }}',
                    iconSize: [38, 38], // size of the icon
                    iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
                    popupAnchor: [0, -38] // point from which the popup should open relative to the iconAnchor
                });

                // Example markers with custom icons and car models
                var carMarkers = [
                    { lat: -6.300641, lng: 106.814095, model: 'Toyota Prius', icon: carIcon1 },
                ];

                carMarkers.forEach(function(car) {
                    var marker = L.marker([car.lat, car.lng], { icon: car.icon })
                        .bindPopup('Command Center, lat: ' + car.lat + ' long: ' + car.lng);
                    markers.addLayer(marker);
                });

                map.addLayer(markers);

                function job() {
                    fetch('/command-center/get-obd-data-first', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(parseFloat(data["longitude"]));
                        console.log(parseFloat(data["latitude"]));

                        map.setView([parseFloat(data["latitude"]), parseFloat(data["longitude"])], {{ config('leaflet.zoom_level') }});

                        var markers = L.markerClusterGroup();
                        var carIcon1 = L.icon({
                            iconUrl: '{{ asset('assets/images/mercysprinter.png') }}',
                            iconSize: [38, 38], // size of the icon
                            iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
                            popupAnchor: [0, -38] // point from which the popup should open relative to the iconAnchor
                        });

                        // Example markers with custom icons and car models
                        var carMarkers = [
                            { lat: parseFloat(data["latitude"]), lng: parseFloat(data["longitude"]), model: 'Toyota Prius', icon: carIcon1 },
                        ];

                        carMarkers.forEach(function(car) {
                            var marker = L.marker([car.lat, car.lng], { icon: car.icon })
                                .bindPopup('Command Center, lat: ' + car.lat + ' long: ' + car.lng);
                            markers.addLayer(marker);
                        });

                        map.addLayer(markers);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // Expose the job function to be called later
                window.job = job;
                setInterval(job, 10000);
            });
            
           
           
            function stopStream() {

                // const videoElement = document.getElementById(camera_${cameraId});
                // videoElement.pause();
                // videoElement.src = '';

                // fetch('/command-center/camera-stop-record', {
                //     method: 'POST',
                //     headers: {
                //     'Content-Type': 'application/json',
                //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                //     },
                //     body: JSON.stringify({ action: action })
                // })
                // .then(response => response.json())
                // .then(data => {
                //     console.log(data.message);
                // })
                // .catch(error => {
                //     console.error('Error:', error);
                // });
            }
            
            function moveCamera(action) {
                console.log(action);

                fetch('/command-center/camera-control', {
                    method: 'POST',
                    headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ action: action })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            function zoomCamera(action) {
                console.log(action);

                fetch('/command-center/camera-zoom-control', {
                    method: 'POST',
                    headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ action: action })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        </script>

        
        <script>
            function deleteData(event) {
                event.preventDefault();

                let id = event.currentTarget.getAttribute('data-id');
                let url = "{{ route('open.interview.report.destroy', ':id') }}";
                url = url.replace(':id', id);
                Swal.fire({
                    title: "Anda yakin?",
                    text: "Akan menghapus data ini",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "Yes!",
                    cancelButtonText: 'No',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: 'btn btn-danger'
                    },
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        let submit = $("#deleteForm").attr('action', url);
                        submit.submit()
                    }
                });
            }
        </script>

    @endpush
</x-backoffice.layout.app-layout>
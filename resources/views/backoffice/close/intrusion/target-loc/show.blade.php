<x-backoffice.layout.app-layout title="Detail Lokasi Target Penyurupan">
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
    <x-backoffice.toolbar heading="Detail Lokasi Target Penyurupan" subheading="" breadcrumb="open-case-detail"
        icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification />
        </div>
    </x-backoffice.toolbar>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card ">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">

                                            </div>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end w-100 gap-2"
                                                    data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-dark btn-sm"
                                                        onclick="window.location.href='{{ route('close.intrusion.target-loc.download-interview-audio-to-text-file', encrypt($data->id)) }}'">
                                                        <i class="fas fa-file"></i> File Suara ke Teks
                                                    </button>

                                                    <button type="button" class="btn btn-dark btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#videoStreamModal">
                                                        <i class="fas fa-video"></i> Stream
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->satker?->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->case?->case_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">{{ \Carbon\Carbon::parse($data->case?->case_date)->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ strip_tags($data->case?->case_description)}}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL LOKASI TARGET:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_gender }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_identity_number_type }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">No. Identitas</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_identity_number }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_religion }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan
                                                    Terakhit</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_education }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->target_occupation }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Lokasi Target</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->lokasi_target }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lokasi</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{strip_tags($data->deskripsi_lokasi) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Lokasi</label>
                                                <div class="col-lg-8">
                                                    @if ($data->lokasi_target_upload)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;"
                                                            src="{{ asset('storage/' . $data->lokasi_target_upload) }}"
                                                            frameborder="0" id="information_collection_upload">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- <a class="btn btn-dark btn-sm btn-icon"
                                                        href="{{ route('close.intrusion.target-loc.download-file', encrypt($data->lokasi_target_upload)) }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a> --}}
                                                </div>
                                            </div>
                                            <div class="row mb-7">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                <div class="image-preview-container">
                                                    @foreach ($images as $image)
                                                        <img class="image-preview"
                                                            style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;"
                                                            src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}"
                                                            alt="Preview">
                                                    @endforeach
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">ANALISA VIDEO:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Video</label>
                                                <div class="col-lg-8">
                                                    @if ($data->video_upload)
                                                        <video controls style="width: 100%; height: 500px;"
                                                            style="width: 100%; height: 500px; overflow: hidden;">
                                                            <source src="{{ asset('storage/' . $data->video_upload) }}"
                                                                type="video/mp4" frameborder="0" id="video_upload">
                                                            </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada video</span>
                                                    @endif
                                                        {{-- @if($data->video_upload)
                                                        <a class="btn btn-dark btn-sm btn-icon"
                                                            href="{{ route('close.tapping.hasil.download-video', encrypt($data->video_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                        @else
                                                        <span class="badge badge-danger">Tidak ada video</span>
                                                        @endif --}}
                                                </div>
                                                <div class="card-body py-5">
                                                    <div class="table-responsive">
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="videoStreamModal" tabindex="-1" aria-labelledby="videoStreamModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoStreamModalLabel">Video Stream</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bodycamSelect" class="form-label">Select Bodycam Stream 1</label>
                        <select id="bodycamSelect">
                            <option value="" selected>Select a bostream</option>
                            @foreach ($bodycam_devices as $row)
                                <option value="{{ $row['id'] }}" @if($row['id'] === old('id_case')) selected @endif>
                                    {{ $row['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-12" id="videoContainer">
                        <iframe id="playerFrame" {{--
                            src="{{ route('player', 'serverIp=160.20.104.115&playerType=real&channelId=1000015$0$26') }}"
                            --}}
                            src="{{ route('player', 'serverIp=dss.kejaksaanri.id&playerType=real&channelId=1000015$0$26') }}"
                            frameborder="0" style="width: 100%; height: 500px;"></iframe>

                    </div>
                    <div class="custom-controls" id="player_control_button">
                        <button id="fullscreenButton" class="btn btn-success">Full Screen</button>
                        <button id="startRecordButton" class="btn btn-secondary">Start Record</button>
                        <button id="stopRecordButton" class="btn btn-secondary">Stop Record</button>
                        <span id="recordStatus">Recording...</span>
                        <span id="pidProcess"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    @endpush

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

    <script type="module">
        // Function to send a message to the iframe to play the RTSP stream
        function sendMessageToIframe(rtspLink) {
            var iframe = document.getElementById('playerFrame');
            var message = {
                type: 'playReal',
                data: rtspLink
            };
            iframe.contentWindow.postMessage(message, '*'); // Use '*' for the origin for testing; in production, specify the correct origin
            console.log('Sent RTSP link to iframe:', rtspLink);
        }

        // Listen for messages from the iframe (optional if you want to listen to messages from the iframe)
        window.addEventListener('message', function (event) {
            // Ensure the message comes from a trusted origin
            // Replace '*' with the iframe's actual origin (e.g., http://localhost)
            console.log('Received message from iframe:', event.data);
        });

        var bodycamSelect = document.getElementById('bodycamSelect');

        bodycamSelect.addEventListener('change', function () {
            var selectedStream = bodycamSelect.value;
            console.log(selectedStream);

            if (selectedStream != "") {
                $.ajax({
                    url: '/bodycam-device-by-id', // Replace with the actual route to your controller
                    type: 'GET',
                    data: { bodycam_id: selectedStream },
                    success: function (response) {
                        console.log(response);

                        if (response.device_dahua_id) {
                            // Dynamically construct the channelId and playerUrl using JavaScript
                            var channelId = response.device_dahua_id + '$0$26';
                            // var playerUrl = `http://160.20.104.115/web/player/index.html?serverIp=160.20.104.115&playerType=real&channelId=${channelId}`;

                            // // Log the constructed player URL for debugging
                            // console.log(playerUrl);

                            // // Set the dynamically created playerUrl to the iframe
                            // document.getElementById('playerFrame').src = playerUrl;

                            // Construct the RTSP link dynamically
                            var rtspLink = 'rtsp://160.20.104.115:9100/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;

                            // Send the RTSP link to the iframe using the postMessage method
                            sendMessageToIframe(rtspLink);
                        } else {
                            var channelId = response.device_dahua_id + '$0$26';
                            // var playerUrl = `http://160.20.104.115/web/player/index.html?serverIp=160.20.104.115&playerType=real&channelId=${channelId}`;
                            var playerUrl = `https://dss.kejaksaanri.id/web/player/index.html?serverIp=dss.kejaksaanri.id&playerType=real&channelId=${channelId}`;

                            // Log the constructed player URL for debugging
                            console.log(playerUrl);

                            // Set the dynamically created playerUrl to the iframe
                            document.getElementById('playerFrame').src = playerUrl;

                            // Construct the RTSP link dynamically
                            var rtspLink = 'rtsp://160.20.104.115/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;

                            // Send the RTSP link to the iframe using the postMessage method
                            sendMessageToIframe(rtspLink);
                        }
                    },
                    error: function (error) {
                        console.error("AJAX request failed:", error);
                    }
                });
            }
        });

        document.getElementById('fullscreenButton').addEventListener('click', function () {
            console.log("clicked")
            const iframe = document.getElementById('playerFrame');
            if (iframe.requestFullscreen) {
                iframe.requestFullscreen();
            } else if (iframe.mozRequestFullScreen) { // Firefox
                iframe.mozRequestFullScreen();
            } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari, and Opera
                iframe.webkitRequestFullscreen();
            } else if (iframe.msRequestFullscreen) { // IE/Edge
                iframe.msRequestFullscreen();
            }
        });



        function generateUUID() {
            var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var uuid = '';
            for (var i = 0; i < 10; i++) {
                uuid += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return uuid;
        }

        document.getElementById('startRecordButton').addEventListener('click', () => {

            var selectedStream = bodycamSelect.value;
            console.log(selectedStream);

            if (selectedStream != "") {
                $.ajax({
                    url: '/bodycam-device-by-id', // Replace with the actual route to your controller
                    type: 'GET',
                    data: { bodycam_id: selectedStream },
                    success: function (response) {
                        console.log(response);

                        if (response.device_dahua_id) {
                            var now = new Date();
                            var year = now.getFullYear();
                            var month = ('0' + (now.getMonth() + 1)).slice(-2); // Months are zero-based
                            var day = ('0' + now.getDate()).slice(-2);
                            var hours = ('0' + now.getHours()).slice(-2);
                            var minutes = ('0' + now.getMinutes()).slice(-2);
                            var seconds = ('0' + now.getSeconds()).slice(-2);
                            var timestamp = year + month + day + '_' + hours + minutes + seconds;

                            // Generate a 10-character UUID
                            var uuid = generateUUID();

                            // Construct the file name
                            var fileName = "intrusion_target_loc_" + timestamp + "_" + uuid;
                            // Dynamically construct the channelId and playerUrl using JavaScript
                            var channelId = response.device_dahua_id;

                            // Construct the RTSP link dynamically
                            var rtspLink = 'rtsp://160.20.104.115:9100/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;
                            // Send the RTSP link to the iframe using the postMessage method
                            $.ajax({
                                url: '/start-recording',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                                    stream_url: rtspLink,
                                    file_name: fileName// pastikan nama parameternya sesuai dengan yang diterima controller
                                },
                                success: function (response) {
                                    console.log(response);
                                    $('#pidProcess').text(response.pid);

                                    document.getElementById('startRecordButton').disabled = true;
                                    document.getElementById('stopRecordButton').disabled = false;
                                    document.getElementById('recordStatus').style.display = 'inline';


                                },
                                error: function (error) {
                                    console.error("AJAX request failed:", error);
                                }
                            });
                        }
                    },
                    error: function (error) {
                        console.error("AJAX request failed:", error);
                    }
                });
            }




        });

        document.getElementById('stopRecordButton').addEventListener('click', () => {

            $.ajax({
                url: '/stop-recording',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    pid: document.getElementById('pidProcess').innerText // pastikan nama parameternya sesuai dengan yang diterima controller
                },
                success: function (response) {
                    console.log(response);

                    $.ajax({
                        url: '/close/intrusion/target-loc/upload-video',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // CSRF token
                            // pastikan nama parameternya sesuai dengan yang diterima controller
                        },
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: "{!!$data->id!!}",
                            path: response.video_path_save
                        },
                        success: function (response) {
                            console.log('Video uploaded successfully:', response);
                        },
                        error: function (xhr, status, error) {
                            console.error('Video upload failed:', error);
                        }
                    });

                    document.getElementById('startRecordButton').disabled = false;
                    document.getElementById('stopRecordButton').disabled = true;
                    document.getElementById('recordStatus').style.display = 'none';


                },
                error: function (error) {
                    console.error("AJAX request failed:", error);
                }
            });


        });


    </script>
</x-backoffice.layout.app-layout>
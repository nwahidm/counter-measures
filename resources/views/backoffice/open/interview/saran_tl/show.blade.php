<x-backoffice.layout.app-layout title="Detail Wawancara Hasil">
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
        .text-justify {
                text-align: justify;
        }
        .custom-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .custom-controls button {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        #recordStatus {
            display: none;
            color: red;
            font-size: 18px;
            font-weight: bold;
            animation: blink 1s infinite;
            margin-left: 10px;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
    @endpush
    <x-backoffice.toolbar heading="Detail Wawancara Hasil" subheading="" breadcrumb="open-case-detail"
        icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification />
        </div>
    </x-backoffice.toolbar>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.9.8/dist/ffmpeg.min.js"></script>
        <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet">
        <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>
        
    </head>
    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title">
                                        
                                    </div>
                                   
                                </div>
                                <div class="card-body">
                                    <div class="card ">
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
                                                        $data->case->satker->nama_satker }}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->nama_kasus
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                        $data->case->tanggal_kasus->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                        $data->case->deskripsi_kasus }}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL WAWANCARA JADWAL:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Pewawancara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interviewJadwal->interviewer_name ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Wawancara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                        {{ optional(optional($data->interviewJadwal)->interviewer_schedule)->isoFormat('DD MMMM YYYY') ?? '' }}
                                                    </span>

                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Diwawancara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                        $data->interviewJadwal->source_person_name ?? ''}}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">No. Identitas
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                        $data->interviewJadwal->target_identity_number?? '' }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tipe Identitas
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                        $data->interviewJadwal->target_type_identity_number?? ''}}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interviewJadwal->target_gender?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interviewJadwal->target_religion ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interviewJadwal->target_education ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                <div class="col-lg-8">
                                                    @if (optional($data->interviewJadwal)->target_photo)
                                                    <img class="img-thumbnail"
                                                        src="{{ asset('storage/' . $data->interviewHasil->interviewJadwal->target_photo) }}"
                                                        alt="{{ $data->interviewHasil->interviewJadwal->source_person_name }}">
                                                    @else
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                        Foto</label>
                                                    @endif
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL WAWANCARA HASIL:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Dokumen Hasil Wawancara</label>
                                                <div class="col-lg-8">
                                                    @if (optional($data->interviewHasil)->upload_dokumen_wawancara)
                                                        <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.research.warrant.download-file', encrypt($data->interviewHasil->upload_dokumen_wawancara)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Video Hasil Wawancara</label>
                                                <div class="col-lg-8">
                                                    @if (optional($data->interviewHasil)->upload_video_wawancara)
                                                    <video style="width: 100%;" controls>
                                                        <source
                                                            src="{{ asset('storage/' . $data->interviewHasil->upload_video_wawancara) }}"
                                                            type="video/mp4">
                                                        <source src="movie.ogg" type="video/ogg">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    @endif
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL WAWANCARA SARAN DAN TINDAK LANJUT:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Saran dan Tindak Lanjut</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                        $data->saran_dan_tindak_lanjut_date->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Saran dan Tindak Lanjut</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->saran_dan_tindak_lanjut) }}</span>
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
    <div class="modal fade" id="videoStreamModal" tabindex="-1" aria-labelledby="videoStreamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoStreamModalLabel">Video Stream</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bodycamSelect" class="form-label">Select Bodycam Stream</label>
                        <select id="bodycamSelect">
                            <option value="" selected>Select a bostream</option>
                            @foreach ($bodycam_devices as $row)
                                <option value="{{ $row['id'] }}" @if($row['id'] === old('id_case')) selected @endif>{{ $row['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-12" id="videoContainer">
                        <video 
                            id="videoPlayer"
                            class="video-js vjs-default-skin" 
                            width="750" height="500"></video>
                    </div>
                    <div class="custom-controls">
                        <button id="playButton" class="btn btn-primary">Play</button>
                        <button id="pauseButton" class="btn btn-warning">Pause</button>
                        <button id="stopButton" class="btn btn-danger">Stop</button>
                        <button id="fullscreenButton" class="btn btn-success">Full Screen</button>
                        <button id="recordButton" class="btn btn-secondary">Record</button>
                        <span id="recordStatus">Recording...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    @endpush
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data from the backend
           
            // Populate the dropdown
            var bodycamSelect = document.getElementById('bodycamSelect');

            // Initialize Video.js player
            var player = videojs('videoPlayer');
            var mediaRecorder;
            var recordedChunks = [];

            // Handle dropdown change
            bodycamSelect.addEventListener('change', function(){
                var selectedStream = bodycamSelect.value;
                console.log(selectedStream);
                if(selectedStream != ""){
                    $.ajax({
                        url: '/bodycam-device-by-id', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {bodycam_id: selectedStream},
                        success: function(response) {
                             console.log(response);

                            if (selectedStream) {
                                player.src({
                                    src: response.device_source_url,
                                    type: 'application/x-mpegURL'
                                });
                                player.play();
                            } else {
                                player.pause();
                                player.src('');
                            }
                        }
                    });
                }
                
              
            });

             // Play button functionality
            document.getElementById('playButton').addEventListener('click', function() {
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
                    uploadToServer(event.data); 
                }
            }

            function download() {
                var blob = new Blob(recordedChunks, {
                    type: 'video/mp4'
                });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                document.body.appendChild(a);
                a.style = 'display: none';
                a.href = url;
                a.download = 'recorded_video.mp4';
                a.click();
                window.URL.revokeObjectURL(url);
            }

            function uploadToServer(blob) {
                var formData = new FormData();
                formData.append('video', blob, 'recorded_video.mp4');

                $.ajax({
                    url: '{{url('upload-video')}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include the CSRF token
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Video uploaded successfully:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Video upload failed:', error);
                    }
                });
            }

            $('#videoStreamModal').on('hide.bs.modal', function () {
                player.pause();
            });
        });
    </script>
</x-backoffice.layout.app-layout>
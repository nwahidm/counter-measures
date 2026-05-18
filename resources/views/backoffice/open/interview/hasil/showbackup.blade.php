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
                0% {
                    opacity: 1;
                }

                50% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
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
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-between w-100 gap-2"
                                            data-kt-customer-table-toolbar="base">
                                            <button type="button" class="btn btn-dark btn-sm"
                                                onclick="window.location.href='{{ route('open.interview.hasil.download-interview-audio-to-text-file', encrypt($data->id_interview_result)) }}'">
                                                <i class="fas fa-file"></i> File Suara ke Teks
                                            </button>




                                            <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#videoStreamModal">
                                                <i class="fas fa-video"></i> Stream
                                            </button>
                                        </div>
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
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->case->nama_kasus
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
    $data->case->tanggal_kasus->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
    $data->case->deskripsi_kasus }}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL WAWANCARA JADWAL:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Pewawancara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->interviewJadwal->interviewer_name ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Wawancara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
    optional($data->interviewJadwal)->interviewer_schedule?->isoFormat('DD MMMM YYYY') ?? ''}}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Diwawancara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
    $data->interviewJadwal->source_person_name ?? '' }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">No. Identitas
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
    $data->interviewJadwal->target_identity_number ?? ''}}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tipe Identitas
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{
    $data->interviewJadwal->target_type_identity_number ?? '' }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->interviewJadwal->target_gender ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->interviewJadwal->target_religion ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->interviewJadwal->target_education ?? ''
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                <div class="col-lg-8">
                                                    @if (optional($data->interviewJadwal)->target_photo)
                                                        <img class="img-thumbnail"
                                                            src="{{ asset('storage/' . $data->interviewJadwal->target_photo) }}"
                                                            alt="{{ $data->interviewJadwal->source_person_name }}">
                                                    @else
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                            Foto</label>
                                                    @endif
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL WAWANCARA HASIL:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Dokumen Hasil
                                                    Wawancara</label>
                                                <div class="col-lg-8">
                                                    @if ($data->upload_dokumen_wawancara)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;"
                                                            src="{{ asset('storage/' . $data->upload_dokumen_wawancara) }}"
                                                            frameborder="0" id="upload_dokumen_wawancara">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- <a class="btn btn-dark btn-sm btn-icon"
                                                        href="{{ route('open.research.warrant.download-file', encrypt($data->upload_video_wawancara)) }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a> --}}
                                                </div>
                                            </div>
                                            <label class="fs-3 fw-bold">ANALISA DOKUMEN:</label>
                                            <hr>
                                            <div class="row mb-5" style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify">{{ $document_pdf_data?->doc_analytics_2 }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5 " style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Kesimpulan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify">{{ $document_pdf_data?->doc_summary_2 }}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">ANALISA VIDEO:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Video Hasil
                                                    Wawancara</label>
                                                <div class="col-lg-8">
                                                    @if ($data->upload_video_wawancara)
                                                        <video style="width: 100%;" controls>
                                                            <source
                                                                src="{{ asset('storage/' . $data->upload_video_wawancara) }}"
                                                                type="video/mp4">
                                                            <source src="movie.ogg" type="video/ogg">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @endif
                                                </div>
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

    <div class="modal fade" id="videoStreamModal" tabindex="-1" aria-labelledby="videoStreamModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoStreamModalLabel">Video Stream backup fatah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bodycamSelect" class="form-label">Select Bodycam Stream</label>
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
                        <iframe id="playerFrame"
                            src="{{ route('player', 'serverIp=160.20.104.115&playerType=real&channelId=1000015$0$26') }}"
                            frameborder="0" style="width: 100%; height: 500px;"></iframe>

                    </div>
                    <div class="custom-controls" id="player_control_button">
                        <button id="fullscreenButton" class="btn btn-success">Full Screen</button>
                        <!-- Tambahkan tombol rekam -->
                        
                        <button id="recordButton" class="btn btn-primary">Start Recording</button>
                        <button id="stopButton" class="btn btn-danger" style="display: none;">Stop Recording</button>
                        <span id="recordStatus">Recording...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        
    </script>
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
                            var rtspLink = 'rtsp://160.20.104.115/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;

                            // Send the RTSP link to the iframe using the postMessage method
                            sendMessageToIframe(rtspLink);
                        } else {
                            var channelId = response.device_dahua_id + '$0$26';
                            var playerUrl = `http://160.20.104.115/web/player/index.html?serverIp=160.20.104.115&playerType=real&channelId=${channelId}`;

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

        
        // Variabel untuk media stream dan perekaman
let mediaRecorder;
let recordedChunks = [];

// Tombol rekam dan hentikan rekam
const recordButton = document.getElementById('recordButton');
const stopButton = document.getElementById('stopButton');
const recordStatus = document.getElementById('recordStatus');

// Event Listener untuk tombol rekam
recordButton.addEventListener('click', async () => {
    try {
        // Mendapatkan elemen video dari iframe
        const iframe = document.getElementById('playerFrame');
        const iframeWindow = iframe.contentWindow || iframe.contentDocument;

        // Pastikan iframe sudah siap dan bisa digunakan
        if (iframeWindow) {
            // Ambil stream dari iframe (jika diizinkan)
            const videoElement = iframeWindow.document.querySelector('video');

            if (videoElement) {
                const stream = videoElement.captureStream();
                
                // **Pastikan Audio juga ditangkap:**
                const audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                const combinedStream = new MediaStream([...stream.getTracks(), ...audioStream.getTracks()]);

                // Mulai perekaman dengan stream gabungan
                startRecording(combinedStream);
            } else {
                console.error("Tidak dapat menemukan elemen video di dalam iframe.");
            }
        } else {
            console.error("Iframe belum siap atau tidak dapat diakses.");
        }
    } catch (err) {
        console.error('Error saat mencoba mengakses media:', err);
    }
});

// Fungsi untuk memulai perekaman
function startRecording(stream) {
    recordedChunks = [];
    mediaRecorder = new MediaRecorder(stream, {
        mimeType: 'video/webm; codecs=vp9,opus'  // Pastikan codec mendukung video dan audio
    });

    mediaRecorder.ondataavailable = (event) => {
        if (event.data.size > 0) {
            recordedChunks.push(event.data);
        }
    };

    mediaRecorder.onstop = () => {
        const blob = new Blob(recordedChunks, { type: 'video/webm' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'bodycam_recording.webm';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    };

    mediaRecorder.start();
    recordStatus.style.display = 'inline';
    recordButton.style.display = 'none';
    stopButton.style.display = 'inline';
    console.log('Perekaman dimulai.');
}

// Event Listener untuk tombol hentikan rekam
stopButton.addEventListener('click', () => {
    if (mediaRecorder && mediaRecorder.state !== "inactive") {
        mediaRecorder.stop();
        recordStatus.style.display = 'none';
        recordButton.style.display = 'inline';
        stopButton.style.display = 'none';
        console.log('Perekaman dihentikan.');
    }
});

    </script>
</x-backoffice.layout.app-layout>
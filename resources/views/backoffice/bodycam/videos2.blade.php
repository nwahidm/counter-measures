<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8" />
    <title>ANJAY</title>
    <meta charset="UTF-8" />
    <title>DSS大华-WebSocket拉流</title>
    <link rel="stylesheet" href="{{ asset('static/WSPlayer/player.css') }}" />
    <script defer="defer" src="{{ asset('main.js') }}"></script>
</head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="{{ asset('static/WSPlayer/singleThread/libdhplay.js') }}"></script>
<script src="{{ asset('static/WSPlayer/PlayerControl.js') }}"></script>
<script src="{{ asset('static/WSPlayer/WSPlayer.js') }}"></script>
<script src="{{ asset('static/jquery-3.6.0.min.js') }}"></script>

<body style="height: 100%; margin: 0">
    <div class="content record-content" style="width: 100%; height: 100%; padding: 0">
        <div class="app-container container-xxl">
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content flex-column-fluid">
                        <div class="row g-5 g-xl-8">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <label class="fs-3 fw-bold">Lokasi Body Camera:</label>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="bodycamSelect" class="form-label">Select Bodycam Stream</label>
                                            <select id="bodycamSelect">
                                                <option value="" selected>Select a bostream</option>
                                                @foreach ($bodycam_devices as $row)
                                                    <option value="{{ $row['id'] }}" @if($row['id'] === old('id_case'))
                                                    selected @endif>
                                                        {{ $row['text'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="player-container">
                                            <div class="content record-content"
                                                style="width: 60%; height: 60%; padding: 0">
                                                <div id="ws-real-player" style="width: 60%; height: 60%; padding: 0">
                                                </div>
                                            </div>

                                            <div class="custom-controls" id="player_control_button">
                                                <button id="fullscreenButton" class="btn btn-success">Full
                                                    Screen</button>
                                                <button id="startRecording">Start Recording</button>
                                                <button id="stopRecording" disabled>Stop Recording & Download</button>

                                            </div>


                                            <!-- <iframe id="playerFrame"
                                                src="{{ route('player', 'serverIp=160.20.104.115&playerType=real&channelId=1000014$0$26') }}"
                                                frameborder="0" style="width: 100%; height: 500px;"></iframe> -->
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


    <script type="module">
        var f = window.PlayerControl;
        
        // Function to send a message to the iframe to play the RTSP stream


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
                            // console.log(playerUrl);

                            // Construct the RTSP link dynamically
                            var rtspLink = 'rtsp://160.20.104.115/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;
                            // Send the RTSP link to the iframe using the postMessage method
                            window.PLAYER_BOX.playReal(rtspLink);
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
            const videoElement = document.getElementById('ws-real-player-0-liveVideo');
            if (videoElement.requestFullscreen) {
                videoElement.requestFullscreen();
            } else if (videoElement.mozRequestFullScreen) { // Firefox
                videoElement.mozRequestFullScreen();
            } else if (videoElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
                videoElement.webkitRequestFullscreen();
            } else if (videoElement.msRequestFullscreen) { // IE/Edge
                videoElement.msRequestFullscreen();
            }
        });

        const video = document.getElementById('ws-real-player-0-liveVideo');
        const startBtn = document.getElementById('startRecording');
        const stopBtn = document.getElementById('stopRecording');

        let mediaRecorder;
        let recordedChunks = [];

        // Start recording
        startBtn.addEventListener('click', () => {
            
            const stream = video.captureStream();
            mediaRecorder = new MediaRecorder(stream, {
                mimeType: 'video/webm; codecs=vp9' // WebM format
            });

            // Collect data when recording
            mediaRecorder.ondataavailable = function (e) {
                if (e.data.size > 0) {
                    recordedChunks.push(e.data);
                }
            };

            // When stop recording, download the video
            mediaRecorder.onstop = function () {
                const blob = new Blob(recordedChunks, {
                    type: 'video/mp4' // Convert to mp4
                });
                const url = URL.createObjectURL(blob);

                // Create a link to download the recorded video
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'recorded-video.mp4'; // File name
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            };

            // Start recording
            mediaRecorder.start();
            startBtn.disabled = true;
            stopBtn.disabled = false;
        });

        // Stop recording and download
        stopBtn.addEventListener('click', () => {
            mediaRecorder.stop();
            stopBtn.disabled = true;
            startBtn.disabled = false;
        });


    </script>
</body>

</html>
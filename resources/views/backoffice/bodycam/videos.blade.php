<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8" />
    <title>ANJAY</title>
</head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

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
                                            <iframe id="playerFrame"
                                                src="{{ route('player', 'serverIp=dss.kejaksaanri.id&playerType=real&channelId=1000014$0$26') }}"
                                                frameborder="0" style="width: 100%; height: 500px;"></iframe>
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
    <!-- <script>
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

        // Trigger when iframe is loaded
        document.getElementById('playerFrame').onload = function () {
            var token = {!! "$token" !!}
            console.log('Iframe loaded', token);
            // Now that the iframe is loaded, send the RTSP link
            var rtspLink = `rtsp://160.20.104.115/dss/monitor/param/cameraid=1000012%240%26substream=1?token=${token}`;
            sendMessageToIframe(rtspLink);
        };
    </script> -->

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

                            // Log the constructed player URL for debugging
                            console.log(playerUrl);

                            // Set the dynamically created playerUrl to the iframe
                            // document.getElementById('playerFrame').src = 'http://127.0.0.1:8001/player?serverIp=160.20.104.115&playerType=real&channelId=1000012%240%2426';

                            // Construct the RTSP link dynamically
                            var rtspLink = 'rtsp://160.20.104.115/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;

                            // Send the RTSP link to the iframe using the postMessage method
                            sendMessageToIframe(rtspLink);
                        } else {
                            var channelId = response.device_dahua_id + '$0$26';
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

        document.getElementById('pauseButton').addEventListener('click', function () {
            iframe.contentWindow.postMessage({ type: 'pause' }, '*');
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



    </script>
</body>

</html>
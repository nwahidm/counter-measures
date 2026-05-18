<?php

namespace App\Http\Controllers\CommandCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CommandCenter\CommandCenterDataTable;
use App\Models\CommandCenter\CommandCenterOBD;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\OpenCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\Interview\InterviewJadwal;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use GuzzleHttp\Client;
use App\Models\CommandCenter\CommandCenterDevice;
use App\Models\CommandCenter\CommandCenterJob;
use App\Models\CommandCenter\CommandCenterCameraVideo;
use App\Helpers\CommandCenterDataHelper;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class CommandCenterController extends Controller
{
    public function index(CommandCenterDataTable $dataTable)
    {
        $command_center_cam_device = CommandCenterDevice::where('device_type', 'Camera')->first();
        $live_stream = Storage::url( "streaming/live.m3u8");
        $histori = CommandCenterCameraVideo::all();
        return $dataTable->render('backoffice.command_center.index', 
        compact('command_center_cam_device', 'live_stream','histori'));
    }

    public function show(Request $request, $id)
    {
        $data = InterviewJadwal::find($id);

        return view('backoffice.open.interview.report.show', compact('data'));
    }

    public function update(Request $request,)
    {
       
        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    protected $cameraIp;
    protected $username;
    protected $password;
    protected $process;

    public function __construct()
    {
        $this->cameraIp = env('CAMERA_IP');
        $this->username = env('CAMERA_USERNAME');
        $this->password = env('CAMERA_PASSWORD');
    }


    public function startRecord(Request $request)
    {
        $camera_id = $request->input('camera_id');

        return response()->json(['message' => 'Record started'.  $camera_id]);
    }

    public function stopRecord()
    {
        
        $camera_id = $request->input('camera_id');
        return response()->json(['error' => 'Record stopeed'.$camera_id], 400);
    }

    
    public function cameraControl(Request $request)
    {
        
        $action = $request->input('action');
        
        $response = Http::post('http://192.168.50.31:8003/api/v1/cameracontrol/move', [
            'movement' => $action,
            // Tambahkan parameter lainnya sesuai kebutuhan
        ]);

        $responseData = $response->json();
        if (isset($responseData['status'])) {
            $status = $responseData['status'];
            return response()->json(['message' => 'Request successful', 'status' => $status, 'data' => $responseData]);
        } else {
            return response()->json(['message' => 'Status key not found in response', 'data' => $responseData]);
        }
    }

    public function cameraZoomControl(Request $request)
    {
        
        $action = $request->input('action');
        
        $response = Http::post('http://192.168.50.31:8003/api/v1/cameracontrol/move', [
            'movement' => $action,
            // Tambahkan parameter lainnya sesuai kebutuhan
        ]);

        $responseData = $response->json();
        if (isset($responseData['status'])) {
            $status = $responseData['status'];
            return response()->json(['message' => 'Request successful', 'status' => $status, 'data' => $responseData]);
        } else {
            return response()->json(['message' => 'Status key not found in response', 'data' => $responseData]);
        }
    }

    public function stream(Request $request)
    {
        $rtspUrl = "rtsp://192.168.50.31:8554/live.sdp";
        $hlsOutput = storage_path("app/public/hls/test.m3u8");

        // Ensure the output directory exists
        if (!file_exists(dirname($hlsOutput))) {
            mkdir(dirname($hlsOutput), 0755, true);
        }

        // Run FFmpeg to convert RTSP to HLS
        // $process = new Process([
        //     'ffmpeg', '-i', $rtspUrl, '-c:v', 'libx264', '-hls_time', '10',
        //     '-hls_playlist_type', 'event', $hlsOutput
        // ]);
        $process = new Process([
            'ffmpeg '.' -i '. $rtspUrl . ' -c:v '. ' libx264 '. ' -hls_time ' . '10'.
        ' -hls_playlist_type '. ' event '. $hlsOutput
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return response()->json(['stream' => asset("storage/hls/test.m3u8")]);

    }

    public function getCommandCenterObdDataFirst(Request $request)
    {
        $command_center_obd_data_first = CommandCenterDataHelper::getCommandCenterObdDataFirst();

        return $command_center_obd_data_first;
    }

    public function downloadReport()
    {
        // $data = OpenCase::find(decrypt($id_case));
        $data = CommandCenterOBD::all();

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        // $imgBase64 = $data->target_photo ? DataHelper::imgToBase64($data->target_photo) : null;

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.command_center.pdf", compact('data')));

        $filename = 'Close_Exploration_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
        
    public function uploadVideo(Request $request)
    {
        $video = $request->getContent(); // get the raw content


        if ($video) {
            #save video
            $filename = 'recorded_video_cc_' . time() . '.mp4';
            $path = 'videos_command_center/' . $filename;
            $command_center_camera_video_data =  new CommandCenterCameraVideo;
            $command_center_camera_video_data->video_record_path = $path;

            $command_center_camera_video_data->save();
            Storage::disk('public')->put($path, $video);

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No video data uploaded']);
    }
}

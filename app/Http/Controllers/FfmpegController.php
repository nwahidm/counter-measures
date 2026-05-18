<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BodyCamDevice\BodyCamRecordStream;
class FfmpegController extends Controller
{
    // Method untuk memulai proses FFmpeg
    public function startRecording(Request $request)
    {
        
        $command = 'ffmpeg -rtsp_transport tcp -i "dahua.com/live/streaming" -y -c:v copy -c:a aac -b:a 128k a.mp4 > /dev/null 2>&1 & echo $!';
        $pid = shell_exec($command); // Menyimpan PID dari proses

  
        $bodycam_record_stream_data = new BodyCamRecordStream();
        $bodycam_record_stream_data->pid_process = trim($pid);
        $bodycam_record_stream_data->video_path_save = 'bodycam_record_stream/' . $fileName . '.mp4';
        $bodycam_record_stream_data->status = "0";
        $bodycam_record_stream_data->created_by = auth()->user()->id;
        $bodycam_record_stream_data->save();

        return response()->json([
            'message' => 'Recording started', 
            'pid' => $pid,
            'command'=> $command]);
    }

    // Method untuk menghentikan proses FFmpeg
    public function stopRecording(Request $request)
    {
        $userId = auth()->user()->id; // ID pengguna yang menghentikan proses
        $pid = $request->input("pid"); // Ambil PID dari session atau database

        if ($pid) {
            // Menghentikan proses dengan PID
            shell_exec("kill $pid");
            $bodycam_record_stream_data = BodyCamRecordStream::where('pid_process', $pid)->first();
            $bodycam_record_stream_data->status = "1";
            $bodycam_record_stream_data->updated_by = auth()->user()->id;
            $bodycam_record_stream_data->save();
            return response()->json([
                'message' => 'Recording stopped',
                'pid_process' => $pid,
                'video_path_save' => $bodycam_record_stream_data->video_path_save]);
        }

        return response()->json(['message' => 'No recording found for this user'], 404);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommandCenter\CommandCenterDevice;
use App\Models\CommandCenter\CommandCenterJob;
use App\Models\CommandCenter\CommandCenterCameraVideo;
use App\Models\CommandCenter\CommandCenterOBD;
use Symfony\Component\HttpFoundation\Response;

class CommandCenterController extends Controller
{
    public function getLastVideo()
    {
         // Mengambil 10 video terakhir yang diurutkan berdasarkan created_at
        $video_command_center_history = CommandCenterCameraVideo::orderBy('created_at', 'desc')->take(10)->get();

        // Cek jika data kosong
        if ($video_command_center_history->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan.',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }

        // Mengembalikan data jika ditemukan
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data ditemukan.',
            "data" => $video_command_center_history,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);
    }

    public function getLastPosition()
    {
         // Mengambil 10 video terakhir yang diurutkan berdasarkan created_at
        $video_command_center_history = CommandCenterOBD::orderBy('created_at', 'desc')->take(10)->get();

        // Cek jika data kosong
        if ($video_command_center_history->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan.',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }

        // Mengembalikan data jika ditemukan
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data ditemukan.',
            "data" => $video_command_center_history,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);
    }

    public function getCamera()
    {
         // Mengambil 10 video terakhir yang diurutkan berdasarkan created_at
        $camera_device = CommandCenterDevice::where('device_type', 'Camera')->first();

        // Cek jika data kosong
        // if ($camera_device->isEmpty()) {
        //     return response()->json([
        //         "status" => Response::HTTP_NOT_FOUND,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
        //         "message" => 'Data tidak ditemukan.',
        //         "data" => null,
        //         'timestamp' => floor(microtime(true) * 1000)
        //     ], Response::HTTP_NOT_FOUND);
        // }

        // Mengembalikan data jika ditemukan
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data ditemukan.',
            "data" => $camera_device,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);
    }


}

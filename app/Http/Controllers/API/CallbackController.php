<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\FetchKegiatanPoskoSentimentEvent;
use App\Events\FetchPollingSentimentEvent;

class CallbackController extends Controller
{
    public function callbackKegiatanPoskoSentiment(Request $request) {
        
        $id = $request->id;
        Log::info("Incomming Callback Kegiatan Posko {$id} -> Request Body  : " . json_encode($request->all()));
        Log::info("Incomming Callback Kegiatan Posko {$id} -> Headers : " . json_encode($request->headers->all()));

        /* Trigger Fetch Sentiment */
        event(new FetchKegiatanPoskoSentimentEvent($id));

        return response()->json([
            'status' => true,
            'message' => 'SUCCESS'
        ], 200);
    }

    public function callbackPollingSentiment(Request $request) {
        
        $id = $request->id;
        Log::info("Incomming Callback Polling {$id} -> Request Body  : " . json_encode($request->all()));
        Log::info("Incomming Callback Polling {$id} -> Headers : " . json_encode($request->headers->all()));

        /* Trigger Fetch Sentiment */
        event(new FetchPollingSentimentEvent($id));

        return response()->json([
            'status' => true,
            'message' => 'SUCCESS'
        ], 200);
    }
}

<?php

namespace App\Listeners;

use App\Models\KegiatanPosko;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class FetchKegiatanPoskoSentimentListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FetchKegiatanPoskoSentimentEvent $event): void
    {
        $id = $event->id;

        $startTime = microtime(true);
        Log::info("Processing Fetch kegiatan posko sentiment {$id}");

        try {
            $kegiatanPosko = KegiatanPosko::find($id);
            if ($kegiatanPosko) {
                $response = Http::get(config('constant.sentiment.url').'/api/v1/conclusiontext/getbyid?id='.$kegiatanPosko->ref_id);
                Log::info("Response Fetch kegiatan posko sentiment {$id} : {$response->body()}");

                if($response->successful()) {
                    $responseData = $response->json();
                    if ($responseData['status']) {
                        $data = $responseData['data'];
                        $kegiatanPosko->update([
                            'conclusion' => $data['conclusion'],
                            'percent_negative' => $data['sentiment_negative'],
                            'percent_neutral' => $data['sentiment_neutral'],
                            'percent_positive' => $data['sentiment_positive']
                        ]);
                        Log::info("Successfully Fetch kegiatan posko {$kegiatanPosko->id} in " .microtime(true) - $startTime.' ms');
                    }
                    else {
                        Log::info("Failed Fetch kegiatan posko sentiment cause : {$responseData['message']}");
                    }
                }
                else {
                    Log::info("Failed Fetch, Internal Server Error");
                }
            }
        }
        catch(\Exception $ex) {
            Log::error("Failed Fetch kegiatan posko sentiment Internal Server Error cause : {$ex->getMessage()}");
        }
    }
}

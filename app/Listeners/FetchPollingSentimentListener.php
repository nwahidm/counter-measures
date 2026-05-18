<?php

namespace App\Listeners;

use App\Models\KirkaCapresPolling;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\FetchPollingSentimentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class FetchPollingSentimentListener implements ShouldQueue
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
    public function handle(FetchPollingSentimentEvent $event): void
    {
        $id = $event->id;

        $startTime = microtime(true);
        Log::info("Processing Fetch polling sentiment {$id}");

        try {
            $polling = KirkaCapresPolling::find($id);
            if ($polling) {
                $response = Http::get(config('constant.sentiment.url').'/api/v1/conclusiontext/getbyid?id='.$polling->ref_id);
                Log::info("Response Fetch polling sentiment {$id} : {$response->body()}");

                if($response->successful()) {
                    $responseData = $response->json();
                    if ($responseData['status']) {
                        $data = $responseData['data'];
                        $polling->update([
                            'conclusion' => $data['conclusion'],
                            'percent_negative' => $data['sentiment_negative'],
                            'percent_neutral' => $data['sentiment_neutral'],
                            'percent_positive' => $data['sentiment_positive']
                        ]);
                        Log::info("Successfully Fetch polling {$polling->id} in " .microtime(true) - $startTime.' ms');
                    }
                    else {
                        Log::info("Failed Fetch polling sentiment cause : {$responseData['message']}");
                    }
                }
                else {
                    Log::info("Failed Fetch, Internal Server Error");
                }
            }
        }
        catch(\Exception $ex) {
            Log::error("Failed Fetch polling sentiment Internal Server Error cause : {$ex->getMessage()}");
        }
    }
}

<?php

namespace App\Listeners;

use App\Models\KirkaCapresPolling;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CreatePollingSentimentEvent;

class CreatePollingSentimentListener implements ShouldQueue
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
    public function handle(CreatePollingSentimentEvent $event): void
    {
        $polling = $event->polling;
        $callbackUrl = $event->callbackUrl;

        $startTime = microtime(true);
        Log::info("Processing Create polling sentiment {$polling->id}");

        try {
            $requestData = [
                'text' => $polling->informasi_diperoleh,
                'callback_url' => $callbackUrl
            ];

            $response = Http::post(config('constant.sentiment.url').'/api/v1/conclusiontext/add', $requestData);
            Log::info("Response Create polling sentiment {$polling->id} : {$response->body()}");

            if($response->successful()) {
                $responseData = $response->json();
                if ($responseData['status']) {
                    $pollingExisting = KirkaCapresPolling::find($polling->id);
                    if ($pollingExisting) {
                        $polling->update([
                            'ref_id' => $responseData['data']['id']
                        ]);
                        Log::info("Successfully Create polling {$polling->id} in " .microtime(true) - $startTime.' ms');
                    }
                }
                else {
                    Log::info("Failed Create polling sentiment cause : {$responseData['message']}");
                }
            }
            else {
                Log::info("Failed Create polling sentiment cause, Internal Server Error");
            }
        }
        catch(\Exception $ex) {
            Log::error("Failed Create polling sentiment Internal Server Error cause : {$ex->getMessage()}");
        }
    }
}

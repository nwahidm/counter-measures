<?php

namespace App\Listeners;

use App\Models\KegiatanPosko;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CreateKegiatanPoskoSentimentEvent;

class CreateKegiatanPoskoSentimentListener implements ShouldQueue
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
    public function handle(CreateKegiatanPoskoSentimentEvent $event): void
    {
        $kegiatanPosko = $event->kegiatanPosko;
        $callbackUrl = $event->callbackUrl;

        $startTime = microtime(true);
        Log::info("Processing Create kegiatan posko sentiment {$kegiatanPosko->id}");

        try {
            $requestData = [
                'text' => $kegiatanPosko->uraian_singkat,
                'callback_url' => $callbackUrl
            ];

            $response = Http::post(config('constant.sentiment.url').'/api/v1/conclusiontext/add', $requestData);
            Log::info("Response Create kegiatan posko sentiment {$kegiatanPosko->id} : {$response->body()}");

            if($response->successful()) {
                $responseData = $response->json();
                if ($responseData['status']) {
                    $kegiatanPoskoExisting = KegiatanPosko::find($kegiatanPosko->id);
                    if ($kegiatanPoskoExisting) {
                        $kegiatanPosko->update([
                            'ref_id' => $responseData['data']['id']
                        ]);
                        Log::info("Successfully Create kegiatan posko {$kegiatanPosko->id} in " .microtime(true) - $startTime.' ms');
                    }
                }
                else {
                    Log::info("Failed Create kegiatan posko sentiment cause : {$responseData['message']}");
                }
            }
            else {
                Log::info("Failed Create kegiatan posko sentiment cause, Internal Server Error");
            }
        }
        catch(\Exception $ex) {
            Log::error("Failed Create kegiatan posko sentiment Internal Server Error cause : {$ex->getMessage()}");
        }
    }
}

<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Events\KinerjaMysimkariProcessEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class KinerjaMysimkariProcessListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\KinerjaMysimkariProcessEvent  $event
     * @return void
     */
    public function handle(KinerjaMysimkariProcessEvent $event)
    {
        $time = time();
        
        Log::info("[$time] Enter create kinerja sipede event");

        $data = $event->data;
        $token = GetToken();
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
        $baseUrl = config('constants.kinerja_sipede.base_url');
        $createEventUrl = config('constants.kinerja_sipede.event_create');

        Log::info("[$time] Data Kinerja Sipede event : ".json_encode($data));
        Log::info("[$time] URL Kinerja Sipede event : ".$baseUrl.$createEventUrl);
        $response = Http::withHeaders($headers)->post($baseUrl.$createEventUrl, $data);

        Log::info("[$time] Response create Kinerja Mysimkari event : " . $response->body());
    }
}

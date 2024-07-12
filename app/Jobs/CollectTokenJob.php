<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CollectTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $walletId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($walletId)
    {
        $this->walletId = $walletId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiUrl = config('app.scan_url');
        $apiKey = config('app.scan_api_key');
        $headers = [
            'Content-Type: application/json',
            'x-api-token' => $apiKey,
        ];
        $body = ['wallet_id' => $this->walletId];

        Http::withHeaders($headers)->post($apiUrl . '/collect-token', $body);
    }
}

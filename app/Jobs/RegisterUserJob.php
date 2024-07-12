<?php

namespace App\Jobs;

use App\Mail\RegisterUserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class RegisterUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $username;
    private $token;
    private $lang;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $username, $token, $lang)
    {
        $this->email = $email;
        $this->username = $username;
        $this->token = $token;
        $this->lang = $lang;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        App::setLocale($this->lang);

        Mail::to($this->email)->send(new RegisterUserMail($this->email, $this->username, $this->token));
    }
}

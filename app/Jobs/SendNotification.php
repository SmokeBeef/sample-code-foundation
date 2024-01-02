<?php

namespace App\Jobs;

use App\Mail\Remember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $delay;
    /**
     * Create a new job instance.
     */
    protected $email;
    protected $tgl;

    public function __construct($email, $tgl)
    {
        $this->email = $email;
        $this->tgl = $tgl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new Remember($this->tgl));
    }
}

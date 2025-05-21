<?php

namespace App\Service\SendNotificationService;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Powitanie')
            ->view('notification')
            ->with([
                'name' => $this->name,
            ]);
    }
}

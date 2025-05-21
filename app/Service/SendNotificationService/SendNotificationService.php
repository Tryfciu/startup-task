<?php

namespace App\Service\SendNotificationService;

use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendNotificationService
{
    public function sendNotification(User $user): void
    {
        $user->emails->each(function (Email $email) use ($user) {
            Mail::to($email->email)->queue(new NotificationEmail($user->name));
        });
    }
}
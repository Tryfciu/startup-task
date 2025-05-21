<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\User;
use App\Service\SendNotificationService\NotificationEmail;
use App\Service\SendNotificationService\SendNotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendNotificationServiceTest extends TestCase
{
    use DatabaseTransactions;

    private SendNotificationService $notificationService;

    public function setUp(): void
    {
        parent::setUp();
        $this->notificationService = app(SendNotificationService::class);
    }

    #[Test]
    public function it_sends_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        Email::factory(2)->forUser($user)->create();

        $this->notificationService->sendNotification($user);

        Mail::assertQueued(NotificationEmail::class, 2);

        $user->emails->each(function (Email $email) use ($user) {
            Mail::assertQueued(NotificationEmail::class, function ($queuedEmail) use ($user, $email) {
                return $queuedEmail->hasTo($email->email) && $queuedEmail->name === $user->name;
            });
        });
    }
}
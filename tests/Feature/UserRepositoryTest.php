<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);
    }

    #[Test]
    public function it_creates_user(): void
    {
        $name = 'Paul';
        $phoneNumber = '';
        $emails = ['superemail@gmail.com', 'superemail2@gmail.com'];

        $user = $this->userRepository->createUser($name, $phoneNumber, $emails);
        $this->assertDatabaseHas('users', ['name' => $name, 'phone_number' => $phoneNumber]);
        collect($emails)->each(fn (string $email) => $this->assertDatabaseHas('emails', [
            'email' => $emails,
            'user_id' => $user->id,
        ]));
    }

    #[Test]
    public function it_validates_if_email_is_not_yet_taken(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('At least one email is already taken');
        $email = 'superemail@gmail.com';
        $this->userRepository->createUser('name', '123123123', [$email]);
        $this->userRepository->createUser('name', '123123123', [$email]);
    }

    #[Test]
    public function it_updates_the_user(): void
    {
        $oldEmail = 'superemail@gmail.com';
        $user = $this->userRepository->createUser('Paul', '123123123', [$oldEmail]);

        $name = 'Peter';
        $phoneNumber = '123987456';
        $newMail = 'gigaemail@gmail.com';

        $this->userRepository->updateUser($user, $name, $phoneNumber, [$newMail]);

        $this->assertDatabaseHas('users', ['name' => $name, 'phone_number' => $phoneNumber]);
        $this->assertDatabaseHas('emails', ['email' => $newMail, 'user_id' => $user->id]);
        $this->assertDatabaseMissing('emails', ['email' => $oldEmail]);
    }

    #[Test]
    public function it_deletes_the_user(): void
    {
        $user = $this->userRepository->createUser('Paul', '123123123', ['supermeail@email.com']);
        $this->userRepository->deleteUser($user);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_returns_user(): void
    {
        $user = $this->userRepository->createUser('Paul', '123123123', ['supermeail@email.com']);
        $user2 = $this->userRepository->getUser($user->id);
        $this->assertSame($user->id, $user2->id);
    }

    #[Test]
    public function it_returns_all_users(): void
    {
        Email::query()->delete();
        User::query()->delete();
        $user = $this->userRepository->createUser('Paul', '123123123', ['paulsuper@email.com']);
        $user2 = $this->userRepository->createUser('Peter', '987654321', ['superpeter@email.com']);
        $users = $this->userRepository->getAllUsers();
        $this->assertCount(2, $users);
        $this->assertCount(2, $users->pluck('id')->intersect([$user->id, $user2->id]));
    }
}
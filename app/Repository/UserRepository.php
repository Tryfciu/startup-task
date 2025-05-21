<?php

namespace App\Repository;

use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class UserRepository
{
    /**
     * @param string[] $emails
     */
    public function createUser(string $name, string $phoneNumber, array $emails): User
    {
        $emailExists = Email::whereIn('email', $emails)->exists();
        if($emailExists) {
            throw ValidationException::withMessages(['emails' => 'At least one email is already taken']);
        }

        $user = User::create(['name' => $name, 'phone_number' => $phoneNumber]);
        $this->createEmailsForUser($user, $emails);

        return $user;
    }

    /**
     * @param string[] $emails
     */
    public function updateUser(User $user, string $name, string $phoneNumber, array $emails): User
    {
        $emailExists = Email::whereIn('email', $emails)->where('user_id', '!=', $user->id)
            ->exists();
        if($emailExists) {
            throw ValidationException::withMessages(['emails' => 'At least one email is already taken']);
        }

        $user->update(['name' => $name, 'phone_number' => $phoneNumber]);
        $user->emails()->delete();
        $this->createEmailsForUser($user, $emails);

        return $user;
    }

    public function getUser(string $userId): User
    {
        return User::findOrFail($userId);
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    /**
     * @param string[] $emails
     */
    private function createEmailsForUser(User $user, array $emails): void
    {
        $user->emails()->createMany(collect($emails)->map(fn (string $email) => ['email' => $email]));
    }
}
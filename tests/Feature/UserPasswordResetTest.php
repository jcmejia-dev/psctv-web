<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;

class UserPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_password_reset_sends_email_and_updates_password()
    {
        // Create a test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('oldpassword'),
            'role' => 'admin',
        ]);

        // Mock the Mail facade to prevent actual email sending
        Mail::shouldReceive('to->send')
            ->once()
            ->with(\Mockery::on(function ($mail) use ($user) {
                return $mail instanceof SendPasswordMail &&
                       $mail->email === $user->email &&
                       $mail->name === $user->name &&
                       strlen($mail->password) === 10; // Random password length
            }));

        // Act: Call the sendPasswordReset method
        $response = $this->actingAs($user, 'web')
            ->post(route('users.send-password-reset', $user->id));

        // Assert: Check that the response is a redirect with success message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'New password has been sent to the user\'s email address.');

        // Assert: Check that the user's password has been updated
        $user->refresh();
        $this->assertNotEquals(bcrypt('oldpassword'), $user->password);
    }
}

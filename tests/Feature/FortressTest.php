<?php

namespace Tmkzmu\Fortress\Feature;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Str;
use Tests\CreatesApplication;

class FortressTest extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = 'secretSecret';
        $user['password_confirmation'] = 'secretSecret';
        $this->postJson(route('register'), $user)->assertCreated();
        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    public function test_login()
    {
        $user = User::factory()->create(['password' => Hash::make('secret')]);
        $response = $this->postJson(route('login'), ['username' => $user->email, 'password' => 'secret'])->assertOk();
        $this->assertTrue(is_string($response->json('access_token')));
    }

    public function test_ask_verify_email()
    {
        $user = User::factory()->unverified()->create();
        Notification::fake();
        $this->actingAs($user);
        $this->postJson(route('verification.ask'))->assertOk();
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verify_email()
    {
        $user = User::factory()->unverified()->create(['token' => Str::random(40)]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('fortress.auth.verification_expire', 60)),
            [
                'id'    => $user->getKey(),
                'hash'  => sha1($user->getEmailForVerification()),
                'token' => $user->token,
            ]
        );

        $this->actingAs($user);
        $this->getJson($url)->assertOk();
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_password_reset()
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'password' => 'test']);
        $this->actingAs($user);
        Notification::fake();
        $this->postJson(route('reset-password.ask'), ['email' => $user->email])->assertOk();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->postJson(route('reset-password.reset'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'passwordPassword',
                'password_confirmation' => 'passwordPassword',
            ]);
            $response->assertOk();
            $this->assertNotEquals('test', $user->refresh()->password);
            return true;
        });
    }

    public function test_update_password()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);
        $newPass = 'testPasswordNew';
        $this->patchJson(route('update-password'), ['password' => $newPass, 'password_confirmation' => $newPass])->assertOk();
        $this->assertTrue(Hash::check($newPass, $user->refresh()->password));
    }

    public function test_update_profile()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);
        $newUserData = User::factory()->make()->toArray();
        $this->putJson(route('update-profile'), $newUserData)->assertOk();
        $this->assertDatabaseHas('users', [
            'email' => $newUserData['email'],
            'first_name'  => $newUserData['first_name'],
            'last_name'  => $newUserData['last_name'],
        ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);
        $this->post(route('logout'))->assertOk();
    }
}

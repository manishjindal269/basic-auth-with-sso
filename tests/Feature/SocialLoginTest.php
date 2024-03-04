<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSocialAuth;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    public function test_redirect_to_google()
    {
        $provider = 'google';

        $response = $this->get(route('social.login', ['provider' => $provider]));

        $response->assertRedirect(); // Ensure it redirects to Google's authentication page
    }

    public function test_social_profile_linkage()
    {
        // Create a user
        $user = User::factory()->create(['email' => 'test@example.com']);

        // Create a social authentication record
        UserSocialAuth::create([
            'user_id' => $user->id,
            'provider_user_id' => '123',
            'provider_type' => 'google',
        ]);

        // Assert the linkage between user and social profile
        $this->assertDatabaseHas('user_social_auths', [
            'user_id' => $user->id,
            'provider_user_id' => '123',
            'provider_type' => 'google',
        ]);
    }
    public function test_callback_from_google()
    {
        // Simulate a valid provider response (e.g., Google)
        $provider = 'google';
        // Create a mock object mimicking a Socialite user object
        $providerUser = Mockery::mock('SocialiteUser');
        $providerUser->shouldReceive('getId')->andReturn(123); 
        $providerUser->shouldReceive('getName')->andReturn('John Doe'); 
        $providerUser->shouldReceive('getEmail')->andReturn('john@example.com');

        // Mock the findOrCreateUser method to return a user without database interaction
        $user = User::factory()->make(['email' => 'john@example.com']);
        // Mock the Socialite driver
        Socialite::shouldReceive('driver')->andReturnSelf();
        Socialite::shouldReceive('driver->user')->andReturn($providerUser);

        // Perform the request to your callback route
        $response = $this->get(route('social.callback', ['provider' => $provider]));

        // Assert that the user is logged in
        $this->assertTrue(Auth::check());

        // Assert that the user is redirected to the dashboard route
        $response->assertRedirect(route('dashboard'));
    }
}

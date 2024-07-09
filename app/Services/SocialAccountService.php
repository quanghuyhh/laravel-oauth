<?php

namespace App\Services;

use App\Enums\SocialDriveEnum;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountService
{
    public function __construct(
        public SocialAccount $socialAccount,
    ) {
    }

    public function authWithSocialUser(SocialUser $socialUser, string $provider)
    {
        $socialAccount = $this->getUserWithSocialProvider($socialUser->getId(), $provider);
        $user = optional($socialAccount)->user;
        if (empty($user)) {
            $user = User::query()->firstOrCreate(
                [
                    'email' => $socialUser->getEmail(),
                ],
                [
                    'name' => $socialUser?->getName() ?? $socialUser?->getNickname() ?? fake()->name,
                    'email_verified_at' => now(),
                    'password' => Str::random(16),
                ]
            );
            $this->attachUserSocialAccount($user, $socialUser, $provider);
        }

        return $user;
    }

    public function getUserWithSocialProvider(string $provider_user_id, string $provider): ?Model
    {
        return $this->socialAccount->userExists($provider_user_id, $provider)->first();
    }

    public function attachUserSocialAccount(User $user, SocialUser $socialUser, string $provider): Model|Builder
    {
        return $this->socialAccount->firstOrCreate(
            [
                'provider_user_id' => $socialUser->getId(),
                'provider' => $provider,
            ],
            [
                'user_id' => $user->id,
                'email' => $socialUser?->getEmail(),
                'access_token' => $socialUser?->token,
                'refresh_token' => $socialUser?->refreshToken,
                'details' => (array) $socialUser,
            ]
        );
    }

    public function fetchUserFromSocial(SocialAccount $socialAccount)
    {
        $providerSettings = config("services.{$socialAccount->provider}");
        $scopes = explode(',', $providerSettings['scopes'] ?? '');
        $socialite = match ($socialAccount->provider) {
            SocialDriveEnum::GOOGLE->value => Socialite::driver($socialAccount->provider)->scopes($scopes)
                ->with(["access_type" => "offline", "prompt" => "consent select_account"]),
            default => Socialite::driver($socialAccount->provider)->scopes($scopes),
        };
        $token = $socialite->refreshToken($socialAccount->refresh_token);
        return $this->transformProfileFromSocialAccount($socialite->userFromToken($token->token));
    }

    protected function transformProfileFromSocialAccount(SocialUser $socialUser)
    {
        return [
            'email' => $socialUser->getEmail(),
            'name' => $socialUser->getName(),
            'avatar' => $socialUser->getAvatar(),
        ];
    }
}

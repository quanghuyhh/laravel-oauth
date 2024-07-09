<?php

namespace App\Http\Controllers;

use App\Enums\SocialDriveEnum;
use App\Http\Controllers\Controller;
use App\Services\SocialAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SocialAuthController extends Controller
{
    public function __construct(
        public SocialAccountService $service,
    ) {
    }

    public function handleRedirect(SocialDriveEnum $provider, Request $request): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        $providerSettings = config("services.{$provider->value}");
        $scopes = explode(',', $providerSettings['scopes'] ?? '');
        $socialite = match ($provider->value) {
            SocialDriveEnum::GOOGLE->value => Socialite::driver($provider->value)->scopes($scopes)
                ->with(["access_type" => "offline", "prompt" => "consent select_account"]),
            default => Socialite::driver($provider->value)->scopes($scopes),
        };

        return $socialite->redirect();
    }

    public function handleCallback(SocialDriveEnum $provider, Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $socialUser = Socialite::driver($provider->value)->user();
            throw_if(
                empty($socialUser),
                new \Exception(trans('Failed to authenticate with '.ucfirst($provider->value).'. Please try again!'))
            );

            $user = $this->service->authWithSocialUser($socialUser, $provider->value);

            DB::commit();

            Auth::login($user);
            Session::regenerate();

            return redirect()->route('home');
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return redirect()->route('login')->withErrors([
                'email' => $throwable->getMessage(),
            ]);
        }
    }

    public function refreshProfile()
    {
        $user = auth()->user();
        $socialProfiles = [];
        foreach ($user->socialAccounts as $account) {
            $socialProfiles[$account->provider] = $this->service->fetchUserFromSocial($account);
        }
        return view('users.partials.social-account', [
            'socialAccounts' => $socialProfiles,
        ])->render();
    }
}

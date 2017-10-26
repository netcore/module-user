<?php

namespace Modules\User\Traits;

use App\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;

trait ControllerSocialite
{
    /**
     * Get available providers
     *
     * @return array
     */
    protected function getProviders()
    {
        $providers = [];

        foreach(config('netcore.module-user.socialite-providers') as $provider => $state) {
            if($state) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }

    /**
     * Redirect user to provider login page
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerRedirect(string $provider)
    {
        $this->providerGate($provider);

        // Set redirect URL on the fly (must be absolute)
        config()->set(
            'services.' . $provider . '.client_id',
            setting()->get($provider . '_client_id')
        );
        config()->set(
            'services.' . $provider . '.client_secret',
            setting()->get($provider . '_client_secret')
        );
        config()->set(
            'services.' . $provider . '.redirect',
            url('/login/' . $provider . '/callback')
        );

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerCallback(string $provider)
    {
        $this->providerGate($provider);

        // Set redirect URL on the fly (must be absolute)
        config()->set(
            'services.' . $provider . '.redirect',
            url('/login/' . $provider . '/callback')
        );

        try {
            // Get the provider user
            $providerUser = Socialite::driver($provider)->fields([
                'name',
                'first_name',
                'last_name',
                'email'
            ])->user();

            // Find out if user with given email is registered
            $systemUser = User::whereEmail($providerUser->getEmail())->first();

            if($systemUser) {
                $identity = $systemUser->oauthIdentities()->firstOrCreate([
                    'provider' => $provider,
                    'provider_id' => $providerUser->getId()
                ]);

                // Update last login time
                $identity->setLastLoginTime();

                auth()->login($systemUser);

                return $this->redirectAfterLogin();
            }

            // User with provider provided email is not found
            // Put socialite user to session and then redirect to register page to finish registration

            session()->put('socialRegister', [
                'provider' => $provider,
                'providerUser' => $providerUser
            ]);

            return $this->redirectToRegisterPage();

        } catch (Exception $exception) {
            return $this->handleBadProviderResponse($exception, $provider);
        }
    }


    /**
     * Check for provider existence
     *
     * @param string $provider
     * @return void|\Illuminate\Http\RedirectResponse
     */
    private function providerGate(string $provider)
    {
        if (!in_array($provider, $this->getProviders(), true)) {
            abort(404);
        }
    }

    /**
     * Bad response handler
     *
     * @param Exception $exception
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleBadProviderResponse(Exception $exception, string $provider)
    {
        $attempts = session($provider . '-attempts', 0);

        if ($attempts > 5) {
            session()->forget($provider . '-attempts');

            return redirect()->route('login')->withErrors(
                trans('login.unexpected_provider_error')
            );
        }

        $attempts++;

        session()->put($provider . '-attempts', $attempts);

        return $this->providerRedirect($provider);
    }

}
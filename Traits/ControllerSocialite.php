<?php

namespace Modules\User\Traits;

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

        foreach (config('netcore.module-user.socialite-providers') as $provider => $state) {
            if ($state) {
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

        return Socialite::driver($provider)->redirect();
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

        // Set redirect URL on the fly (must be absolute)
        config()->set('services.' . $provider . '.client_id', setting()->get('oauth.' . $provider . '_client_id'));
        config()->set('services.' . $provider . '.client_secret', setting()->get('oauth.' . $provider . '_client_secret'));
        config()->set('services.' . $provider . '.redirect', url('/login/' . $provider . '/callback'));
    }

    /**
     * Bad response handler
     *
     * @param Exception $exception
     * @param string    $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleBadProviderResponse(Exception $exception, string $provider)
    {
        $attempts = session($provider . '-attempts', 0);

        if ($attempts > 5) {
            session()->forget($provider . '-attempts');

            return redirect()->route('login')->withErrors(trans('login.unexpected_provider_error'));
        }

        $attempts++;

        session()->put($provider . '-attempts', $attempts);

        return $this->providerRedirect($provider);
    }

}

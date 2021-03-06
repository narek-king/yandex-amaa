<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                $params = array(
                    'format' => 'json',
                    'oauth_token' => $request->input('api_token')
                );
                $userInfo = json_decode(file_get_contents('https://login.yandex.ru/info' . '?' . urldecode(http_build_query($params))), true);

                $users = json_decode(env('USERS'));
                if (in_array($userInfo['default_email'], $users)) {
                    $user = new User();
                    $user->name = $userInfo['real_name'];
                    $user->email = $userInfo['default_email'];
                    return $user;
                }
            }
            return null;
        });
    }
}

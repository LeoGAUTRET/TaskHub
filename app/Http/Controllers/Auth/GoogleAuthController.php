<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Google_Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class GoogleAuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));

        $client->setScopes([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/calendar',
        ]);

        $authUrl = $client->createAuthUrl();

        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));

        $code = $request->get('code');

        $accessToken = $client->fetchAccessTokenWithAuthCode($code);

        $client->setAccessToken($accessToken);

        // Récupérez les informations de l'utilisateur depuis le jeton d'accès s'il est disponible
        $userInfo = $client->verifyIdToken();
        $userId = $userInfo['sub'];
        $userName = $userInfo['name'];
        $userEmail = $userInfo['email'];
        $userUrlPicture = $userInfo['picture'];
        $userLanguage = $userInfo['locale'];

        User::updateOrCreate(
            ['user_id' => $userId],
            [
                'name' => $userName,
                'email' => $userEmail,
                'url_picture' => $userUrlPicture,
                'language' => $userLanguage,
            ]
        );

        if (is_array($accessToken)) {
            $accessToken = json_encode($accessToken);
        }

        // Stockez l'identifiant de l'utilisateur dans un cookie
        return redirect()->route('home')
            ->withCookie(cookie()->forever('google_user_id', $userInfo['sub'])) // 'sub' est l'identifiant unique de l'utilisateur
            ->withCookie(cookie()->forever('google_access_token', $accessToken));
    }

}

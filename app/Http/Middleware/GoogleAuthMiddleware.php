<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Oauth2;

class GoogleAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifiez si le cookie "google_access_token" existe
        if ($request->hasCookie('google_access_token')) {
            // Obtenez le jeton d'accès depuis le cookie
            $accessToken = $request->cookie('google_access_token');

            // Convertissez le jeton JSON en tableau associatif
            $accessToken = json_decode($accessToken, true);

            // Assurez-vous que le jeton contient un "access_token" valide
            if (isset($accessToken['access_token'])) {
                // Créez une instance de Google_Client
                $client = new Google_Client();
                $client->setAccessToken($accessToken);

                // Assurez-vous que le jeton d'accès n'a pas expiré
                if (!$client->isAccessTokenExpired()) {
                    // Créez une instance de Google_Service_Oauth2 pour obtenir des informations sur l'utilisateur
                    $oauth2Service = new Google_Service_Oauth2($client);
                    $userInfo = $oauth2Service->userinfo->get();

                    // Vous pouvez maintenant utiliser $userInfo pour identifier l'utilisateur
                    // Par exemple, vous pouvez stocker l'ID de l'utilisateur dans la session
                    Session::put('user_id', $userInfo->getId());
                    Session::put('user_name', $userInfo->getName());

                    // Poursuivez la requête
                    return $next($request);
                }
            }
        }

        // Si le cookie n'existe pas, s'il ne contient pas un jeton valide
        // ou si le jeton a expiré, redirigez l'utilisateur vers la page de connexion
        return redirect()->route('login');
    }
}

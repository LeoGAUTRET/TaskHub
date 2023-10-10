<?php

namespace App\Http\Controllers;

use Google_Client;
use App\Models\User;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class GoogleCalendarController extends Controller
{
    public function index()
    {
        // Récupérez le jeton d'accès depuis la session
        $accessToken = Cookie::get('google_access_token');

        // Créez une nouvelle instance de Google_Client
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->setAccessType('offline');

        // Configurez le client avec le jeton d'accès
        $client->setAccessToken($accessToken);

        // Assurez-vous que le jeton d'accès est valide
        if ($client->isAccessTokenExpired()) {
            // Redirigez l'utilisateur vers l'URL d'autorisation
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        }

        // Utilisez le client pour accéder à Google Calendar
        $service = new Google_Service_Calendar($client);

        // Liste des événements du calendrier
        $events = $service->events->listEvents('primary');

        // Faites quelque chose avec la liste des événements
        // var_dump($events);
        print("<pre>".print_r($events,true)."</pre>");
    }

    public function callback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        // Récupérez le code d'autorisation depuis la requête
        $code = $request->get('code');

        // Échangez le code d'autorisation contre un jeton d'accès
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);

        // Stockez le jeton d'accès (par exemple, en base de données) pour une utilisation future
        // $this->storeAccessToken($accessToken);

        // Redirigez l'utilisateur vers la page de calendrier
        return redirect('/calendar');
    }

    public function createOrUpdate()
    {
        $events = $this->getCalendar();

        $user = User::find(Cookie::get('google_user_id'));

        if($user) {
            $etag = $events['etag'];
            $bddetag = $user->etag; #TODO create or update calendar with bdd user_calendar with etag

            if ($etag !== $bddetag) {
                foreach($events['items'] as $item) {
                    #TODO Update le calendrier
                }
            }
        }

        return false;
    }

    public function getCalendar()
    {
        // Récupérez le jeton d'accès depuis la session
        $accessToken = Cookie::get('google_access_token');

        // Créez une nouvelle instance de Google_Client
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->setAccessType('offline');

        // Configurez le client avec le jeton d'accès
        $client->setAccessToken($accessToken);

        // Assurez-vous que le jeton d'accès est valide
        if ($client->isAccessTokenExpired()) {
            // Redirigez l'utilisateur vers l'URL d'autorisation
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        }

        // Utilisez le client pour accéder à Google Calendar
        $service = new Google_Service_Calendar($client);

        // Liste des événements du calendrier
        $events = $service->events->listEvents('primary');

        return $events;

        // Faites quelque chose avec la liste des événements
        // var_dump($events);
    }
}

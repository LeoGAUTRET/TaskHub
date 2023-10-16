<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use App\Models\User;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use Illuminate\Support\Facades\Cookie;

class GoogleCalendarController extends Controller
{
    public function index()
    {
        $this->createOrUpdate();

        $events = CalendarEvent::all();

        return view('calendar', ['events' => $events]);
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

        $user = User::where('user_id', Cookie::get('google_user_id'))->first();

        #TODO lors de l'enregistrement, si l'evenement est sur toute la journée cela crée une exeption et met la date au moment de la création du model

        if($user) {
            $etag = $events['etag'];
            $bddetag = $user->calendar_etag;

            if ($etag !== $bddetag || $bddetag === null) {
                foreach($events['items'] as $event) {
                    $calendar_event = CalendarEvent::where('calendar_event_id', $event->id)->first();
                    $event_etag = $event->etag;

                    if($event->colorId === null) {
                        $colorId = 12;
                    }
                    else {
                        $colorId = $event->colorId;
                    }

                    if(!isset($calendar_event)) {
                        $newEvent = new CalendarEvent([
                            'calendar_event_id' => $event->id,
                            'title' => $event->summary,
                            'description' => $event->description,
                            'location' => $event->location,
                            'color_id' => $colorId,
                            'start' => Carbon::parse($event->start->dateTime),
                            'end' => Carbon::parse($event->end->dateTime),
                            'etag' => $event_etag,
                            'user_id' => $user->user_id,
                        ]);
                        $newEvent->save();
                    }
                    else {
                        $calendar_event_etag = $calendar_event->etag;

                        if ($event_etag !== $calendar_event_etag) {
                            $calendar_event->update([
                                'title' => $event->summary,
                                'description' => $event->description,
                                'location' => $event->location,
                                'color_id' => $colorId,
                                'start' => Carbon::parse($event->start->dateTime),
                                'end' => Carbon::parse($event->end->dateTime),
                                'etag' => $event_etag,
                                'user_id' => $user->user_id,
                            ]);
                        }
                    }
                }

                $user->calendar_etag = $etag;
                $user->save();
            }
        }

        return false;
    }

    public function getCalendar()
    {
        $accessToken = Cookie::get('google_access_token');

        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->setAccessType('offline');

        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        }

        $service = new Google_Service_Calendar($client);

        $events = $service->events->listEvents('primary');

        // print("<pre>".print_r($events,true)."</pre>");

        return $events;
    }
}

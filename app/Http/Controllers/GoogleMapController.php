<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GoogleMapController extends Controller
{
    private $apiKey;
    public $latitude;
    public $longitude;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_API_KEY');
        $this->latitude = $_COOKIE['latitude'];
        $this->longitude = $_COOKIE['longitude'];
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getCurrentAdress()
    {
        $client = new Client();

        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'latlng' => "$this->latitude,$this->longitude",
                'key' => $this->getApiKey(),
            ],
        ]);

        $data = json_decode($response->getBody());

        if ($data->status === 'OK') {
            $address = $data->results[0]->formatted_address;

            return $address;
        } else {
            echo 'Adresse non trouvée';
        }

        return false;
    }

    public function calculateRoute()
    {
        try {
            $client = new Client();

            $origin = $this->getCurrentAdress();

            if($origin === false) {
                return false;
            }
            // $destination = '28 Av. de la Blancheraie, 49100 Angers, France';
            $destination = '14 Gruteau, Mazé-Milon';


            $response = $client->get("https://maps.googleapis.com/maps/api/directions/json", [
                'query' => [
                    'origin' => $origin,
                    'destination' => $destination,
                    'key' => $this->getApiKey(),
                ],
            ]);

            $data = json_decode($response->getBody());

            dd($data);

            $secondes = $data->routes[0]->legs[0]->duration->value;
        } catch (RequestException $e) {
            // Gérer l'exception de la requête HTTP, par exemple, en enregistrant une erreur ou en renvoyant une réponse d'erreur.
            return response()->json(['error' => 'Erreur lors de la requête à l\'API Google Maps.'], 500);
        } catch (\Exception $e) {
            // Gérer d'autres exceptions possibles.
            return response()->json(['error' => 'Une erreur inattendue s\'est produite.'], 500);
        }
    }
}

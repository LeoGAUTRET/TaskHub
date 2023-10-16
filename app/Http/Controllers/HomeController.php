<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\GoogleMapController;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userName = Session::get('user_name');

        $googleMapController = new GoogleMapController();
        $currentAddress = $googleMapController->getCurrentAdress();

        $googleCalendarController = new GoogleCalendarController();
        $googleCalendarController->createOrUpdate();

        $today = Carbon::now()->toDateString(); // Obtenez la date d'aujourd'hui sous forme de chaîne

        $events = CalendarEvent::whereDate('start', '=', $today)->get();

        dd($events);

        return view('home', ['username' => $userName, 'currentAddress' => $currentAddress, 'events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

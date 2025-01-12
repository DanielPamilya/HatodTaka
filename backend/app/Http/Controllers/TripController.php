<?php

namespace App\Http\Controllers;

use App\Events\TripAccepted;
use App\Events\TripCreated;
use App\Events\TripEnded;
use App\Events\TripLocationUpdated;
use App\Events\TripStarted;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        $trip = $request->user()->trips()->create($request->only([
            'origin',
            'destination',
            'destination_name'
        ]));

        return $trip;
    }

    public function show(Request $request, Trip $trip){
        //is the trip associated with the authenticated user?
        if($trip->user->id === $request->user()->id){
            return $trip;
        }

        if ($trip->driver && $request->user()->driver) {
            if ($trip->driver->id === $request->user()->driver->id) {
                return $trip;
            }
        }
       

        return response()->json(['meesage' => 'Cannot find the trip.'], 404);
    }

    public function accept(Request $request, Trip $trip){
        //a driver accept a trip
        $request->validate([
            'driver_location' => 'required',
        ]);

        $trip->update([
            'driver_id' => $request->user()->id,
            'driver_location' => $request->driver_location,
        ]);

        TripAccepted::dispatch($trip, $request->user());

        $trip->load('driver.user');

        return $trip;
    }
    
    public function start(Request $request, Trip $trip){
        //a driver started taking its passenger to destination
        $trip->update([
            'is_started' => true,
        ]);

        $trip->load('driver.user');

        TripStarted::dispatch($trip, $request->user());

        return $trip;
    }
    
    public function end(Request $request, Trip $trip){
        //a driver stopped taking its passenger to destination
        $trip->update([
            'is_complete' => true,
        ]);

        $trip->load('driver.user');

        TripEnded::dispatch($trip, $request->user());

        return $trip;
    }
    
    public function location(Request $request, Trip $trip){
        //update the current location of the driver
        $request->validate([
            'driver_location' => 'required',
        ]);

        $trip->update([
            'driver_location' => $request->driver_location,
        ]);

        $trip->load('driver.user');

        TripLocationUpdated::dispatch($trip, $request->user());

        return $trip;
    }
}

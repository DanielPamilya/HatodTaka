<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request){
        //return back the user and associated driver
        $user = $request->user();
        $user->load('driver');


        return $request->user();
    }

    public function update(Request $request){
        $request->validate([
            'year' => 'required|numeric|between:2010, 2024',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required|alpha',
            'license_plate' => 'required',
            'name' => 'required',
        ]);

        $user = $request->user();

        $user->update($user->only('name'));

        //create or update a driver association with this  user
        $user->driver()->updateOrCreate($request->only([
            'year',
            'make',
            'model',
            'color',
            'license_plate'
        ]));
        
        $user->load('driver');

        return $user;
    }

}
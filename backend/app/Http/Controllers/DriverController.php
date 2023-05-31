<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        //return back the user and associate them with the driver-model
        $user = $request->user();
        // check if the user object has a relationship existing in the driver-model
        // if yes, then the "load()" method will try and append that record to the $user object.
        $user->load('driver');

        //$user->driver = null|associated driver object
        return $user;
    }

    public function update(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|between:2003,2024',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required|alpha',
            'license_plate' => 'required',
            'name' => 'required'
        ]);

        $user = $request->user();

        $user->update($request->only('name'));

        // create or update a driver associated with this user
        $user->driver()->updateOrCreate($request->only([
            'year',
            'make',
            'model',
            'color',
            'license_plate',
        ]));

        $user->load('driver');

        return $user;
    }
}

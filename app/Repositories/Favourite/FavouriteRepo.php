<?php

namespace App\Repositories\Favourite;

use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;


class FavouriteRepo
{
    public function getAllFavs()
{
    $user = Auth::user();

    $favoriteDoctors = $user->favoriteDoctors()->get();

    return response()->json([
        'favourites' => $favoriteDoctors
    ], 200);
}

    public function addToFav($request)
    {
        $user = Auth::user();
        
        $doctor = Doctor::findOrFail($request['doctor_id']);
       
        $fav = $user->favourites()->firstorCreate([
            'doctor_id'=>$doctor->id
        ]);

        if(! $fav->wasRecentlyCreated)
        {
            return response()->json(['message' => 'Doctor already in favourites'], 409);
        }

        return response()->json(['message' => 'Doctor added to favourites'], 201);
    }

    public function removeFromFav($request)
{
    $user = Auth::user();

    $doctorId = $request['doctor_id'];

    $favourite = $user->favourites()->where('doctor_id', $doctorId)->first();

    if (! $favourite) {
        return response()->json([
            'message' => 'Doctor not found in favourites'
        ], 404);
    }

    $favourite->delete();

    return response()->json([
        'message' => 'Doctor removed from favourites'
    ], 200);
}

}
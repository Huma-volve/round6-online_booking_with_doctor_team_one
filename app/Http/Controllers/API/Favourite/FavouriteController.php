<?php

namespace App\Http\Controllers\API\Favourite;

use App\Http\Controllers\Controller;
use App\Repositories\Favourite\FavouriteRepo;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    protected $favouriteRepo;

    public function __construct(FavouriteRepo $favouriteRepo)
    {
        $this->favouriteRepo = $favouriteRepo;
    }

    public function getAllFavs()
    {
        return $this->favouriteRepo->getAllFavs();
    }

    public function addToFav(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        return $this->favouriteRepo->addToFav($request->only('doctor_id'));
    }

    public function removeFromFav(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        return $this->favouriteRepo->removeFromFav($request->only('doctor_id'));
    }


}

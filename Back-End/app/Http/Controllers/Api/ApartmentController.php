<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\service;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index(){
        $apartments = Apartment::inRandomOrder()->take(6)->get();
        return response()->json([
            'success' => true,
            'results'  => $apartments
        ]);
    }
}

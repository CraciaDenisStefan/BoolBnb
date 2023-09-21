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
    public function search(Request $request){
        $n_rooms = $request->input('n_rooms');
        $n_beds = $request->input('n_beds');
        $services = $request->input('services');
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $range = $request->input('range');
    
        $apartmentsFilter = Apartment::query()->with('services');
    
        if ($n_rooms !== null) {
            $apartmentsFilter->where('n_rooms', '>=', $n_rooms);
        }
    
        if ($n_beds !== null) {
            $apartmentsFilter->where('n_beds', '>=', $n_beds);
        }
    
        if (!empty($services)) {
            $apartmentsFilter->whereHas('services', function ($query) use ($services) {
                $query->whereIn('name', explode(',', $services));
            });
        }
    
        if ($lat !== null && $lon !== null && $range !== null) {
            // Calcolo della distanza e ordinamento per la distanza
            $apartmentsFilter->selectRaw('*,
                (6371000 * 2 * ASIN(SQRT(POW(SIN((? - ABS(latitude)) * PI() / 180 / 2), 2) +
                COS(? * PI() / 180) * COS(ABS(latitude) * PI() / 180) * POW(SIN((? - longitude) * PI() / 180 / 2), 2)))) AS distance',
                [$lat, $lat, $lon]
            )->having('distance', '<', $range * 1000)->orderBy('distance');
        }
    
        $results = $apartmentsFilter->get();
    
        return response()->json([
            'success' => true,
            'results'  => $results
        ]);
    }
    // public function searchApartments(Request $request) {
        
    //     $apartments = Apartment::all();


    // }
    public function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}

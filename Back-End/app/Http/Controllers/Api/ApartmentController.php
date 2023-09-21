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
        $n_rooms = $request->input('n_rooms'); // Numero di stanze
        $n_beds = $request->input('n_beds');   // Numero di letti
        $services = $request->input('services'); // Array di ID dei servizi
    
        // Inizia con una query base per gli appartamenti
        $apartmentsFilter = Apartment::query()->with('services');
    
        // Applica i filtri in base ai parametri specificati
        if ($n_rooms !== null) {
            $apartmentsFilter->where('n_rooms', '>=', $n_rooms);
        }
    
        if ($n_beds !== null) {
            $apartmentsFilter->where('n_beds', '>=', $n_beds);
        }

        if (!empty($services)) {
            $apartmentsFilter->whereHas('services', function ($query) use ($services) {
                $query->whereIn('name', explode(',', $services)); // Separa i servizi in un array se sono separati da virgola
            });
        }
    
        // Esegui la query e ottieni i risultati
        $results = $apartmentsFilter->get();
    
        return response()->json([
            'success' => true,
            'results'  => $results
        ]);
    }
    public function searchApartments(Request $request) {
        $lat = $request->input('lat'); // Numero di stanze
        $lon = $request->input('lon');   // Numero di letti
        $range = $request->input('range');

        $apartments = Apartment::all();

        $apartmentsInRange = [];

        foreach ($apartments as $apartment) {

            // raggio di ricarca default 20km
            if ($this->haversineGreatCircleDistance($lat, $lon, $apartment->latitude, $apartment->longitude) < $range) {
                array_push($apartmentsInRange, $apartment);
            }
        }

        return json_encode($apartmentsInRange);
    }
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

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
}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Apartment;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $apartments = Apartment::where('user_id', Auth::id())->get();
        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.apartments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApartmentRequest $request)
    {
        $form_data = $request->all();
        $form_data['user_id'] = Auth::id();

        // Effettua una chiamata API per ottenere le coordinate
        $address = urlencode($form_data['address']);
        $apiKey = config('services.tomtom.key'); // Assicurati di avere configurato la chiave di TomTom nei tuoi servizi

        $apiUrl = "https://api.tomtom.com/search/2/geocode/%7B$address%7D.json?key={$apiKey}";

        try{
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);
    
            if($data && isset($data['results'][0]['position'])){
                $coordinates = $data['results'][0]['position'];
                $form_data['latitude'] = $coordinates['lat'];
                $form_data['longitude'] = $coordinates['lon'];
            }
        }catch(\Exception $e) {
            // Gestisci eventuali errori nella chiamata API o nella decodifica JSON
            // ...
        }
    

        $apartment = new Apartment();

        $form_data['slug'] =  $apartment->generateSlug($form_data['title']);
        $apartment->fill($form_data);

        $apartment->save();

        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        return view('admin.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        return view('admin.apartments.edit', compact('apartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApartmentRequest  $request
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        $form_data = $request->all();
        
         // Effettua una chiamata API per ottenere le coordinate
         $address = urlencode($form_data['address']);
         $apiKey = config('services.tomtom.key'); // Assicurati di avere configurato la chiave di TomTom nei tuoi servizi
 
         $apiUrl = "https://api.tomtom.com/search/2/geocode/%7B$address%7D.json?key={$apiKey}";
 
         try{
             $response = file_get_contents($apiUrl);
             $data = json_decode($response, true);
     
             if($data && isset($data['results'][0]['position'])){
                 $coordinates = $data['results'][0]['position'];
                 $form_data['latitude'] = $coordinates['lat'];
                 $form_data['longitude'] = $coordinates['lon'];
             }
         }catch(\Exception $e) {
             // Gestisci eventuali errori nella chiamata API o nella decodifica JSON
             // ...
         }

        if($request->hasFile('img')){
            if($apartment->img){
                Storage::delete($apartment->img);
            }
            
            $path = Storage::put('apartments-img', $request->img);
            $form_data['img'] = $path;
        }
        
        $form_data['slug'] =  $apartment->generateSlug($form_data['title']);
        $apartment->update($form_data);
        
        return redirect()->route('admin.apartments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {

       
        if($apartment->img){
            Storage::delete($apartment->img);
        }
            
        $apartment->delete();
        return redirect()->route('admin.apartments.index');
    }
}

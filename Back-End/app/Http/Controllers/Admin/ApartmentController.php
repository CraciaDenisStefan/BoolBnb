<?php

namespace App\Http\Controllers\Admin;
use App\Models\Service;
use App\Models\Apartment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $datas = $request->all();

        if(isset($datas['message'])){
            $message = $datas['message'];
        }else{
            $message = '';
        }

        $apartments = Apartment::where('user_id', Auth::id())->get();
        return view('admin.apartments.index', compact('apartments', 'message'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.apartments.create', compact('services'));
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

        if($request->has('service')){
            $service = $request->input('service');
            $apartment->services()->attach($service);
        }

        $message = 'Creazione appartamneto completata';
        return redirect()->route('admin.apartments.index', ['message' => $message]);
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
        $services = Service::all();
        return view('admin.apartments.edit', compact('apartment', 'services'));
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

        if($request->has('services')) {
            $services = $request->input('services');
            $apartment->services()->sync($services);
        }else{
            $apartment->services()->detach();
        }
        
        $form_data['slug'] =  $apartment->generateSlug($form_data['title']);
        $apartment->update($form_data);
        
        $message = 'Aggiornamento appartamento completato';
        return redirect()->route('admin.apartments.index', ['message' => $message]);
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

        $apartment->services()->detach();
            
        $apartment->delete();
        $message = 'Cancellazione appartamento completata';
        return redirect()->route('admin.apartments.index', ['message' => $message]);
    }
}

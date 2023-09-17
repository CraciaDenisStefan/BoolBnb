@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12  my-5">
                <div class="d-md-flex justify-content-md-between align-items-md-start">
                    <div >
                        <h1>{{ $apartment->title }}</h1>
                        <h5>{{$apartment->address}}</h5>
                    </div>
                    <div class="mt-4 mt-md-0">
                        <a href="{{route('admin.apartments.index')}}" class="btn btn-primary mt-1">Torna ai tuoi appartamenti</a>
                    </div>
                    
                </div>
               
                
            </div>
            <div class="col-12 col-md-6">
                <img class="img-fluid" src="{{ asset('storage/'. $apartment->img) }}" alt="Immagine non disponibile">
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                <div class="card my-4" style="width: 18rem;">
                    <div class="card-header">
                      Informazioni:
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fa-solid fa-door-open"></i> <strong>Stanze </strong> {{$apartment->n_rooms}}</li>
                        <li class="list-group-item"><i class="fa-solid fa-bed"></i></i> <strong>Posti letti </strong> {{$apartment->n_beds}}</li>
                        <li class="list-group-item "><i class="fa-solid fa-toilet-paper"></i> <strong>Bagni </strong> {{$apartment->n_bathrooms}}</li>
                        <li class="list-group-item"><i class="fa-solid fa-sack-dollar"></i> <strong>Prezzo </strong> {{$apartment->price}} &euro;</li>
                        <li class="list-group-item"><i class="fa-solid fa-ruler-combined"></i>  {{$apartment->mq}} mq</li>
                        <li class="list-group-item">  
                            @if($apartment->services->count() > 0)
                                <p class="control-label mb-3"><i class="fa-solid fa-circle-info"></i> <strong>Servizi </strong></p>
                                <ul>
                                    @foreach($apartment->services as $service)
                                        <li>{{ $service->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Nessun servizio.</p>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <p>{{ $apartment->description }}</p>
        </div>
    </div>
@endsection
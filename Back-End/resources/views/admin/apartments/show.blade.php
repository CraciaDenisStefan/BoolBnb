@extends('layouts.admin')

@section('content')
<div class="background_index">
    <div class="container mt-4">
        @if($message != '')
        <div class="alert alert-success">
            {{ $message }}
        </div>
        @endif
        <div class="row">
            <div class="col-12  my-5">
                <div class="d-md-flex justify-content-md-between align-items-md-start">
                    <div >
                        <h1>{{ $apartment->title }}</h1>
                        <h5>{{$apartment->address}}</h5>
                    </div>
                    <div class="mt-4 mt-md-0">
                        <a href="{{route('admin.apartments.index')}}" class="btn primary-colour mt-1">Torna ai tuoi appartamenti</a>
                    </div>
                    
                </div>
               
                
            </div>
            <div class="col-12 col-md-6">
                @if($apartment->img)
                    <img class="img-fluid w-100 h-100 shadow-lg" src="{{ asset('storage/'. $apartment->img) }}">
                @else
                    <img class="img-fluid w-100 h-100 shadow-lg" src="https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png">
                @endif
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                <div class="card my-4 shadow-lg" style="width: 18rem;">
                    <div class="card-header">
                      Informazioni:
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fa-solid fa-door-open"></i> <strong>Stanze </strong> {{$apartment->n_rooms}}</li>
                        <li class="list-group-item"><i class="fa-solid fa-bed"></i></i> <strong>Posti letto </strong> {{$apartment->n_beds}}</li>
                        <li class="list-group-item "><i class="fa-solid fa-toilet-paper"></i> <strong>Bagni </strong> {{$apartment->n_bathrooms}}</li>
                        <li class="list-group-item"><i class="fa-solid fa-sack-dollar"></i> <strong>Prezzo </strong> {{$apartment->price}} &euro;</li>
                        <li class="list-group-item"><i class="fa-solid fa-ruler-combined"></i>  {{$apartment->mq}} mq</li>
                        <li class="list-group-item">
                            <div>
                                @if($apartment->visible)
                                    <i class="fa-solid fa-eye"></i> <strong>Visibile: </strong> <i class="fa-solid fa-check" style="color: #00ff00;"></i>
                                @else
                                    <i class="fa-solid fa-eye-slash"></i> <strong>Visibile: </strong> <i class="fa-solid fa-xmark" style="color: #ff0000;"></i>
                                @endif
                            </div>
                        </li>
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
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end align-items-center">
                        <a class="btn btn-sm btn-warning mx-2" href="{{route('admin.apartments.edit', $apartment->id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form class="form-delete delete-apartment-form" action="{{route('admin.apartments.destroy', $apartment->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button data-apartment-title="{{ $apartment->title }}" type="submit" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div class="card p-3 shadow-lg mb-5">
                <p>{{ $apartment->description }}</p>
            </div>
        </div>
    </div>
</div>
@include('admin.partials.modal_delete')
@endsection
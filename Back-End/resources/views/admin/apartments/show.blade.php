@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 my-4">
                <h1>{{ $apartment->title }}</h1>
            </div>
            <div class="col-12 col-md-6">
                <img class="img-fluid" src="{{ asset('storage/'. $apartment->img) }}" alt="Immagine non disponibile">
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                <p>{{ $apartment->description }}</p>
            </div>
            <hr class="my-3">
        </div>
    </div>
@endsection
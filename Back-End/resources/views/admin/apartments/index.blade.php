@extends('layouts.admin')

@section('content')
@vite(['resources/js/isSponsored.js'])

<div class="background_index">
  <div class="container">
    <div class="col-12 col-sm-6 col-md-3 py-5 w-100">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <a href="{{route('admin.dashboard')}}" class="btn primary-colour">Torna alla dashboard</a>
        </div>
        <div>
          <a href="{{route('admin.apartments.create')}}" class="btn primary-colour">Aggiungi un appartamento</a>
        </div>
        <div>
          <a href="{{route('admin.sponsorships.index')}}" class="btn primary-colour">Sponsorizza appartamento</a>
        </div>
        <div>
          <a href="{{route('admin.emails.index')}}" class="btn primary-colour">Visualizza messaggi</a>
        </div>
      </div>
    </div>
  </div>
      
  <div class="container">
    @if($message != '')
      <div class="alert alert-success">
          {{ $message }}
      </div>
    @endif
    @if($messageNoAuth != '')
      <div class="alert alert-danger">
          {{ $messageNoAuth }}
      </div>
    @endif
    @if($apartments->isEmpty())
    <div class="d-flex justify-content-center">
      <h1 class="my-5">Aggiungi un appartamento</h1>
    </div>
    @else
    <div>
      <h3>I tuoi appartamenti</h3>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th></th>
          <th>Titolo</th>
          <th class="d-none d-md-block">Descrizione</th>
          <th class="text-center">Visibilità</th>
          <th class="text-center">Sponzorizzato</th>
          <th class="text-center">Azioni</th>
        </tr>
      </thead>
      <tbody>
        @foreach($apartments as $apartment)
        <tr>
          <td>
            @if($apartment->img)
              <div class="card-img d-none d-md-block  my_card shadow-lg"
                style="background-image:url('{{ asset('storage/'.$apartment->img) }}'); background-size: cover; background-position: center;">
              </div>
            @else
              <div class="card-img my_card d-none d-md-block"
                style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center;">
              </div>
            @endif
          </td>
          <td>{{$apartment->title}}</td>
          <td class="truncate-text display-none">
            <div class="">
              {{$apartment->description}}
            </div>
          </td>
          <td>
            @if($apartment->visible)
              <p class="text-center"><i class="fa-solid fa-eye" style="color: #00ff00;"></i> </p>
            @else
              <p class="text-center"><i class="fa-solid fa-eye-slash" style="color: #ff0000;"></i></p> 
            @endif
          </td>
          <td>
            <?php
              $currentTimestamp = time(); // Ottieni il timestamp UNIX corrente
              $currentDate = strtotime('now'); // Converti il timestamp corrente in un oggetto DateTime
              date_default_timezone_set('Europe/Rome'); // Imposta il fuso orario a Roma
              
              if (count($apartment->sponsorships) > 0) {
                  $isSponsored = false;
              
                  foreach ($apartment->sponsorships as $sponsorship) {
                      $endDate = strtotime($sponsorship->pivot->end_date); // Converti la data di fine in timestamp
              
                      if ($currentTimestamp < $endDate) {
                          // L'appartamento è sponsorizzato
                          $isSponsored = true;
                          break; // Non c'è bisogno di continuare a controllare le altre sponsorizzazioni
                      }
                  }
              
                  if ($isSponsored) {
                      echo '<p class="text-center"><i class="fa-solid fa-star" style="color: #FFD700;"></i></p>';
                  } else {
                      echo '<p class="text-center">No</p>';
                  }
              } else {
                  echo '<p class="text-center">No</p>';
              }
              
            ?>
          </td>
          <td>
            <div class="d-flex justify-content-center">
              <a class="btn btn-sm btn-primary" href="{{route('admin.apartments.show', $apartment->id)}}"><i class="fa-solid fa-eye"></i></a>
              <a class="btn btn-sm btn-warning mx-2" href="{{route('admin.apartments.edit', $apartment->id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
              <a class="btn btn-sm btn-success mx-2" href="{{ route('admin.views.index', ['apartment' => $apartment->id]) }}"><i class="fa-solid fa-chart-line"></i></a>
              <form class="form-delete delete-apartment-form" action="{{route('admin.apartments.destroy', $apartment->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button data-apartment-title="{{ $apartment->title }}" type="submit" class="btn btn-sm btn-danger">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>
@include('admin.partials.modal_delete')
@endsection

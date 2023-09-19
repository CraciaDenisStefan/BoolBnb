@extends('layouts.admin')

@section('content')

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
      </div>
    </div>
  </div>
      
  <div class="container">
    @if($message != '')
      <div class="alert alert-success">
          {{ $message }}
      </div>
    @endif
    @if($apartments->isEmpty())
    <div class="d-flex justify-content-center">
      <h1>Devi aggiungere un appartamento</h1>
    </div>
    @else
    <div>
      <h3>I tuoi appartamenti</h3>
    </div>
    <div class="row ">
      @foreach($apartments as $apartment)
        <div class="col-12 col-lg-6 mb-4"> 
          <div class="card border m-2 d-flex align-items-stretch" >
            <div class="row g-0">
              <div class="col-md-4">
                @if($apartment->img)
                  <div class="card-img my_card shadow-lg"
                    style="background-image:url('{{ asset('storage/'.$apartment->img) }}'); background-size: cover; background-position: center; height: 100%;">
                  </div>
                @else
                  <div class="card-img my_card"
                    style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 100%;">
                  </div>
                @endif
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">{{$apartment->title}}</h5>
                  <p class="card-text truncate-text" >{{$apartment->description}}</p>
                  <p class="card-text">
                    <div class="d-flex ">
                      <a class="btn btn-sm btn-primary" href="{{route('admin.apartments.show', $apartment->id)}}"><i class="fa-solid fa-eye"></i></a>
                      <a class="btn btn-sm btn-warning mx-2" href="{{route('admin.apartments.edit', $apartment->id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
                      <form class="form-delete delete-apartment-form" action="{{route('admin.apartments.destroy', $apartment->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button data-apartment-title="{{ $apartment->title }}" type="submit" class="btn btn-sm btn-danger">
                          <i class="fa-solid fa-trash-can"></i>
                        </button>
                      </form>
                    </div>
                 </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endif
@include('admin.partials.modal_delete')
@endsection
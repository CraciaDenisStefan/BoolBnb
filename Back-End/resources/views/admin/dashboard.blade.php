@extends('layouts.admin')

@section('content')
    <div class="background_index">
        <div class="container card-container-AuthLoginRegister mt-5 pb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header text-center bg-custom text-uppercase font-weight-bold">Ti diamo il benvenuto su BoolBnB</div>
                        <img class="card-img-top" src="https://a0.muscache.com/im/pictures/fdb46962-10c1-45fc-a228-d0b055411448.jpg?im_w=720" alt="Immagine di benvenuto">
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="create text-center center-block">
                                <h4 class="text-button">Visualizza le tue proprietà</h4>
                                <a class="btn btn-custom primary-colour btn-large" href="{{route('admin.apartments.index')}}">Cerca
                                    <svg class="bi bi-map icon-button" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M15.817.613A.5.5 0 0 1 16 1v13a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 14.51l-4.902.98A.5.5 0 0 1 0 15V2a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0l4.902.98 4.902-.98a.5.5 0 0 1 .415.103zM10 2.41l-4-.8v11.98l4 .8V2.41zm1 11.98l4-.8V1.61l-4 .8v11.98zm-6-.8V1.61l-4 .8v11.98l4-.8z"/>
                                    </svg>
                                </a>
                                <a href="http://localhost:5173/">Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
{{-- prova --}}
    <div class="container">
        <div class="row">
            <div class="col-12 my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Inserisci un nuovo appartamneto</h2>
                    <a href=" {{ route('admin.apartments.index')}} " class="btn btn-secondary btn-sm">Tutte le proprietà</a>
                </div>
                <div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.apartments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="class-group">
                            <label class="control-label">Titolo dell'appartamento</label>
                            <input type="text" id="title" name="title" class="form-control @error('title')is-invalid @enderror" placeholder="Inserisci il nome dell'appartamento" value="{{ old('title') }}">
                            @error('title')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="class-group">
                            <label class="control-label">Numero stanze</label>
                            <input type="number" id="n_rooms" name="n_rooms" class="form-control @error('n_rooms')is-invalid @enderror" placeholder="Inserire il numero degli appartamenti" value="{{ old('n_rooms') }}">
                            @error('n_rooms')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="class-group">
                            <label class="control-label">Posti letto</label>
                            <input type="number" id="n_beds" name="n_beds" class="form-control @error('n_beds')is-invalid @enderror" placeholder="Inserisci posti letto" value="{{ old('n_beds') }}">
                            @error('n_beds')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="class-group">
                            <label class="control-label">Numero bagni</label>
                            <input type="number" id="n_bathrooms" name="n_bathrooms" class="form-control @error('n_bathrooms')is-invalid @enderror" placeholder="Inserisci il numero dei bagni" value="{{ old('n_bathrooms') }}">
                            @error('n_bathrooms')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="class-group">
                            <label class="control-label">m<sup>2</sup></label>
                            <input type="number" id="mq" name="mq" class="form-control @error('mq')is-invalid @enderror" placeholder="Inserire i mq" value="{{ old('mq') }}">
                            @error('mq')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="class-group">
                            <label class="control-label">Indirizzo</label>
                            <input type="text" id="address" name="address" class="form-control @error('address')is-invalid @enderror" placeholder="Inserisci l'indirizzo" value="{{ old('address') }}">
                            @error('address')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                                <label class="control-label my-2">Prezzo</label>
                                <input type="text" name="price" id="price" placeholder="Inserisci il prezzo" value="{{ old('price')}}" class="form-control @error('price') is-invalid @enderror">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                            <label class="form-control-label">Descrizione</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12 my-3">
                            <!-- Immagine -->
                            <label class="control-label my-3">Immagine</label>
                            <input type="file" name="img" id="img" placeholder="Inserisci un'immagine rappresentativa dell'appartamento" class="form-control @error('img') is-invalid @enderror" value="{{ old('img') }}">
                            @error('img')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex flex-wrap justify-content-between mt-4">
                            <div class="col-12 col-sm-6">
                                <p class="mt-3 ms-0">Visibile</p>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" value="1" name="visible" id="visible" checked>
                                        <label class="form-check-label" for="visible">Si</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="0" name="visible" id="visible">
                                        <label class="form-check-label" for="visible">No</label>
                                    </div>
                                </div>
                            </div>
                        <div class="class-group my-3">
                            <button type="submit" class="btn btn-primary btn-success">Aggiungi appartamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
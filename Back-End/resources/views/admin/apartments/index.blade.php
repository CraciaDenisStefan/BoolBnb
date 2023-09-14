@extends('layouts.admin')

@section('content')

<div class="container">
<div class="col-12 col-sm-6 col-md-3 my-5 w-100">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <a href="{{route('admin.apartments.create')}}" class="btn btn-primary">Aggiungi un appartamento</a>
    </div>
    <div>
      <a href="{{route('admin.dashboard')}}" class="btn btn-primary">Torna alla dashboard</a>
    </div>
  </div>
</div>


    <div class="card mb-3">
      <div class="card-header">
        <h3>I nostri B&B</h3>
      </div>
      <div class="card-body">
        <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Slug</th>
                <th scope="col">Specie</th>
                <th scope="col "><span class="">Utiliti</span></th>
              </tr>
            </thead>
            <tbody>
                @foreach($apartments as $apartment)
              <tr>
                <th scope="row">{{$apartment->id}}</th>
                <td>{{$apartment->title}}</td>
                <td>{{$apartment->slug}}</td>
                <td>{{$apartment->price}}</td>
                <td>
                  <div class="d-flex align-items-center justify-content-between my-content">
                      <a class="btn btn-sm btn-primary" href="{{route('admin.apartments.show', $apartment->id)}}"><i class="fa-solid fa-eye"></i></a>
                      <a class="btn btn-sm btn-warning" href="{{route('admin.apartments.edit', $apartment->id)}}" class="mx-3"><i class="fa-solid fa-pen-to-square"></i></a>
                      <form class="form-delete" action="{{route('admin.apartments.destroy', $apartment->id)}}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fa-solid fa-trash-can"></i>
                          </button>
                      </form>
                  </div>
                </td>
              </tr>
            </tbody>
          @endforeach
        </table>
      </div>
    </div>
</div>
@endsection
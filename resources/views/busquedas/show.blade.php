@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="titulo-categoria text-uppercase mt-5 mb-4">
            Resultado Busqueda : {{$busqueda}}
        </h2>

        <div class="row">
            @foreach ($recetas as $receta )
                    @include('ui.receta')
            @endforeach
        </div>

        <div class="col-12 mt-4 justify-content-center d-flex">

            {{$recetas->links()}}

        </div>
    </div>
    

@endsection
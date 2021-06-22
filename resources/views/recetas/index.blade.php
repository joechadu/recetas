@extends('layouts.app')

@section('botones')

@include('ui.navegacion')

@endsection

@section('content')

<h2 class="text-center mb-5">Administra tus recetas</h2>

<div class="col-md-10 mx-auto bg-white p-3">
    <table class="table">
        <thead class="bg-primary text-light">
            <tr>
                <th scole="col">Titulo</th>
                <th scole="col">Categoría</th>
                <th scole="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recetas as $receta)
        
            <tr>
                <td>{{$receta->titulo}}</td>
                <td>{{$receta->categoria->nombre}}</td>
                <td>

                    <eliminar-receta
                     receta-id={{$receta->id}}
                    ></eliminar-receta>

                    <a href="{{ route('recetas.edit', $receta->id)}}" class="btn btn-dark d-block mb-2">Editar</a>
                    <a href="{{ route('recetas.show', $receta->id)}}" class="btn btn-success d-block">Ver</a>

                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
    <div class="col-12 mt-4 justify-content-center d-flex">
        {{$recetas->links()}}
    </div>


    
</div>

    <div class="container">
        <h2 class="text-center my-5">Recetas que te gustan</h2>
        <div class="cold-md-10 mx-auto bg-white p-3">

            <ul class="list-group">
            @if (count($usuario->meGusta) >0)
    
                @foreach ($usuario->meGusta as $receta )

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <p>{{$receta->titulo}}</p>  
                      <a href="{{ route('recetas.show', $receta->id)}}" class="btn btn-outline-success text-uppercase font-weight-bold">Ver</a>
                    </li>
                    
                @endforeach

            </ul>

            @else
                <p class="text-center"> Aún no tiene recetas guardadas
                    <small>dale me gusta a las recetas y aparecerán aquí</small>
                </p>
            @endif
        </div>
    </div>
@endsection
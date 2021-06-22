@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css" integrity="sha512-CWdvnJD7uGtuypLLe5rLU3eUAkbzBR3Bm1SFPEaRfvXXI2v2H5Y0057EMTzNuGGRIznt8+128QIDQ8RqmHbAdg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('botones')

<a href="{{route('recetas.index')}}" class="btn btn-primary mr-2 text-white">Volver</a> 

@endsection

@section('content')

<h2 class="text-center mb-5">Editar Receta: {{$receta->titulo}}</h2>

<div class="row justify-content-center mt-5">   
    <div class="col-md-8">

        <form action="{{ route('recetas.update', $receta->id)}}" method="post" novalidate enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="titulo">Titulo Receta</label>
                   
                             <input type="text"
                                 name="titulo"
                                 class="form-control @error('titulo') is-invalid @enderror"
                                 id="titulo"
                                 placeholder="Titulo Receta"
                                 value="{{$receta->titulo}}"
                             >

                    @error('titulo')
                     
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                        
                    @enderror

            </div>

            <div class="form-group">
                <label for="categoria">Categoria</label>

                <select name="categoria" id="categoria" class="form-control @error('categoria') is-invalid @enderror">

                    <option value="">--Seleccione--</option>
                    @foreach($categorias as $categoria)
                    
                    <option value="{{ $categoria->id }}" 
                    {{$receta->categoria_id==$categoria->id ? 'selected' : ''}}>

                    {{$categoria->nombre}}
                    </option>

                    @endforeach
                 

                </select>
                @error('categoria')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="preparacion">Preparación</label>

                <input type="hidden" id="preparacion" name="preparacion" value="{{$receta->preparacion}}">

                <trix-editor  input="preparacion" class="form-control @error('preparacion') is-invalid @enderror"></trix-editor>

                @error('preparacion')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror

            </div>

            <div class="form-group mt-3">
                <label for="ingredientes">Ingredientes</label>

                <input type="hidden" id="ingredientes" name="ingredientes" value="{{$receta->ingredientes}}">

                <trix-editor  input="ingredientes" class="form-control @error('ingredientes') is-invalid @enderror"></trix-editor>

                @error('ingredientes')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror

            </div>

            <div class="form-group mt-3">
                <label for="imagen">Elige la Imagen</label>

                <input type="file" 
                        id="imagen"
                        class="form-control @error('imagen') is-invalid @enderror"
                        name="imagen">

            </div>

            <div class="mt-4">

                <p>Imagen actual</p>
                <img src="/storage/{{$receta->imagen}}" alt="" style="width: 300px">

            </div>

            @error('imagen')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{$message}}</strong>
            </span>
            @enderror

            <div class="form-group mt-4">
                <input type="submit" class="btn btn-primary" value="Agregar Receta">

            </div>

        </form>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js" integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use App\Models\CategoriaReceta;

class CategoriasController extends Controller
{
    //
    public function show(CategoriaReceta $CategoriaReceta)
    {
        $recetas = Receta::where('categoria_id', $CategoriaReceta->id)->paginate(3);

        return view('categorias.show', compact('recetas', 'CategoriaReceta' ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class RecetaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' =>['show', 'search']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $usuario=auth()->user();
       // $recetas=Auth::user()->recetas;
       $recetas=Receta::where('user_id', $usuario->id)->paginate(2);

        return view('recetas.index')
            ->with('recetas', $recetas)
            ->with('usuario', $usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$categorias= DB::table('categoria_recetas')->get()->pluck('nombre','id');

        $categorias= CategoriaReceta::all(['id','nombre']);

        return view('recetas.create')->with('categorias', $categorias);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
        $data = request()->validate([
            'titulo' => 'required|min:5',
            'categoria'=>'required',
            'preparacion'=>'required',
            'ingredientes'=>'required',
            'imagen'=>'required|image',
        ]);

        $ruta_imagen= $request['imagen']->store('upload-recetas', 'public');
        //resize de la imagen
        $img= Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
        $img->save();

        // DB::table('recetas')->insert([
        //     'titulo' => $data['titulo'],
        //     'ingredientes' => $data['ingredientes'],
        //     'preparacion' => $data['preparacion'],
        //     'imagen'=>$ruta_imagen,
        //     'user_id' =>Auth::user()->id,
        //     'categoria_id'=>$data['categoria']
        // ]);

        //almacenar en la Bd con modelo
        Auth::user()->recetas()->create([
            'titulo' => $data['titulo'],
            'ingredientes' => $data['ingredientes'],
            'preparacion' => $data['preparacion'],
            'imagen'=>$ruta_imagen,
            'categoria_id'=>$data['categoria']
        ]);


        //Redireccionar
        return redirect()->action([RecetaController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        //obtener si el usuario actual le gusta la receta y esta autenticado
        $like = ( auth()->user() ) ? auth()->user()->meGusta->contains($receta->id) : false;

        //pasa la cantidad d elikes a la vista 
        $likes= $receta->likes->count();

        return view('recetas.show', compact('receta','like','likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        $this->authorize('view', $receta);
        $categorias= CategoriaReceta::all(['id','nombre']);
        return view('recetas.edit', compact('categorias', 'receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {

        //Revisar el policy
        $this->authorize('update', $receta);

        $data = request()->validate([
            'titulo' => 'required|min:5',
            'categoria'=>'required',
            'preparacion'=>'required',
            'ingredientes'=>'required',
            //'imagen'=>'required|image'
        ]);

        //actualizar

        $receta->titulo = $data['titulo'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->categoria_id = $data['categoria'];
        
        //si el usuario sube una nueva imagen

        if(request('imagen')){
            $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');
            $img= Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
            $img->save();

            //asignar al objeto
            $receta->imagen=$ruta_imagen;
        }

        $receta->save();

        //redireccionar
        return redirect()->action([RecetaController::class, 'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {

        $this->authorize('delete', $receta);
        $receta->delete();

        return redirect()->action([RecetaController::class, 'index']);

    }

    public function search(Request $request)
    {
        $busqueda=$request->get('buscar');

        $recetas =Receta::where('titulo', 'like', '%' . $busqueda . '%')->paginate(10);
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show', compact('recetas', 'busqueda')); ;
    }
}

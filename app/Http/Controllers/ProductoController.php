<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        if ($buscar==''){
            $productos = Producto::where('empresa_id', '=', Auth::user()->empresa_id)
            ->orderBy('id', 'desc')->paginate(10);
        }
        else{
            
            $productos = Producto::where('empresa_id', '=', Auth::user()->empresa_id)
            ->where('nombre', 'like', '%'. $buscar . '%')
            ->orderBy('id', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    }
 
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $filepath = "";
        // Si trae imagen
      if($request->image <> ""){
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
            $nombre = uniqid();
            $filepath = "img/". $nombre .".".$request->extension;
            file_put_contents($filepath,$data);
            
        }

        $producto = new Producto();
        $producto->imagen = $filepath;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->stock = $request->stock;
        $producto->precio = $request->precio;
        $producto->empresa_id = $request->empresa_id;
        $producto->save();
    }

    public function update(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $filepath = ""; 

        $producto = Producto::where('id', $request->id)->firstOrFail();
            //consulta si existe una imagen asociada en el servidor ...
        if(file_exists($producto->imagen)){  
            // Consulta si trae una nueva imagen de reemplazo ...
            if($request->image <> "" && $request->image <> $producto->imagen){ 
                unlink($producto->imagen);
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
                $nombre = uniqid();
                $filepath = "img/". $nombre .".".$request->extension;
                file_put_contents($filepath,$data);
            }
        }else{ // Si no tenía imagen anteriormente ...
            if($request->image <> ""){
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
                $nombre = uniqid();
                $filepath = "img/". $nombre .".".$request->extension;
                file_put_contents($filepath,$data);
            }
        }
 
        $producto = Producto::where('id', $request->id)->firstOrFail();

        if($filepath <> ""){
            $producto->imagen = $filepath;
        } 
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->stock = $request->stock;
        $producto->precio = $request->precio;
        $producto->empresa_id = $request->empresa_id;
        $producto->save();
    }
 
    public function eliminar(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $producto = Producto::findOrFail($request->id);
        $producto->delete();
    }
 
}

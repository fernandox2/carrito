<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{

    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        if ($buscar==''){
            $empresas = Empresa::orderBy('id', 'desc')->paginate(6);
        }
        else{
            $empresas = Empresa::where('rut', 'like', '%'. $buscar . '%')
            ->orWhere('razon_social', 'like', '%'. $buscar . '%')
            ->orWhere('rubro', 'like', '%'. $buscar . '%')
            ->orderBy('id', 'desc')->paginate(6);
            
        }
        

        return [
            'pagination' => [
                'total'        => $empresas->total(),
                'current_page' => $empresas->currentPage(),
                'per_page'     => $empresas->perPage(),
                'last_page'    => $empresas->lastPage(),
                'from'         => $empresas->firstItem(),
                'to'           => $empresas->lastItem(),
            ],
            'empresas' => $empresas
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

        $empresa = new Empresa();
        $empresa->imagen_corporativa = $filepath;
        $empresa->rut = $request->rut;
        $empresa->razon_social = $request->razon_social;
        $empresa->rubro = $request->rubro;
        $empresa->representante_legal = $request->representante_legal;
        $empresa->correo = $request->correo;
        $empresa->ciudad = $request->ciudad;
        $empresa->save();
    }

    public function storeFirma(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $empresa = Empresa::findOrFail(auth()->user()->empresa_id);
        $rut_limpio = explode('-',$empresa->rut)[0];
        $filepath = "firmas/". $rut_limpio . ".p12";

        if(file_exists($filepath)){
            unlink($filepath);
        }
      
        if(move_uploaded_file($_FILES['firma']['tmp_name'], $filepath)){
            $empresa->ruta_firma_electronica = $filepath;
            $empresa->clave_firma = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            $empresa->save();
            echo true;
        }else{
            echo false;
        }

    }

    public function firmaOK(){

        $empresa = Empresa::findOrFail(auth()->user()->empresa_id);

        if($empresa->ruta_firma_electronica <> ""){
            echo true;
        }else{
            echo false;
        }
    }
 
    public function update(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $filepath = ""; 

        $empresa = Empresa::findOrFail($request->id);
            //consulta si existe una imagen asociada en el servidor ...
        if(file_exists($empresa->imagen_corporativa)){  
            // Consulta si trae una nueva imagen de reemplazo ...
            if($request->image <> "" && $request->image <> $empresa->imagen_corporativa){ 
                unlink($empresa->imagen_corporativa);
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
 
        $empresa = Empresa::findOrFail($request->id);
        if($filepath <> ""){
            $empresa->imagen_corporativa = $filepath;
        } 
        $empresa->rut = $request->rut;
        $empresa->razon_social = $request->razon_social;
        $empresa->rubro = $request->rubro;
        $empresa->representante_legal = $request->representante_legal;
        $empresa->correo = $request->correo;
        $empresa->ciudad = $request->ciudad;
        //$empresa->condicion = '1';
        $empresa->save();
    }
 
    public function eliminar(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $empresa = Empresa::findOrFail($request->id);
        $empresa->delete();
    }
 

    public function buscarempresalogin()
    {
        echo Auth::user()->empresa_id;
    }
}
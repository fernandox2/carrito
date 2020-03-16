<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;
date_default_timezone_set('America/Santiago');
class CompraController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        if ($buscar==''){
            $compras = Compra::where('empresa_id', '=', Auth::user()->empresa_id)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('monto as monto'), DB::raw('compra as detalle'), DB::raw('id as id'))
            ->orderBy('date', 'desc')->paginate(10);
        }
        else{
            
            $compras = Compra::where('empresa_id', '=', Auth::user()->empresa_id)
            ->where('compra', 'like', '%'. $buscar . '%')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('monto as monto'), DB::raw('compra as detalle'), DB::raw('id as id'))
            ->orderBy('date', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $compras->total(),
                'current_page' => $compras->currentPage(),
                'per_page'     => $compras->perPage(),
                'last_page'    => $compras->lastPage(),
                'from'         => $compras->firstItem(),
                'to'           => $compras->lastItem(),
            ],
            'compras' => $compras
        ];
    }
 
    public function store(Request $request)
    {

        $compra = new Compra();

        $compra->compra = $request->compra;
        $compra->monto = $request->monto;
        $compra->empresa_id = $request->empresa_id;
        $compra->save();
    }

    public function update(Request $request)
    {

        $compra = Compra::where('id', $request->id)->firstOrFail();      
        $compra->compra = $request->compra;
        $compra->monto = $request->monto;
        $compra->save();

    }
 
    public function eliminar(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $compra = Compra::findOrFail($request->id);
        $compra->delete();
    }

 
}

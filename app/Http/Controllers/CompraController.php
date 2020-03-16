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
        $compra->created_at = $request->fecha;
        $compra->save();
    }

    public function update(Request $request)
    {

        $compra = Compra::where('id', $request->id)->firstOrFail();      
        $compra->compra = $request->compra;
        $compra->monto = $request->monto;
        $compra->created_at = $request->fecha;
        $compra->save();

    }
 
    public function eliminar(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $compra = Compra::findOrFail($request->id);
        $compra->delete();
    }

    public function comprasPorFecha(Request $request)
    {

        $id_empresa =  Auth::user()->empresa_id;
  
        $compras = Compra::where('empresa_id', '=', Auth::user()->empresa_id)
        ->whereBetween('compras.created_at', [$request->inicio." 00:00:00", $request->fin." 23:59:59"])
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('monto as monto'), DB::raw('compra as detalle'), DB::raw('id as id'))
        ->orderBy('date', 'desc')->paginate(10);

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


    public function comprasPorFecha2(Request $request)
    {

        $id_empresa =  Auth::user()->empresa_id;
        
        $compras = DB::table('compras')
        ->where('compras.empresa_id', $id_empresa)
        ->where('DATE(compras.created_at) as fecha', $request->fecha)
        ->select(DB::raw('count(*) as n_compras'),  DB::raw('SUM(compras.monto) as monto'))
        ->groupBy('date')
        ->get();

        echo $compras;

    }

 
}

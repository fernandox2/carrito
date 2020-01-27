<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Detalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{

    public function store(Request $request)
    {
        try{

            $boleta = DB::table('ventas')
                ->where('empresa_id', $request->empresa_id)
                ->max('boleta');

            $boleta = intval($boleta) + 1;
            
            $venta = new Venta();
            $venta->boleta = $boleta;
            $venta->usuario_id = $request->usuario_id;
            $venta->monto = $request->monto;
            $venta->empresa_id = $request->empresa_id;
            $venta->save();

            $id_venta = Venta::max('id');


            foreach ($request->arraySeleccion as $producto) {
                
                $detalle = new Detalle();
                $detalle->venta_id = $id_venta;
                $detalle->producto_id = $producto['id'];
                $detalle->cantidad = $producto['cantidad'];
                $detalle->total = $producto['total'];
                $detalle->save();

            }

            echo true;
        }
        catch (\Exception $e) {
            echo false;
        }

    }


    public function ventasdeldia()
    {
        $hoy = date("Y-m-d");

        $id_empresa =  Auth::user()->empresa_id;

        $ventas = DB::table('ventas')
        ->where('empresa_id',$id_empresa)
        ->whereBetween('created_at', [$hoy." 00:00:00", $hoy." 23:59:59"])
        ->count();

        echo $ventas;

    }

    public function recaudaciondeldia()
    {
        $hoy = date("Y-m-d");

        $id_empresa =  Auth::user()->empresa_id;

        $total = DB::table('ventas')
        ->where('empresa_id',$id_empresa)
        ->whereBetween('created_at', [$hoy." 00:00:00", $hoy." 23:59:59"])
        ->sum('monto');

        echo $total;
    }

    public function vendedordelmes()
    {
        $mes = date("m");
        if ($mes == "02")
        {
            $fin = date("Y-m-"."28");
        }else{
            $fin = date("Y-m-"."30");
        }
        $inicio = date("Y-m-"."01");

        $id_empresa =  Auth::user()->empresa_id;

        $total = DB::table('ventas')
        ->join('users', 'users.id', '=', 'ventas.usuario_id')
        ->where('ventas.empresa_id', $id_empresa)
        ->whereBetween('ventas.created_at', [$inicio." 00:00:00", $fin." 23:59:59"])
        ->select(DB::raw('count(ventas.id) as ventas_realizadas, users.name as vendedor'))
        ->groupBy('vendedor')
        ->orderBy('ventas_realizadas', 'desc')
        ->get();

        echo $total;
    }
}

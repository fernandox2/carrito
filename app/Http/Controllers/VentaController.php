<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Detalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}

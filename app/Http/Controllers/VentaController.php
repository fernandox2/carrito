<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Detalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function ultimaboleta()
    {
        $venta = Venta::max('boleta');
        echo (intval($venta) + 1);
    }

    public function store(Request $request)
    {
        try{
            
            $venta = new Venta();
            $venta->boleta = $request->boleta;
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

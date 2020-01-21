<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function ultimaboleta()
    {
        $venta = Venta::max('boleta');
        echo $venta;
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
            echo true;
        }
        catch (\Exception $e) {
            echo false;
        }

    }
}

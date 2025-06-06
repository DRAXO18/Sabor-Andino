<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        // Validaciones
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
            'correo' => 'required|email|max:150',
            'mensaje' => 'required|string|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Errores de validación',
                'errores' => $validator->errors()
            ], 422);
        }

        // Guardar mensaje
        $contacto = Contacto::create($request->only('nombre', 'correo', 'mensaje'));

        return response()->json([
            'mensaje' => 'Mensaje enviado correctamente',
            'data' => $contacto
        ], 201);
    }
}

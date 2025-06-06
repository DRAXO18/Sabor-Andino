<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    // Middleware para proteger ciertas rutas
    public function __construct()
    {
        $this->middleware('auth')->except(['index']); // Solo index es público
    }

    // Obtener todos los platos - público
    public function index()
    {
        $menu = Menu::all();
        return response()->json($menu, 200);
    }

    // Crear un nuevo plato - solo usuarios autenticados
    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'categoria' => 'required|in:entrada,plato fuerte,postre,bebida',
            'imagen' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos inválidos',
                'detalles' => $validator->errors()
            ], 422);
        }

        $menu = Menu::create($validator->validated());

        return response()->json([
            'mensaje' => 'Plato creado exitosamente',
            'data' => $menu
        ], 201);
    }

    // Editar un plato - solo usuarios autenticados
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Plato no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'precio' => 'sometimes|required|numeric',
            'categoria' => 'sometimes|required|in:entrada,plato fuerte,postre,bebida',
            'imagen' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos inválidos',
                'detalles' => $validator->errors()
            ], 422);
        }

        $menu->update($validator->validated());

        return response()->json([
            'mensaje' => 'Plato actualizado exitosamente',
            'data' => $menu
        ], 200);
    }

    // Eliminar un plato - solo usuarios autenticados
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Plato no encontrado'], 404);
        }

        $menu->delete();

        return response()->json(['mensaje' => 'Plato eliminado exitosamente'], 200);
    }
}

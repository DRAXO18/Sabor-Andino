<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReservaController extends Controller
{
    // Middleware para proteger rutas, solo usuarios logueados
    public function __construct()
    {
        $this->middleware('auth')->except(['index']); // Ajusta si quieres otras rutas públicas
    }

    // Registrar una reserva (POST /reservas)
    public function store(Request $request)
    {
        $user = Auth::user(); // Recupera al usuario logueado

        // Validar los datos de la reserva
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'numero_personas' => 'required|integer|min:1',
            'mensaje_adicional' => 'nullable|string',
        ]);

        // Crear la reserva
        $reserva = Reserva::create([
            'user_id' => $user->id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'numero_personas' => $request->numero_personas,
            'mensaje_adicional' => $request->mensaje_adicional,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'reserva' => $reserva,
            'usuario' => [
                'nombre' => $user->name,
                'correo' => $user->email,
            ],
        ], 201);
    }

    // Ver todas las reservas (GET /reservas) - solo admins
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $reservas = Reserva::with('user:id,name,email')->get(); // Trae datos de usuario

        return response()->json($reservas);
    }

    // Confirmar o modificar reserva (PUT /reservas/{id}) - solo admins
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $reserva = Reserva::findOrFail($id);

        $request->validate([
            'fecha' => 'sometimes|required|date|after_or_equal:today',
            'hora' => 'sometimes|required',
            'numero_personas' => 'sometimes|required|integer|min:1',
            'mensaje_adicional' => 'nullable|string',
            'estado' => ['sometimes', 'required', Rule::in(['pendiente', 'confirmada', 'cancelada'])],
        ]);

        $reserva->update($request->all());

        return response()->json([
            'message' => 'Reserva actualizada',
            'reserva' => $reserva
        ]);
    }

    // Cancelar reserva (DELETE /reservas/{id}) - solo admins
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $reserva = Reserva::findOrFail($id);

        $reserva->delete();

        return response()->json(['message' => 'Reserva cancelada']);
    }
}

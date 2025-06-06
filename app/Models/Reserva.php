<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'user_id',
        'fecha',
        'hora',
        'numero_personas',
        'mensaje_adicional',
        'estado',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

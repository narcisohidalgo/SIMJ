<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'titulo',
        'descripcion',
        'inicio',
        'fin'
    ];
    protected $casts = [
        'inicio' => 'datetime',
        'fin' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
   public function getMinutosAttribute()
{
    if (!$this->inicio || !$this->fin) return 0;
    return Carbon::parse($this->inicio)->diffInMinutes(Carbon::parse($this->fin));
}
}

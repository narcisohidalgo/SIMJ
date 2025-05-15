<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Proyecto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function usuario()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function creador()
{
    return $this->belongsTo(User::class, 'user_id');
}
}

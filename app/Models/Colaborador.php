<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $table = "colaboradores";

    protected $fillable = [
        'nome',
        'email',
        'cargo',
        'data_admissao',
        'empresa_id',
    ];


    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

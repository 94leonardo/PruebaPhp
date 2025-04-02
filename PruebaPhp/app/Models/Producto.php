<?php

//modulo de la tabla
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //

    protected $table = 'producto';

    protected $fillable = [
        'documento',
        'nombreproducto',
        'Referencia',
        'precio',
        'stock',
        'fechacreacion'

    ];
}

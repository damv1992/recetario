<?php

namespace App\Models;

use CodeIgniter\Model;

class GenerosProductoModel extends Model
{
    protected $table = 'generosproducto';
    protected $primarykey = ['Genero', 'Producto'];
    protected $allowedFields = ['Genero', 'Producto'];
}
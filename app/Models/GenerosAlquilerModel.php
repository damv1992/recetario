<?php

namespace App\Models;

use CodeIgniter\Model;

class GenerosAlquilerModel extends Model
{
    protected $table = 'generosalquiler';
    protected $primarykey = ['Genero', 'Producto'];
    protected $allowedFields = ['Genero', 'Producto'];
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class AlquileresModel extends Model
{
    protected $table = 'alquileres';
    protected $primarykey = 'CodigoProducto';
    protected $allowedFields = ['CodigoProducto', 'NombreProducto', 'Descripcion', 'FotoProducto', 'Precio', 'Plataforma', 'Tipo'];
}
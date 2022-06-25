<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table = 'productos';
    protected $primarykey = 'CodigoProducto';
    protected $allowedFields = ['CodigoProducto', 'NombreProducto', 'Descripcion', 'FotoProducto', 'Precio', 'Plataforma', 'Tipo'];
}
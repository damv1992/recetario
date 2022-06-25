<?php

namespace App\Models;

use CodeIgniter\Model;

class CarritoModel extends Model
{
    protected $table = 'carritos';
    protected $primarykey = 'DireccionIp';
    protected $allowedFields = ['DireccionIp', 'Producto'];
}
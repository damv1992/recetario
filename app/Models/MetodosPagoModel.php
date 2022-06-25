<?php

namespace App\Models;

use CodeIgniter\Model;

class MetodosPagoModel extends Model
{
    protected $table = 'metodospago';
    protected $primarykey = 'CodigoMetodo';
    protected $allowedFields = ['CodigoMetodo', 'ImagenMetodo'];
}
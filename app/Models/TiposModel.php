<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposModel extends Model
{
    protected $table = 'tipos';
    protected $primarykey = 'CodigoTipo';
    protected $allowedFields = ['CodigoTipo', 'NombreTipo'];
}
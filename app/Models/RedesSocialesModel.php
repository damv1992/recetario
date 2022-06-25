<?php

namespace App\Models;

use CodeIgniter\Model;

class RedesSocialesModel extends Model
{
    protected $table = 'redessociales';
    protected $primarykey = 'CodigoSocial';
    protected $allowedFields = ['CodigoSocial', 'EnlaceSocial', 'IconoSocial'];
}
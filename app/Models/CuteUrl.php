<?php

namespace App\Models;

use CodeIgniter\Model;

class CuteUrl extends Model
{
    protected $table = 'cute_url';
    protected $primaryKey = 'id';
    protected $createdField = 'dtc';
    protected $protectFields = false;
}

<?php
// ============================================ USER ACCOUNT MODEL ============================================
namespace App\Models;

use CodeIgniter\Model;

class UserAccount extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $createdField = 'dtc';
    protected $protectFields = false;
}
?>
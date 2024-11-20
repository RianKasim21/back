<?php
namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'user_id'];
}

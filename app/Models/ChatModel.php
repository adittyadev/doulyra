<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table         = 'chat';
    protected $primaryKey    = 'idchat';
    protected $allowedFields = ['pelanggan_id', 'pengirim', 'pesan', 'dibaca'];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatStatusModel extends Model
{
    protected $table            = 'riwayat_status';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'laporan_id',
        'user_id',
        'status_sebelumnya',
        'status_baru',
        'keterangan',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
}
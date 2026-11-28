<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table            = 'lokasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'laporan_id',
        'latitude',
        'longitude',
        'nama_lokasi',
        'akurasi_gps',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
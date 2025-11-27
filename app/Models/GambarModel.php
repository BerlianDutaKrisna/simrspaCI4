<?php

namespace App\Models;

use CodeIgniter\Model;

class GambarModel extends Model
{
    protected $table            = 'gambar';
    protected $primaryKey       = 'id_gambar';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    // protected $useSoftDeletes = false;

    protected $allowedFields    = [
        'id_hpa',
        'nama_file',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    // Timestamps otomatis (created_at & updated_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua gambar berdasarkan id_hpa
     */
    public function getByHpa($idHpa)
    {
        return $this->where('id_hpa', $idHpa)->findAll();
    }
}

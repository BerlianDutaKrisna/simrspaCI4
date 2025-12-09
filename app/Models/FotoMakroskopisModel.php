<?php

namespace App\Models;

use CodeIgniter\Model;

class FotoMakroskopisModel extends Model
{
    protected $table            = 'foto_makroskopis';
    protected $primaryKey       = 'id_foto_makroskopis';
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
     * Ambil semua foto_makroskopis berdasarkan id_hpa
     */
    public function getByHpa($idHpa)
    {
        return $this->where('id_hpa', $idHpa)->findAll();
    }
}

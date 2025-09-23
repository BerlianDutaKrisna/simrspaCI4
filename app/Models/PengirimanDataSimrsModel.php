<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanDataSimrsModel extends Model
{
    protected $table            = 'pengiriman_data_simrs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'idtransaksi',
        'tanggal',
        'register',
        'pemeriksaan',
        'idpasien',
        'norm',
        'nama',
        'noregister',
        'datang',
        'periksa',
        'selesai',
        'diambil',
        'iddokterpa',
        'dokterpa',
        'statuslokasi',
        'diagnosaklinik',
        'diagnosapatologi',
        'mutusediaan',
        'responsetime',
        'hasil',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPengirimanData()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }
}

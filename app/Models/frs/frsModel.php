<?php

namespace App\Models\FrsModel;

use CodeIgniter\Model;

class frsModel extends Model
{
    protected $table = 'frs';
    protected $primaryKey = 'id_frs';
    protected $allowedFields = [
        'kode_frs',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_frs',
        'makroskopis_frs',
        'foto_makroskopis_frs',
        'mikroskopis_frs',
        'foto_mikroskopis_frs',
        'jumlah_slide',
        'hasil_frs',
        'print_frs',
        'penerima_frs',
        'tanggal_penerima',
        'id_mutu',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLastKodefrs()
    {
        return $this->orderBy('id_frs', 'DESC')->first();
    }
    public function countfrsProcessed()
    {
        return $this->where('status_frs !=', 'Selesai')->countAllResults();
    }
    public function countPenerimaanfrs()
    {
        return $this->where('status_frs =', 'Terdaftar')->countAllResults();
    }
}
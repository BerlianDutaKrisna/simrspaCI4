<?php

namespace App\Models\Fnab;

use CodeIgniter\Model;

class FnabModel extends Model
{
    protected $table = 'fnab';
    protected $primaryKey = 'id_fnab';
    protected $allowedFields = [
        'kode_fnab',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_fnab',
        'makroskopis_fnab',
        'foto_makroskopis_fnab',
        'mikroskopis_fnab',
        'foto_mikroskopis_fnab',
        'jumlah_slide',
        'hasil_fnab',
        'print_fnab',
        'penerima_fnab',
        'tanggal_penerima',
        'id_mutu',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLastKodefnab()
    {
        return $this->orderBy('id_fnab', 'DESC')->first();
    }
}
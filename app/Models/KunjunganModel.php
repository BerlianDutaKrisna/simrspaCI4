<?php

namespace App\Models;

use CodeIgniter\Model;

class KunjunganModel extends Model
{
    protected $table            = 'kunjungan';
    protected $primaryKey       = 'idtransaksi';
    protected $allowedFields    = [
        'idtransaksi',
        'tanggal',
        'idpasien',
        'norm',
        'nama',
        'tgl_lhr',
        'pasien_usia',
        'beratbadan',
        'tinggibadan',
        'alamat',
        'jeniskelamin',
        'kota',
        'jenispasien',
        'iddokterperujuk',
        'dokterperujuk',
        'iddokterpa',
        'dokterpa',
        'pelayananasal',
        'idunitasal',
        'unitasal',
        'register',
        'pemeriksaan',
        'responsetime',
        'statuslokasi',
        'diagnosaklinik',
        'hasil',
        'diagnosapatologi',
        'mutusediaan'
    ];
    protected $useTimestamps = false;
}
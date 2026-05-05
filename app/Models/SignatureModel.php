<?php

namespace App\Models;

use CodeIgniter\Model;

class SignatureModel extends Model
{
    protected $table = 'signature';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_transaksi',
        'tanggal',
        'register',
        'noregister',
        'idpasien',
        'norm',
        'nama',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',

        'dokter_pelaksana',
        'petugas_pelaksana',
        'pemberi_informasi',

        'hubungan_dengan_pasien',
        'nama_hubungan_pasien',
        'tgl_lahir_hubungan_pasien',
        'alamat_hubungan_pasien',

        'diagnosis_kerja',

        'concentSignaturePasien',
        'concentSignatureDokter',
        'concentSignaturePetugas',

        'dateTimeSignature',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
<?php

namespace App\Models\Frs\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penerimaan_frs extends Model
{
    protected $table      = 'penerimaan_frs'; // Nama tabel
    protected $primaryKey = 'id_penerimaan_frs'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_frs',
        'id_user_penerimaan_frs',
        'status_penerimaan_frs',
        'mulai_penerimaan_frs',
        'selesai_penerimaan_frs',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenerimaan_frs()
    {
        return $this->select(
            '
        penerimaan_frs.*, 
        frs.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_frs,
        mutu_frs.id_mutu_frs,
        mutu_frs.total_nilai_mutu_frs'
        )
            ->join('frs', 'penerimaan_frs.id_frs = frs.id_frs', 'left') // Relasi dengan tabel frs
            ->join('patient', 'frs.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan_frs.id_user_penerimaan_frs = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan_frs
            ->join('mutu_frs', 'frs.id_frs = mutu_frs.id_frs', 'left') // Relasi dengan tabel mutu_frs berdasarkan id_frs
            ->whereIn('frs.status_frs', ['penerimaan_frs', 'Penerimaan']) // Menambahkan filter whereIn untuk status_frs
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penerimaan_frs
    public function updatepenerimaan_frs($id_penerimaan_frs, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table penerimaan_frs
        $builder->where('id_penerimaan_frs', $id_penerimaan_frs);  // Menentukan baris yang akan diupdate berdasarkan id_penerimaan_frs
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenerimaan_frs($id_penerimaan_frs)
    {
        return $this->delete($id_penerimaan_frs);
    }

}

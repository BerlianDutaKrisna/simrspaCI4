<?php

namespace App\Models\Ihc\Proses; // Update namespace sesuai dengan folder

use CodeIgniter\Model;

class Penerimaan_ihc extends Model
{
    protected $table      = 'penerimaan_ihc'; // Nama tabel
    protected $primaryKey = 'id_penerimaan_ihc'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_ihc',
        'id_user_penerimaan_ihc',
        'status_penerimaan_ihc',
        'mulai_penerimaan_ihc',
        'selesai_penerimaan_ihc',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenerimaan_ihc()
    {
        return $this->select(
            '
        penerimaan_ihc.*, 
        ihc.*, 
        patient.*, 
        users.nama_user AS nama_user_penerimaan_ihc,
        mutu_ihc.id_mutu_ihc,
        mutu_ihc.total_nilai_mutu_ihc'
        )
            ->join('ihc', 'penerimaan_ihc.id_ihc = ihc.id_ihc', 'left') // Relasi dengan tabel ihc
            ->join('patient', 'ihc.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan_ihc.id_user_penerimaan_ihc = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan_ihc
            ->join('mutu_ihc', 'ihc.id_ihc = mutu_ihc.id_ihc', 'left') // Relasi dengan tabel mutu_ihc berdasarkan id_ihc
            ->whereIn('ihc.status_ihc', ['penerimaan_ihc', 'Penerimaan']) // Menambahkan filter whereIn untuk status_ihc
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penerimaan_ihc
    public function updatepenerimaan_ihc($id_penerimaan_ihc, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table penerimaan_ihc
        $builder->where('id_penerimaan_ihc', $id_penerimaan_ihc);  // Menentukan baris yang akan diupdate berdasarkan id_penerimaan_ihc
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletepenerimaan_ihc($id_penerimaan_ihc)
    {
        return $this->delete($id_penerimaan_ihc);
    }

}

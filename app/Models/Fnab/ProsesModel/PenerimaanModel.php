<?php

namespace App\Models\Fnab\ProsesModel;
use CodeIgniter\Model;

class PenerimaanModel extends Model
{
    protected $table      = 'penerimaan_fnab'; // Nama tabel
    protected $primaryKey = 'id_penerimaan'; // Nama primary key
    protected $returnType = 'array';

    // Kolom-kolom yang dapat diisi melalui mass-assignment
    protected $allowedFields = [
        'id_fnab',
        'id_user_penerimaan',
        'status_penerimaan',
        'mulai_penerimaan',
        'selesai_penerimaan',
        'created_at',
        'updated_at'
    ];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk insert data Penerimaan
    public function insertPenerimaan(array $data): bool
    {
        $this->insertPenerimaan($data);
        return $this->db->affectedRows() > 0;
    }

    public function getPenerimaanWithRelations()
    {
        return $this->select(
            '
        penerimaan_fnab.*, 
        fnab.*, 
        patient.*,
        mutu_fnab.*,
        users.nama_user AS nama_user_penerimaan,
        mutu_fnab.total_nilai_mutu'
        )
            ->join('fnab', 'penerimaan_fnab.id_fnab = fnab.id_fnab', 'left') // Relasi dengan tabel fnab
            ->join('patient', 'fnab.id_pasien = patient.id_pasien', 'left') // Relasi dengan tabel patient
            ->join('users', 'penerimaan_fnab.id_user_penerimaan = users.id_user', 'left') // Relasi dengan tabel users untuk penerimaan
            ->join('mutu_fnab', 'fnab.id_fnab = mutu_fnab.id_fnab', 'left') // Relasi dengan tabel mutu berdasarkan id_fnab
            ->whereIn('fnab.status_fnab', ['Penerimaan', 'Terdaftar']) // Menambahkan filter whereIn untuk status_fnab
            ->orderBy('fnab.kode_fnab', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mengupdate data penerimaan
    public function updatePenerimaan($id_penerimaan, $data)
    {
        $builder = $this->db->table($this->table);  // Mengambil table penerimaan
        $builder->where('id_penerimaan', $id_penerimaan);  // Menentukan baris yang akan diupdate berdasarkan id_penerimaan
        $builder->update($data);  // Melakukan update dengan data yang dikirimkan
        return $this->db->affectedRows();  // Mengembalikan jumlah baris yang terpengaruh
    }

    public function deletePenerimaan($id_penerimaan)
    {
        return $this->delete($id_penerimaan);
    }

    public function countPenerimaan()
    {
        return $this->where('status_penerimaan !=', 'Selesai Pemeriksaan')->countAllResults();
    }
}

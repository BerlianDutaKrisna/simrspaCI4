<?php
namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patient'; // Nama tabel di database
    protected $primaryKey = 'id_pasien';
    protected $returnType = 'array';
    protected $allowedFields = [
        'norm_pasien',
        'nama_pasien',
        'alamat_pasien',
        'tanggal_lahir_pasien',
        'jenis_kelamin_pasien',
        'status_pasien'
    ];
    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;

    // Nama kolom untuk waktu dibuat dan diperbarui
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // Menyimpan data patient baru
    public function insertPatient($data)
    {
        // Pastikan data disimpan dengan benar
        try {
            // Proses penyimpanan data menggunakan insert
            $this->insert($data);

            // Cek apakah ada error saat penyimpanan
            if ($this->db->affectedRows() > 0) {
                return true; // Berhasil menyimpan data
            } else {
                return false; // Gagal menyimpan data
            }
        } catch (\Exception $e) {
            // Menangani error dan mengembalikan pesan error
            return $e->getMessage();
        }
    }
    // Mengecek apakah NORM sudah ada di database
    public function checkNormExists($norm_pasien)
{
    return $this->where('norm_pasien', $norm_pasien)->countAllResults() > 0;
}
    public function deletePatient($id_patient)
    {
        try {
            // Menghapus data berdasarkan ID
            return $this->db->table($this->table)->delete(['id_pasien' => $id_patient]);
        } catch (\Throwable $e) {
            // Menangkap error jika terjadi
            log_message('error', 'Error saat menghapus data pasien: ' . $e->getMessage());
            return false;
        }
    }
    // Mencari pasien berdasarkan norm_pasien
public function searchByNorm($norm_pasien)
{
    return $this->where('norm_pasien', $norm_pasien)->first();  // Mengambil satu data pasien yang cocok
}


}

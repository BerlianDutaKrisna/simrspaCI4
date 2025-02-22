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
        return $this->orderBy('id_fnab', 'DESC')->first(); // Ambil data terakhir berdasarkan ID
    }
    // Fungsi untuk insert data HPA dengan pengecekan kode_hpa
    public function insertHpa(array $data): bool
    {
        // Cek jika kode_hpa sudah ada
        if ($this->where('kode_hpa', $data['kode_hpa'])->first()) {
            return false;  // Jika sudah ada, tidak melakukan insert
        }

        // Melakukan insert data
        return $this->insertHpa($data) > 0;
    }
    public function getHpaWithPatient($id_hpa)
    {
        return $this->select('hpa.*, patient.*')
            ->join('patient', 'patient.id_pasien = hpa.id_pasien')
            ->where('hpa.id_hpa', $id_hpa)
            ->first();
    }
    public function getHpaWithAllPatient()
    {
        return $this->db->table($this->table)
            ->select(' 
            hpa.*, 
            patient.*
        ')
            ->join('patient', 'patient.id_pasien = hpa.id_pasien', 'left')
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function countHpaProcessed()
    {
        return $this->where('status_hpa !=', 'Selesai')->countAllResults();
    }
    public function countPenerimaan()
    {
        return $this->where('status_hpa =', 'Terdaftar')->countAllResults();
    }
    public function countPengirisan()
    {
        return $this->where('status_hpa =', 'Pengirisan')->countAllResults();
    }
    public function countPemotongan()
    {
        return $this->where('status_hpa =', 'Pemotongan')->countAllResults();
    }
    public function countPemprosesan()
    {
        return $this->where('status_hpa =', 'Pemprosesan')->countAllResults();
    }
    public function countPenanaman()
    {
        return $this->where('status_hpa =', 'Penanaman')->countAllResults();
    }
    public function countPemotonganTipis()
    {
        return $this->where('status_hpa =', 'Pemotongan Tipis')->countAllResults();
    }
    public function countPewarnaan()
    {
        return $this->where('status_hpa =', 'Pewarnaan')->countAllResults();
    }
    public function countPembacaan()
    {
        return $this->where('status_hpa =', 'Pembacaan')->countAllResults();
    }
    public function countPenulisan()
    {
        return $this->where('status_hpa =', 'Penulisan')->countAllResults();
    }
    public function countPemverifikasi()
    {
        return $this->where('status_hpa =', 'Pemverifikasi')->countAllResults();
    }
    public function countAutorized()
    {
        return $this->where('status_hpa =', 'Autorized')->countAllResults();
    }
    public function countPencetakan()
    {
        return $this->where('status_hpa =', 'Pencetakan')->countAllResults();
    }

    public function updateHpa($id_hpa, $data)
    {
        $builder = $this->db->table('hpa');  // Mengambil table 'hpa'
        $builder->where('id_hpa', $id_hpa); // Menambahkan kondisi WHERE
        $builder->update($data);             // Melakukan update data
        return $this->db->affectedRows();    // Mengembalikan jumlah baris yang terpengaruh
    }

    // Fungsi untuk memperbarui penerima_hpa
    public function updatePenerima($id_hpa, $data)
    {
        // Validasi parameter
        if (
            empty($id_hpa) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID HPA atau data tidak valid.');
        }
        // Mengambil table 'hpa'
        $builder = $this->db->table('hpa');
        // Menambahkan kondisi WHERE
        $builder->where('id_hpa', $id_hpa);
        // Melakukan update data
        $updateResult = $builder->update($data);
        // Mengecek apakah update berhasil
        if ($updateResult) {
            return $this->db->affectedRows(); // Mengembalikan jumlah baris yang terpengaruh
        } else {
            throw new \RuntimeException('Update data gagal.'); // Menangani error
        }
    }

    public function updateStatusHpa($id_hpa, $data)
    {
        // Validasi parameter
        if (
            empty($id_hpa) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID HPA atau data tidak valid.');
        }
        // Mengambil table 'hpa'
        $builder = $this->db->table('hpa');
        // Menambahkan kondisi WHERE
        $builder->where('id_hpa', $id_hpa);
        // Melakukan update data
        $updateResult = $builder->update($data);
        // Mengecek apakah update berhasil
        if ($updateResult) {
            return $this->db->affectedRows(); // Mengembalikan jumlah baris yang terpengaruh
        } else {
            throw new \RuntimeException('Update data gagal.'); // Menangani error
        }
    }

    public function getHpaWithRelations()
    {
        return $this->db->table($this->table)
            ->select('
            fnab.*, 
            patient.nama_pasien, 
            patient.norm_pasien, 
            penerimaan.*, 
            pengirisan.*, 
            pemotongan.*, 
            pemprosesan.*, 
            penanaman.*, 
            pemotongan_tipis.*, 
            pewarnaan.*, 
            pembacaan.*, 
            penulisan.*, 
            pemverifikasi.*, 
            pencetakan.*, 
            user_penerimaan.nama_user AS nama_user_penerimaan, 
            user_pengirisan.nama_user AS nama_user_pengirisan, 
            user_pemotongan.nama_user AS nama_user_pemotongan, 
            user_pemprosesan.nama_user AS nama_user_pemprosesan, 
            user_penanaman.nama_user AS nama_user_penanaman, 
            user_pemotongan_tipis.nama_user AS nama_user_pemotongan_tipis, 
            user_pewarnaan.nama_user AS nama_user_pewarnaan, 
            user_pembacaan.nama_user AS nama_user_pembacaan, 
            user_penulisan.nama_user AS nama_user_penulisan, 
            user_pemverifikasi.nama_user AS nama_user_pemverifikasi, 
            user_pencetakan.nama_user AS nama_user_pencetakan
        ')
            ->join('patient', 'patient.id_pasien = fnab.id_pasien', 'left')
            ->join('penerimaan', 'fnab.id_penerimaan = penerimaan.id_penerimaan', 'left')
            ->join('pengirisan', 'fnab.id_pengirisan = pengirisan.id_pengirisan', 'left')
            ->join('pemotongan', 'fnab.id_pemotongan = pemotongan.id_pemotongan', 'left')
            ->join('pemprosesan', 'fnab.id_pemprosesan = pemprosesan.id_pemprosesan', 'left')
            ->join('penanaman', 'fnab.id_penanaman = penanaman.id_penanaman', 'left')
            ->join('pemotongan_tipis', 'fnab.id_pemotongan_tipis = pemotongan_tipis.id_pemotongan_tipis', 'left')
            ->join('pewarnaan', 'fnab.id_pewarnaan = pewarnaan.id_pewarnaan', 'left')
            ->join('pembacaan', 'fnab.id_pembacaan = pembacaan.id_pembacaan', 'left')
            ->join('penulisan', 'fnab.id_penulisan = penulisan.id_penulisan', 'left')
            ->join('pemverifikasi', 'fnab.id_pemverifikasi = pemverifikasi.id_pemverifikasi', 'left')
            ->join('pencetakan', 'fnab.id_pencetakan = pencetakan.id_pencetakan', 'left')
            ->join('users AS user_penerimaan', 'penerimaan.id_user_penerimaan = user_penerimaan.id_user', 'left')
            ->join('users AS user_pengirisan', 'pengirisan.id_user_pengirisan = user_pengirisan.id_user', 'left')
            ->join('users AS user_pemotongan', 'pemotongan.id_user_pemotongan = user_pemotongan.id_user', 'left')
            ->join('users AS user_pemprosesan', 'pemprosesan.id_user_pemprosesan = user_pemprosesan.id_user', 'left')
            ->join('users AS user_penanaman', 'penanaman.id_user_penanaman = user_penanaman.id_user', 'left')
            ->join(
                'users AS user_pemotongan_tipis',
                'pemotongan_tipis.id_user_pemotongan_tipis = user_pemotongan_tipis.id_user',
                'left'
            )
            ->join('users AS user_pewarnaan', 'pewarnaan.id_user_pewarnaan = user_pewarnaan.id_user', 'left')
            ->join('users AS user_pembacaan', 'pembacaan.id_user_pembacaan = user_pembacaan.id_user', 'left')
            ->join('users AS user_penulisan', 'penulisan.id_user_penulisan = user_penulisan.id_user', 'left')
            ->join('users AS user_pemverifikasi', 'pemverifikasi.id_user_pemverifikasi = user_pemverifikasi.id_user', 'left')
            ->join('users AS user_pencetakan', 'pencetakan.id_user_pencetakan = user_pencetakan.id_user', 'left')
            ->where('fnab.status_fnab !=', 'Pemverifikasi')
            ->where('fnab.status_fnab !=', 'Autorized')
            ->where('fnab.status_fnab !=', 'Pencetakan')
            ->where('fnab.status_fnab !=', 'Selesai')
            ->orderBy('fnab.kode_fnab', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getHpaChartData()
    {
        return $this->select("DATE_FORMAT(tanggal_permintaan, '%Y') AS tahun, DATE_FORMAT(tanggal_permintaan, '%M') AS bulan, COUNT(*) AS total")
            ->where('tanggal_permintaan IS NOT NULL')
            ->groupBy("tahun, bulan")
            ->orderBy("MIN(tanggal_permintaan)", "ASC")
            ->findAll();
    }
}

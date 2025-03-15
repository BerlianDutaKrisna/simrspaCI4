<?php

namespace App\Models\Ihc;

use CodeIgniter\Model;

class IhcModel extends Model
{
    protected $table = 'ihc';
    protected $primaryKey = 'id_ihc';
    protected $allowedFields = [
        'kode_ihc',
        'kode_block_ihc',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_ihc',
        'makroskopis_ihc',
        'mikroskopis_ihc',
        'jumlah_slide',
        'hasil_ihc',
        'print_ihc',
        'penerima_ihc',
        'tanggal_penerima',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLastKodeihc()
    {
        return $this->orderBy('id_ihc', 'DESC')->first(); // Ambil data terakhir berdasarkan ID
    }

    public function countProsesihc()
    {
        return $this->where('status_ihc !=', 'Selesai')->countAllResults() ?? 0;
    }
    public function countPenerimaanihc()
    {
        return $this->where('status_ihc', 'Penerimaan')->countAllResults() ?? 0;
    }
    public function countPengirisanihc()
    {
        return $this->where('status_ihc =', 'Pengirisan')->countAllResults() ?? 0;
    }
    public function countPemotonganihc()
    {
        return $this->where('status_ihc =', 'Pemotongan')->countAllResults() ?? 0;
    }
    public function countPemprosesanihc()
    {
        return $this->where('status_ihc =', 'Pemprosesan')->countAllResults() ?? 0;
    }
    public function countPenanamanihc()
    {
        return $this->where('status_ihc =', 'Penanaman')->countAllResults() ?? 0;
    }
    public function countPemotonganTipisihc()
    {
        return $this->where('status_ihc =', 'Pemotongan Tipis')->countAllResults() ?? 0;
    }
    public function countPewarnaanihc()
    {
        return $this->where('status_ihc =', 'Pewarnaan')->countAllResults() ?? 0;
    }
    public function countPembacaanihc()
    {
        return $this->where('status_ihc =', 'Pembacaan')->countAllResults() ?? 0;
    }
    public function countPenulisanihc()
    {
        return $this->where('status_ihc =', 'Penulisan')->countAllResults() ?? 0;
    }
    public function countPemverifikasiihc()
    {
        return $this->where('status_ihc =', 'Pemverifikasi')->countAllResults() ?? 0;
    }
    public function countAuthorizedihc()
    {
        return $this->where('status_ihc =', 'Autorized')->countAllResults() ?? 0;
    }
    public function countPencetakanihc()
    {
        return $this->where('status_ihc =', 'Pencetakan')->countAllResults() ?? 0;
    }

    public function getihcChartData()
    {
        return $this->select("DATE_FORMAT(tanggal_permintaan, '%Y') AS tahun, DATE_FORMAT(tanggal_permintaan, '%M') AS bulan, COUNT(*) AS total")
            ->where('tanggal_permintaan IS NOT NULL')
            ->groupBy("tahun, bulan")
            ->orderBy("MIN(tanggal_permintaan)", "ASC")
            ->findAll();
    }

    public function insertihc(array $data): bool
    {
        if ($this->where('kode_ihc', $data['kode_ihc'])->first()) {
            return false;
        }

        return $this->insertihc($data) > 0;
    }

    public function getihcWitihctient($id_ihc)
    {
        return $this->select('ihc.*, patient.*')
            ->join('patient', 'patient.id_pasien = ihc.id_pasien')
            ->where('ihc.id_ihc', $id_ihc)
            ->first();
    }

    public function updateihc($id_ihc, $data)
    {
        $builder = $this->db->table('ihc');
        $builder->where('id_ihc', $id_ihc);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function updatePenerima($id_ihc, $data)
    {
        // Validasi parameter
        if (
            empty($id_ihc) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID ihc atau data tidak valid.');
        }
        // Mengambil table 'ihc'
        $builder = $this->db->table('ihc');
        // Menambahkan kondisi WHERE
        $builder->where('id_ihc', $id_ihc);
        // Melakukan update data
        $updateResult = $builder->update($data);
        // Mengecek apakah update berhasil
        if ($updateResult) {
            return $this->db->affectedRows();
        } else {
            throw new \RuntimeException('Update data gagal.');
        }
    }

    public function updateStatusihc($id_ihc, $data)
    {
        // Validasi parameter
        if (
            empty($id_ihc) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID ihc atau data tidak valid.');
        }
        // Mengambil table 'ihc'
        $builder = $this->db->table('ihc');
        // Menambahkan kondisi WHERE
        $builder->where('id_ihc', $id_ihc);
        // Melakukan update data
        $updateResult = $builder->update($data);
        // Mengecek apakah update berhasil
        if ($updateResult) {
            return $this->db->affectedRows(); // Mengembalikan jumlah baris yang terpengaruh
        } else {
            throw new \RuntimeException('Update data gagal.'); // Menangani error
        }
    }
}

<?php

namespace App\Models\Frs;

use CodeIgniter\Model;

class FrsModel extends Model
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
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLastKodefrs()
    {
        return $this->orderBy('id_frs', 'DESC')->first(); // Ambil data terakhir berdasarkan ID
    }

    public function countProsesfrs()
    {
        return $this->where('status_frs !=', 'Selesai')->countAllResults() ?? 0;
    }
    public function countPenerimaanfrs()
    {
        return $this->where('status_frs', 'Penerimaan')->countAllResults() ?? 0;
    }
    public function countPengirisanfrs()
    {
        return $this->where('status_frs =', 'Pengirisan')->countAllResults() ?? 0;
    }
    public function countPemotonganfrs()
    {
        return $this->where('status_frs =', 'Pemotongan')->countAllResults() ?? 0;
    }
    public function countPemprosesanfrs()
    {
        return $this->where('status_frs =', 'Pemprosesan')->countAllResults() ?? 0;
    }
    public function countPenanamanfrs()
    {
        return $this->where('status_frs =', 'Penanaman')->countAllResults() ?? 0;
    }
    public function countPemotonganTipisfrs()
    {
        return $this->where('status_frs =', 'Pemotongan Tipis')->countAllResults() ?? 0;
    }
    public function countPewarnaanfrs()
    {
        return $this->where('status_frs =', 'Pewarnaan')->countAllResults() ?? 0;
    }
    public function countPembacaanfrs()
    {
        return $this->where('status_frs =', 'Pembacaan')->countAllResults() ?? 0;
    }
    public function countPenulisanfrs()
    {
        return $this->where('status_frs =', 'Penulisan')->countAllResults() ?? 0;
    }
    public function countPemverifikasifrs()
    {
        return $this->where('status_frs =', 'Pemverifikasi')->countAllResults() ?? 0;
    }
    public function countAuthorizedfrs()
    {
        return $this->where('status_frs =', 'Authorized')->countAllResults() ?? 0;
    }
    public function countPencetakanfrs()
    {
        return $this->where('status_frs =', 'Pencetakan')->countAllResults() ?? 0;
    }

    public function getfrsChartData()
    {
        return $this->select("DATE_FORMAT(tanggal_permintaan, '%Y') AS tahun, DATE_FORMAT(tanggal_permintaan, '%M') AS bulan, COUNT(*) AS total")
            ->where('tanggal_permintaan IS NOT NULL')
            ->groupBy("tahun, bulan")
            ->orderBy("MIN(tanggal_permintaan)", "ASC")
            ->findAll();
    }

    public function insertfrs(array $data): bool
    {
        if ($this->where('kode_frs', $data['kode_frs'])->first()) {
            return false;
        }

        return $this->insertfrs($data) > 0;
    }

    public function getfrsWitfrstient($id_frs)
    {
        return $this->select('frs.*, patient.*')
            ->join('patient', 'patient.id_pasien = frs.id_pasien')
            ->where('frs.id_frs', $id_frs)
            ->first();
    }

    public function getfrsWithRelationsProses($id_frs)
    {
        return $this->select('frs.*, patient.*, penerimaan_frs.*, pembacaan_frs.*, penulisan_frs.*, pemverifikasi_frs.*, authorized_frs.*, pencetakan_frs.*, mutu_frs.*')
            ->join('patient', 'patient.id_pasien = frs.id_pasien', 'left')
            ->join('penerimaan_frs', 'penerimaan_frs.id_frs = frs.id_frs', 'left')
            ->join('pembacaan_frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('penulisan_frs', 'penulisan_frs.id_frs = frs.id_frs', 'left')
            ->join('pemverifikasi_frs', 'pemverifikasi_frs.id_frs = frs.id_frs', 'left')
            ->join('authorized_frs', 'authorized_frs.id_frs = frs.id_frs', 'left')
            ->join('pencetakan_frs', 'pencetakan_frs.id_frs = frs.id_frs', 'left')
            ->join('mutu_frs', 'mutu_frs.id_frs = frs.id_frs', 'left')
            ->where('frs.id_frs', $id_frs)
            ->first();
    }

    public function updatefrs($id_frs, $data)
    {
        $builder = $this->db->table('frs');
        $builder->where('id_frs', $id_frs);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function updatePenerima($id_frs, $data)
    {
        // Validasi parameter
        if (
            empty($id_frs) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID frs atau data tidak valid.');
        }
        // Mengambil table 'frs'
        $builder = $this->db->table('frs');
        // Menambahkan kondisi WHERE
        $builder->where('id_frs', $id_frs);
        // Melakukan update data
        $updateResult = $builder->update($data);
        // Mengecek apakah update berhasil
        if ($updateResult) {
            return $this->db->affectedRows();
        } else {
            throw new \RuntimeException('Update data gagal.');
        }
    }

    public function updateStatusfrs($id_frs, $data)
    {
        // Validasi parameter
        if (
            empty($id_frs) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID frs atau data tidak valid.');
        }
        // Mengambil table 'frs'
        $builder = $this->db->table('frs');
        // Menambahkan kondisi WHERE
        $builder->where('id_frs', $id_frs);
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

<?php

namespace App\Models\Srs;

use CodeIgniter\Model;

class SrsModel extends Model
{
    protected $table = 'srs';
    protected $primaryKey = 'id_srs';
    protected $allowedFields = [
        'kode_srs',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_srs',
        'makroskopis_srs',
        'foto_makroskopis_srs',
        'mikroskopis_srs',
        'foto_mikroskopis_srs',
        'jumlah_slide',
        'hasil_srs',
        'print_srs',
        'penerima_srs',
        'tanggal_penerima',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLastKodesrs()
    {
        return $this->orderBy('id_srs', 'DESC')->first(); // Ambil data terakhir berdasarkan ID
    }

    public function countProsessrs()
    {
        return $this->where('status_srs !=', 'Selesai')->countAllResults() ?? 0;
    }
    public function countPenerimaansrs()
    {
        return $this->where('status_srs', 'Penerimaan')->countAllResults() ?? 0;
    }
    public function countPengirisansrs()
    {
        return $this->where('status_srs =', 'Pengirisan')->countAllResults() ?? 0;
    }
    public function countPemotongansrs()
    {
        return $this->where('status_srs =', 'Pemotongan')->countAllResults() ?? 0;
    }
    public function countPemprosesansrs()
    {
        return $this->where('status_srs =', 'Pemprosesan')->countAllResults() ?? 0;
    }
    public function countPenanamansrs()
    {
        return $this->where('status_srs =', 'Penanaman')->countAllResults() ?? 0;
    }
    public function countPemotonganTipissrs()
    {
        return $this->where('status_srs =', 'Pemotongan Tipis')->countAllResults() ?? 0;
    }
    public function countPewarnaansrs()
    {
        return $this->where('status_srs =', 'Pewarnaan')->countAllResults() ?? 0;
    }
    public function countPembacaansrs()
    {
        return $this->where('status_srs =', 'Pembacaan')->countAllResults() ?? 0;
    }
    public function countPenulisansrs()
    {
        return $this->where('status_srs =', 'Penulisan')->countAllResults() ?? 0;
    }
    public function countPemverifikasisrs()
    {
        return $this->where('status_srs =', 'Pemverifikasi')->countAllResults() ?? 0;
    }
    public function countAuthorizedsrs()
    {
        return $this->where('status_srs =', 'Authorized')->countAllResults() ?? 0;
    }
    public function countPencetakansrs()
    {
        return $this->where('status_srs =', 'Pencetakan')->countAllResults() ?? 0;
    }

    public function getsrsChartData()
    {
        return $this->select("DATE_FORMAT(tanggal_permintaan, '%Y') AS tahun, DATE_FORMAT(tanggal_permintaan, '%M') AS bulan, COUNT(*) AS total")
            ->where('tanggal_permintaan IS NOT NULL')
            ->groupBy("tahun, bulan")
            ->orderBy("MIN(tanggal_permintaan)", "ASC")
            ->findAll();
    }

    public function getTotalSrs() {
        return $this->db->table('srs')->countAllResults();
    }

    public function getsrsWithPatient()
    {
        return $this->select('srs.*, patient.*')
            ->join('patient', 'patient.id_pasien = srs.id_pasien')
            ->orderBy('srs.kode_srs', 'ASC')
            ->findAll();
    }

    public function getsrsWithRelationsProses($id_srs)
    {
        return $this->select('srs.*, patient.*, penerimaan_srs.*, pembacaan_srs.*, penulisan_srs.*, pemverifikasi_srs.*, authorized_srs.*, pencetakan_srs.*, mutu_srs.*')
            ->join('patient', 'patient.id_pasien = srs.id_pasien', 'left')
            ->join('penerimaan_srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('penulisan_srs', 'penulisan_srs.id_srs = srs.id_srs', 'left')
            ->join('pemverifikasi_srs', 'pemverifikasi_srs.id_srs = srs.id_srs', 'left')
            ->join('authorized_srs', 'authorized_srs.id_srs = srs.id_srs', 'left')
            ->join('pencetakan_srs', 'pencetakan_srs.id_srs = srs.id_srs', 'left')
            ->join('mutu_srs', 'mutu_srs.id_srs = srs.id_srs', 'left')
            ->where('srs.id_srs', $id_srs)
            ->first();
    }

    public function riwayatPemeriksaansrs($id_pasien)
    {
        return $this->select('srs.*, pembacaan_srs.id_user_dokter_pembacaan_srs, users.nama_user AS dokter_nama')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('users', 'users.id_user = pembacaan_srs.id_user_dokter_pembacaan_srs', 'left')
            ->where('srs.id_pasien', $id_pasien)
            ->where('srs.hasil_srs IS NOT NULL', null, false)
            ->findAll();
    }

    public function updatePenerima($id_srs, $data)
    {
        // Validasi parameter
        if (
            empty($id_srs) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID srs atau data tidak valid.');
        }
        // Mengambil table 'srs'
        $builder = $this->db->table('srs');
        // Menambahkan kondisi WHERE
        $builder->where('id_srs', $id_srs);
        // Melakukan update data
        $updateResult = $builder->update($data);
        // Mengecek apakah update berhasil
        if ($updateResult) {
            return $this->db->affectedRows();
        } else {
            throw new \RuntimeException('Update data gagal.');
        }
    }

    public function updateStatussrs($id_srs, $data)
    {
        // Validasi parameter
        if (
            empty($id_srs) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID srs atau data tidak valid.');
        }
        // Mengambil table 'srs'
        $builder = $this->db->table('srs');
        // Menambahkan kondisi WHERE
        $builder->where('id_srs', $id_srs);
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

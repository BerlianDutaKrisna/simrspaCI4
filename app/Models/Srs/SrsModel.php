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
        'id_transaksi',
        'tanggal_transaksi',
        'no_register',
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

    public function getTotalSrs()
    {
        return $this->db->table('srs')->countAllResults();
    }

    public function getsrsWithPatient()
    {
        return $this->select('
            srs.*, 
            patient.*, 
            penerimaan_srs.id_penerimaan_srs AS id_penerimaan,
            pewarnaan_srs.id_pewarnaan_srs AS id_pewarnaan,
            pembacaan_srs.id_pembacaan_srs AS id_pembacaan,
            penulisan_srs.id_penulisan_srs AS id_penulisan,
            pemverifikasi_srs.id_pemverifikasi_srs AS id_pemverifikasi,
            authorized_srs.id_authorized_srs AS id_authorized,
            pencetakan_srs.id_pencetakan_srs AS id_pencetakan,
            mutu_srs.id_mutu_srs AS id_mutu
        ')
            ->join('patient', 'patient.id_pasien = srs.id_pasien', 'left')
            ->join('penerimaan_srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left')
            ->join('pewarnaan_srs', 'pewarnaan_srs.id_srs = srs.id_srs', 'left')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('penulisan_srs', 'penulisan_srs.id_srs = srs.id_srs', 'left')
            ->join('pemverifikasi_srs', 'pemverifikasi_srs.id_srs = srs.id_srs', 'left')
            ->join('authorized_srs', 'authorized_srs.id_srs = srs.id_srs', 'left')
            ->join('pencetakan_srs', 'pencetakan_srs.id_srs = srs.id_srs', 'left')
            ->join('mutu_srs', 'mutu_srs.id_srs = srs.id_srs', 'left')
            ->orderBy("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_srs, '/', 1), '.', -1) AS UNSIGNED) ASC")
            ->orderBy("CAST(SUBSTRING_INDEX(kode_srs, '/', -1) AS UNSIGNED) ASC")
            ->findAll();
    }

    public function getsrsWithPatientDESC()
    {
        return $this->select('
            srs.*, 
            patient.*, 
            penerimaan_srs.id_penerimaan_srs AS id_penerimaan,
            pembacaan_srs.id_pembacaan_srs AS id_pembacaan,
            penulisan_srs.id_penulisan_srs AS id_penulisan,
            pemverifikasi_srs.id_pemverifikasi_srs AS id_pemverifikasi,
            authorized_srs.id_authorized_srs AS id_authorized,
            pencetakan_srs.id_pencetakan_srs AS id_pencetakan,
            mutu_srs.id_mutu_srs AS id_mutu,
            dokter_pembacaan.nama_user AS nama_dokter_pembacaan
        ')
            ->join('patient', 'patient.id_pasien = srs.id_pasien', 'left')
            ->join('penerimaan_srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('penulisan_srs', 'penulisan_srs.id_srs = srs.id_srs', 'left')
            ->join('pemverifikasi_srs', 'pemverifikasi_srs.id_srs = srs.id_srs', 'left')
            ->join('authorized_srs', 'authorized_srs.id_srs = srs.id_srs', 'left')
            ->join('pencetakan_srs', 'pencetakan_srs.id_srs = srs.id_srs', 'left')
            ->join('mutu_srs', 'mutu_srs.id_srs = srs.id_srs', 'left')
            ->join('users AS dokter_pembacaan', 'dokter_pembacaan.id_user = pembacaan_srs.id_user_dokter_pembacaan_srs', 'left')
            ->orderBy("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_srs, '/', 1), '.', -1) AS UNSIGNED) DESC")
            ->orderBy("CAST(SUBSTRING_INDEX(kode_srs, '/', -1) AS UNSIGNED) DESC")
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
        return $this
            ->select('srs.*, pembacaan_srs.id_user_dokter_pembacaan_srs, users.nama_user AS dokter_nama')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('users', 'users.id_user = pembacaan_srs.id_user_dokter_pembacaan_srs', 'left')
            ->where('srs.id_pasien', $id_pasien)
            ->groupStart()
            ->where('srs.hasil_srs IS NOT NULL', null, false)
            ->where('srs.hasil_srs !=', '')
            ->groupEnd()
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

    public function getsrsWithRelations()
    {
        $previousMonthStart = date('Y-m-01', strtotime('-1 month'));

        return $this->select('
            srs.*, 
            patient.*, 
            users.nama_user AS dokter_pembaca,
            penerimaan_srs.mulai_penerimaan_srs,
            penulisan_srs.selesai_penulisan_srs,
            mutu_srs.total_nilai_mutu_srs
        ')
            ->join('patient', 'patient.id_pasien = srs.id_pasien')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('users', 'users.id_user = pembacaan_srs.id_user_dokter_pembacaan_srs', 'left')
            ->join('penerimaan_srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left')
            ->join('penulisan_srs', 'penulisan_srs.id_srs = srs.id_srs', 'left')
            ->join('mutu_srs', 'mutu_srs.id_srs = srs.id_srs', 'left')
            ->where('srs.tanggal_permintaan >=', $previousMonthStart)
            ->where('srs.tanggal_permintaan <=', date('Y-m-t'))
            ->orderBy("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_srs, '/', 1), '.', -1) AS UNSIGNED) ASC")
            ->orderBy("CAST(SUBSTRING_INDEX(kode_srs, '/', -1) AS UNSIGNED) ASC")
            ->findAll();
    }

    public function filtersrsWithRelations($filterField, $filterValue, $startDate, $endDate)
    {
        $builder = $this->db->table('srs')
            ->select("
            srs.*,
            patient.*,
            users.nama_user AS dokter_pembaca,
            penerimaan_srs.mulai_penerimaan_srs,
            pemverifikasi_srs.selesai_pemverifikasi_srs,
            mutu_srs.total_nilai_mutu_srs
        ")
            ->join('patient', 'patient.id_pasien = srs.id_pasien')
            ->join('pembacaan_srs', 'pembacaan_srs.id_srs = srs.id_srs', 'left')
            ->join('users', 'users.id_user = pembacaan_srs.id_user_dokter_pembacaan_srs', 'left')
            ->join('penerimaan_srs', 'penerimaan_srs.id_srs = srs.id_srs', 'left')
            ->join('pemverifikasi_srs', 'pemverifikasi_srs.id_srs = srs.id_srs', 'left')
            ->join('mutu_srs', 'mutu_srs.id_srs = srs.id_srs', 'left')
            ->where('srs.tanggal_permintaan >=', $startDate)
            ->where('srs.tanggal_permintaan <=', $endDate);

        // Jika ada filter tambahan dari user (nama pasien, norm, dll)
        if (!empty($filterField) && !empty($filterValue)) {
            $builder->like($filterField, $filterValue);
        }

        $builder->orderBy('srs.tanggal_permintaan', 'ASC')
            ->orderBy('srs.kode_srs', 'ASC');

        return $builder->get()->getResultArray();
    }
}

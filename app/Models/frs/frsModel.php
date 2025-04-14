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
    
    public function getTotalFrs() {
        return $this->db->table('frs')->countAllResults();
    }

    public function getfrsWithPatient()
    {
        return $this->select('frs.*, patient.*')
            ->join('patient', 'patient.id_pasien = frs.id_pasien')
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
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

    public function riwayatPemeriksaanfrs($id_pasien)
    {
        return $this
            ->select('frs.*, pembacaan_frs.id_user_dokter_pembacaan_frs, users.nama_user AS dokter_nama')
            ->join('pembacaan_frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('users', 'users.id_user = pembacaan_frs.id_user_dokter_pembacaan_frs', 'left')
            ->where('frs.id_pasien', $id_pasien)
            ->groupStart()
            ->where('frs.hasil_frs IS NOT NULL', null, false)
            ->where('frs.hasil_frs !=', '')
            ->groupEnd()
            ->findAll();
    }

    public function updatePenerima($id_frs, $data)
    {
        if (
            empty($id_frs) || empty($data) || !is_array($data)
        ) {
            throw new \InvalidArgumentException('Parameter ID frs atau data tidak valid.');
        }
        $builder = $this->db->table('frs');
        $builder->where('id_frs', $id_frs);
        $updateResult = $builder->update($data);
        if ($updateResult) {
            return $this->db->affectedRows();
        } else {
            throw new \RuntimeException('Update data gagal.');
        }
    }

    public function getfrsWithRelations()
    {
        $previousMonthStart = date('Y-m-01', strtotime('-1 month'));

        return $this->select('
            frs.*, 
            patient.*, 
            users.nama_user AS dokter_pembaca,
            penerimaan_frs.mulai_penerimaan_frs,
            pemverifikasi_frs.selesai_pemverifikasi_frs,
            mutu_frs.total_nilai_mutu_frs
        ')
            ->join('patient', 'patient.id_pasien = frs.id_pasien')
            ->join('pembacaan_frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('users', 'users.id_user = pembacaan_frs.id_user_dokter_pembacaan_frs', 'left')
            ->join('penerimaan_frs', 'penerimaan_frs.id_frs = frs.id_frs', 'left')
            ->join('pemverifikasi_frs', 'pemverifikasi_frs.id_frs = frs.id_frs', 'left')
            ->join('mutu_frs', 'mutu_frs.id_frs = frs.id_frs', 'left')
            ->where('frs.tanggal_permintaan >=', $previousMonthStart)
            ->where('frs.tanggal_permintaan <=', date('Y-m-t'))
            ->orderBy('frs.kode_frs', 'ASC')
            ->findAll();
    }

    public function filterfrsWithRelations($filterField, $filterValue, $startDate, $endDate)
    {
        $builder = $this->db->table('frs')
            ->select("
            frs.*,
            patient.*,
            users.nama_user AS dokter_pembaca,
            penerimaan_frs.mulai_penerimaan_frs,
            pemverifikasi_frs.selesai_pemverifikasi_frs,
            mutu_frs.total_nilai_mutu_frs
        ")
            ->join('patient', 'patient.id_pasien = frs.id_pasien')
            ->join('pembacaan_frs', 'pembacaan_frs.id_frs = frs.id_frs', 'left')
            ->join('users', 'users.id_user = pembacaan_frs.id_user_dokter_pembacaan_frs', 'left')
            ->join('penerimaan_frs', 'penerimaan_frs.id_frs = frs.id_frs', 'left')
            ->join('pemverifikasi_frs', 'pemverifikasi_frs.id_frs = frs.id_frs', 'left')
            ->join('mutu_frs', 'mutu_frs.id_frs = frs.id_frs', 'left')
            ->where('frs.tanggal_permintaan >=', $startDate)
            ->where('frs.tanggal_permintaan <=', $endDate);

        // Jika ada filter tambahan dari user (nama pasien, norm, dll)
        if (!empty($filterField) && !empty($filterValue)) {
            $builder->like($filterField, $filterValue);
        }

        $builder->orderBy('frs.tanggal_permintaan', 'ASC')
            ->orderBy('frs.kode_frs', 'ASC');

        return $builder->get()->getResultArray();
    }
}

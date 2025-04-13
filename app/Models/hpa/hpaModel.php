<?php

namespace App\Models\Hpa;

use CodeIgniter\Model;

class HpaModel extends Model
{
    protected $table = 'hpa';
    protected $primaryKey = 'id_hpa';
    protected $allowedFields = [
        'kode_hpa',
        'id_pasien',
        'unit_asal',
        'dokter_pengirim',
        'tanggal_permintaan',
        'tanggal_hasil',
        'lokasi_spesimen',
        'tindakan_spesimen',
        'diagnosa_klinik',
        'status_hpa',
        'makroskopis_hpa',
        'foto_makroskopis_hpa',
        'mikroskopis_hpa',
        'foto_mikroskopis_hpa',
        'jumlah_slide',
        'hasil_hpa',
        'print_hpa',
        'penerima_hpa',
        'tanggal_penerima',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLastKodeHPA()
    {
        return $this->orderBy('id_hpa', 'DESC')->first(); // Ambil data terakhir berdasarkan ID
    }
    public function countProseshpa()
    {
        return $this->where('status_hpa !=', 'Selesai')->countAllResults() ?? 0;
    }
    public function countPenerimaanhpa()
    {
        return $this->where('status_hpa', 'Penerimaan')->countAllResults() ?? 0;
    }
    public function countPengirisanhpa()
    {
        return $this->where('status_hpa =', 'Pengirisan')->countAllResults() ?? 0;
    }
    public function countPemotonganhpa()
    {
        return $this->where('status_hpa =', 'Pemotongan')->countAllResults() ?? 0;
    }
    public function countPemprosesanhpa()
    {
        return $this->where('status_hpa =', 'Pemprosesan')->countAllResults() ?? 0;
    }
    public function countPenanamanhpa()
    {
        return $this->where('status_hpa =', 'Penanaman')->countAllResults() ?? 0;
    }
    public function countPemotonganTipishpa()
    {
        return $this->where('status_hpa =', 'Pemotongan Tipis')->countAllResults() ?? 0;
    }
    public function countPewarnaanhpa()
    {
        return $this->where('status_hpa =', 'Pewarnaan')->countAllResults() ?? 0;
    }
    public function countPembacaanhpa()
    {
        return $this->where('status_hpa =', 'Pembacaan')->countAllResults() ?? 0;
    }
    public function countPenulisanhpa()
    {
        return $this->where('status_hpa =', 'Penulisan')->countAllResults() ?? 0;
    }
    public function countPemverifikasihpa()
    {
        return $this->where('status_hpa =', 'Pemverifikasi')->countAllResults() ?? 0;
    }
    public function countAuthorizedhpa()
    {
        return $this->where('status_hpa =', 'Authorized')->countAllResults() ?? 0;
    }
    public function countPencetakanhpa()
    {
        return $this->where('status_hpa =', 'Pencetakan')->countAllResults() ?? 0;
    }

    public function getHpaChartData()
    {
        return $this->select("DATE_FORMAT(tanggal_permintaan, '%Y') AS tahun, DATE_FORMAT(tanggal_permintaan, '%M') AS bulan, COUNT(*) AS total")
            ->where('tanggal_permintaan IS NOT NULL')
            ->groupBy("tahun, bulan")
            ->orderBy("MIN(tanggal_permintaan)", "ASC")
            ->findAll();
    }
    
    public function getTotalHpa() {
        return $this->db->table('hpa')->countAllResults();
    }

    public function gethpaWithPatient()
{
    return $this->select('
            hpa.*, 
            patient.*, 
            penerimaan_hpa.id_penerimaan_hpa AS id_penerimaan,
            pemotongan_hpa.id_pemotongan_hpa AS id_pemotongan,
            pemprosesan_hpa.id_pemprosesan_hpa AS id_pemprosesan,
            penanaman_hpa.id_penanaman_hpa AS id_penanaman,
            pemotongan_tipis_hpa.id_pemotongan_tipis_hpa AS id_pemotongan_tipis,
            pewarnaan_hpa.id_pewarnaan_hpa AS id_pewarnaan,
            pembacaan_hpa.id_pembacaan_hpa AS id_pembacaan,
            penulisan_hpa.id_penulisan_hpa AS id_penulisan,
            pemverifikasi_hpa.id_pemverifikasi_hpa AS id_pemverifikasi,
            authorized_hpa.id_authorized_hpa AS id_authorized,
            pencetakan_hpa.id_pencetakan_hpa AS id_pencetakan,
            mutu_hpa.id_mutu_hpa AS id_mutu
        ')
        ->join('patient', 'patient.id_pasien = hpa.id_pasien', 'left')
        ->join('penerimaan_hpa', 'penerimaan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pemotongan_hpa', 'pemotongan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pemprosesan_hpa', 'pemprosesan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('penanaman_hpa', 'penanaman_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pemotongan_tipis_hpa', 'pemotongan_tipis_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pewarnaan_hpa', 'pewarnaan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pembacaan_hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('penulisan_hpa', 'penulisan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pemverifikasi_hpa', 'pemverifikasi_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('authorized_hpa', 'authorized_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('pencetakan_hpa', 'pencetakan_hpa.id_hpa = hpa.id_hpa', 'left')
        ->join('mutu_hpa', 'mutu_hpa.id_hpa = hpa.id_hpa', 'left')
        ->orderBy('hpa.kode_hpa', 'ASC')
        ->findAll();
}


    public function getHpaWithRelationsProses($id_hpa)
    {
        return $this->select('hpa.*, patient.*, penerimaan_hpa.*, pemotongan_hpa.*, pembacaan_hpa.*, penulisan_hpa.*, pemverifikasi_hpa.*, authorized_hpa.*, pencetakan_hpa.*, mutu_hpa.*')
            ->join('patient', 'patient.id_pasien = hpa.id_pasien', 'left')
            ->join('penerimaan_hpa', 'penerimaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('pemotongan_hpa', 'pemotongan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('pembacaan_hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('penulisan_hpa', 'penulisan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('pemverifikasi_hpa', 'pemverifikasi_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('authorized_hpa', 'authorized_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('pencetakan_hpa', 'pencetakan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('mutu_hpa', 'mutu_hpa.id_hpa = hpa.id_hpa', 'left')
            ->where('hpa.id_hpa', $id_hpa)
            ->first();
    }

    public function riwayatPemeriksaanhpa($id_pasien)
    {
        return $this
            ->select('hpa.*, pembacaan_hpa.id_user_dokter_pembacaan_hpa, users.nama_user AS dokter_nama')
            ->join('pembacaan_hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('users', 'users.id_user = pembacaan_hpa.id_user_dokter_pembacaan_hpa', 'left')
            ->where('hpa.id_pasien', $id_pasien)
            ->groupStart()
            ->where('hpa.hasil_hpa IS NOT NULL', null, false)
            ->where('hpa.hasil_hpa !=', '')
            ->groupEnd()
            ->findAll();
    }

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
            return $this->db->affectedRows();
        } else {
            throw new \RuntimeException('Update data gagal.');
        }
    }

    public function gethpaWithRelations()
    {
        $previousMonthStart = date('Y-m-01', strtotime('-1 month'));

        return $this->select('
            hpa.*, 
            patient.*, 
            users.nama_user AS dokter_pembaca,
            penerimaan_hpa.mulai_penerimaan_hpa,
            pemverifikasi_hpa.selesai_pemverifikasi_hpa,
            mutu_hpa.total_nilai_mutu_hpa
        ')
            ->join('patient', 'patient.id_pasien = hpa.id_pasien')
            ->join('pembacaan_hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('users', 'users.id_user = pembacaan_hpa.id_user_dokter_pembacaan_hpa', 'left')
            ->join('penerimaan_hpa', 'penerimaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('pemverifikasi_hpa', 'pemverifikasi_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('mutu_hpa', 'mutu_hpa.id_hpa = hpa.id_hpa', 'left')
            ->where('hpa.tanggal_permintaan >=', $previousMonthStart)
            ->where('hpa.tanggal_permintaan <=', date('Y-m-t'))
            ->orderBy('hpa.kode_hpa', 'ASC')
            ->findAll();
    }

    public function filterhpaWithRelations($filterField, $filterValue, $startDate, $endDate)
    {
        $builder = $this->db->table('hpa')
            ->select("
            hpa.*,
            patient.*,
            users.nama_user AS dokter_pembaca,
            penerimaan_hpa.mulai_penerimaan_hpa,
            pemverifikasi_hpa.selesai_pemverifikasi_hpa,
            mutu_hpa.total_nilai_mutu_hpa
        ")
            ->join('patient', 'patient.id_pasien = hpa.id_pasien')
            ->join('pembacaan_hpa', 'pembacaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('users', 'users.id_user = pembacaan_hpa.id_user_dokter_pembacaan_hpa', 'left')
            ->join('penerimaan_hpa', 'penerimaan_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('pemverifikasi_hpa', 'pemverifikasi_hpa.id_hpa = hpa.id_hpa', 'left')
            ->join('mutu_hpa', 'mutu_hpa.id_hpa = hpa.id_hpa', 'left')
            ->where('hpa.tanggal_permintaan >=', $startDate)
            ->where('hpa.tanggal_permintaan <=', $endDate);

        // Jika ada filter tambahan dari user (nama pasien, norm, dll)
        if (!empty($filterField) && !empty($filterValue)) {
            $builder->like($filterField, $filterValue);
        }

        $builder->orderBy('hpa.tanggal_permintaan', 'ASC')
            ->orderBy('hpa.kode_hpa', 'ASC');

        return $builder->get()->getResultArray();
    }
}

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
        'foto_makroskopis_ihc',
        'mikroskopis_ihc',
        'foto_mikroskopis_ihc',
        'jumlah_slide',
        'hasil_ihc',
        'print_ihc',
        'penerima_ihc',
        'tanggal_penerima',
        'no_tlp_ihc',
        'no_bpjs_ihc',
        'no_ktp_ihc',
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
        return $this->where('status_ihc =', 'Authorized')->countAllResults() ?? 0;
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

    public function getTotalIhc() {
        return $this->db->table('ihc')->countAllResults();
    }

    public function getihcWithPatient()
    {
        return $this->select('ihc.*, patient.*')
            ->join('patient', 'patient.id_pasien = ihc.id_pasien')
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    public function getihcWithRelationsProses($id_ihc)
    {
        return $this->select('ihc.*, patient.*, penerimaan_ihc.*, pembacaan_ihc.*, penulisan_ihc.*, pemverifikasi_ihc.*, authorized_ihc.*, pencetakan_ihc.*, mutu_ihc.*')
            ->join('patient', 'patient.id_pasien = ihc.id_pasien', 'left')
            ->join('penerimaan_ihc', 'penerimaan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('pembacaan_ihc', 'pembacaan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('penulisan_ihc', 'penulisan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('pemverifikasi_ihc', 'pemverifikasi_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('authorized_ihc', 'authorized_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('pencetakan_ihc', 'pencetakan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('mutu_ihc', 'mutu_ihc.id_ihc = ihc.id_ihc', 'left')
            ->where('ihc.id_ihc', $id_ihc)
            ->first();
    }

    public function riwayatPemeriksaanihc($id_pasien)
    {
        return $this
            ->select('ihc.*, pembacaan_ihc.id_user_dokter_pembacaan_ihc, users.nama_user AS dokter_nama')
            ->join('pembacaan_ihc', 'pembacaan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('users', 'users.id_user = pembacaan_ihc.id_user_dokter_pembacaan_ihc', 'left')
            ->where('ihc.id_pasien', $id_pasien)
            ->groupStart()
            ->where('ihc.hasil_ihc IS NOT NULL', null, false)
            ->where('ihc.hasil_ihc !=', '')
            ->groupEnd()
            ->findAll();
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

    public function getihcWithRelations()
    {
        $previousMonthStart = date('Y-m-01', strtotime('-1 month'));

        return $this->select('
            ihc.*, 
            patient.*, 
            users.nama_user AS dokter_pembaca,
            penerimaan_ihc.mulai_penerimaan_ihc,
            pemverifikasi_ihc.selesai_pemverifikasi_ihc,
            mutu_ihc.total_nilai_mutu_ihc
        ')
            ->join('patient', 'patient.id_pasien = ihc.id_pasien')
            ->join('pembacaan_ihc', 'pembacaan_ihc.id_ihc = ihc.id_ihc')
            ->join('users', 'users.id_user = pembacaan_ihc.id_user_dokter_pembacaan_ihc')
            ->join('penerimaan_ihc', 'penerimaan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('pemverifikasi_ihc', 'pemverifikasi_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('mutu_ihc', 'mutu_ihc.id_ihc = ihc.id_ihc', 'left')
            ->where('ihc.tanggal_permintaan >=', $previousMonthStart)
            ->where('ihc.tanggal_permintaan <=', date('Y-m-t'))
            ->orderBy('ihc.kode_ihc', 'ASC')
            ->findAll();
    }

    public function filterihcWithRelations($filterField, $filterValue, $startDate, $endDate)
    {
        $builder = $this->db->table('ihc')
            ->select("
            ihc.*,
            patient.*,
            users.nama_user AS dokter_pembaca,
            penerimaan_ihc.mulai_penerimaan_ihc,
            pemverifikasi_ihc.selesai_pemverifikasi_ihc,
            mutu_ihc.total_nilai_mutu_ihc
        ")
            ->join('patient', 'patient.id_pasien = ihc.id_pasien')
            ->join('pembacaan_ihc', 'pembacaan_ihc.id_ihc = ihc.id_ihc')
            ->join('users', 'users.id_user = pembacaan_ihc.id_user_dokter_pembacaan_ihc')
            ->join('penerimaan_ihc', 'penerimaan_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('pemverifikasi_ihc', 'pemverifikasi_ihc.id_ihc = ihc.id_ihc', 'left')
            ->join('mutu_ihc', 'mutu_ihc.id_ihc = ihc.id_ihc', 'left')
            ->where('ihc.tanggal_permintaan >=', $startDate)
            ->where('ihc.tanggal_permintaan <=', $endDate);

        // Jika ada filter tambahan dari user (nama pasien, norm, dll)
        if (!empty($filterField) && !empty($filterValue)) {
            $builder->like($filterField, $filterValue);
        }

        $builder->orderBy('ihc.tanggal_permintaan', 'ASC')
            ->orderBy('ihc.kode_ihc', 'ASC');

        return $builder->get()->getResultArray();
    }
}

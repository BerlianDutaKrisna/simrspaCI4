<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\Frs\FrsModel;
use App\Models\Srs\SrsModel;
use App\Models\Ihc\IhcModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\KunjunganModel;

class Dashboard extends BaseController
{
    protected $hpaModel;
    protected $frsModel;
    protected $srsModel;
    protected $ihcModel;
    protected $usersModel;
    protected $patientModel;
    protected $kunjunganModel;

    public function __construct()
    {
        $this->hpaModel       = new hpaModel();
        $this->frsModel       = new frsModel();
        $this->srsModel       = new srsModel();
        $this->ihcModel       = new ihcModel();
        $this->usersModel     = new UsersModel();
        $this->patientModel   = new PatientModel();
        $this->kunjunganModel = new KunjunganModel();
        $this->session        = \Config\Services::session();
    }

    public function index()
    {
        $data = [
            'id_user' => $this->session->get('id_user'),
            'nama_user' => $this->session->get('nama_user'),
            'counts' => $this->getCounts(),

            'chartData' => json_encode([
                'hpa' => $this->hpaModel->getHpaChartData(),
                'frs' => $this->frsModel->getfrsChartData(),
                'srs' => $this->srsModel->getsrsChartData(),
                'ihc' => $this->ihcModel->getihcChartData()
            ], JSON_NUMERIC_CHECK),

            // Jangan encode di sini
            'pieChartData' => [
                'hpa' => $this->hpaModel->getTotalHpa(),
                'frs' => $this->frsModel->getTotalFrs(),
                'srs' => $this->srsModel->getTotalSrs(),
                'ihc' => $this->ihcModel->getTotalIhc()
            ],

            'pieChartUserData' => [
                'endar' => $this->usersModel->getTotalByUserName("Endar Pratiwi, S.Si"),
                'arlina' => $this->usersModel->getTotalByUserName("Arlina Kartika, A.Md.AK"),
                'ilham' => $this->usersModel->getTotalByUserName("Ilham Tyas Ismadi, A.Md.Kes"),
                'berlian' => $this->usersModel->getTotalByUserName("Berlian Duta Krisna, S.Tr.Kes"),
            ],

            'pieChartDokterData' => [
                'vinna' => $this->usersModel->getTotalByUserName("dr. Vinna Chrisdianti, Sp.PA"),
                'ayu'   => $this->usersModel->getTotalByUserName("dr. Ayu Tyasmara Pratiwi, Sp.PA"),
            ],

            // Jumlah pasien OK EMERGENCY & OK ELEKTIF yang Belum Terdaftar (untuk notifikasi dashboard)
            'count_ok_emergency' => $this->kunjunganModel->countOkEmergencyBelumTerdaftar(),
            'count_ok_elektif'   => $this->kunjunganModel->countOkElektifBelumTerdaftar(),
        ];
        
        return view('dashboard/dashboard', $data);
    }
}

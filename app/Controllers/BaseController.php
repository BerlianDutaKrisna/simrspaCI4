<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Hpa\hpaModel;
use App\Models\Frs\frsModel;
use App\Models\Srs\srsModel;
use App\models\Ihc\ihcModel;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;
    protected $id_user;
    protected $nama_user;
    protected $hpaModel;
    protected $frsModel;
    protected $srsModel;
    protected $ihcModel;


    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->hpaModel = new hpaModel();
        $this->frsModel = new frsModel();
        $this->srsModel = new srsModel();
        $this->ihcModel = new ihcModel();
        // Inisialisasi sesi
        $this->session = \Config\Services::session();

        // Mengambil data sesi dan menangani potensi kesalahan
        $this->id_user = $this->session->get('id_user');
        $this->nama_user = $this->session->get('nama_user');

        // Memeriksa apakah pengguna sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/');
        }

        if (is_null($this->id_user) || is_null($this->nama_user)) {
            $logger->error('Data sesi hilang: id_user atau nama_user');
            return redirect()->to('/');
        }

    }

    protected function getCounts()
    {
        return [
            'countProseshpa' => $this->hpaModel->countProseshpa(),
            'countPenerimaanhpa' => $this->hpaModel->countPenerimaanhpa(),
            'countPengirisanhpa' => $this->hpaModel->countPengirisanhpa(),
            'countPemotonganhpa' => $this->hpaModel->countPemotonganhpa(),
            'countPemprosesanhpa' => $this->hpaModel->countPemprosesanhpa(),
            'countPenanamanhpa' => $this->hpaModel->countPenanamanhpa(),
            'countPemotonganTipishpa' => $this->hpaModel->countPemotonganTipishpa(),
            'countPewarnaanhpa' => $this->hpaModel->countPewarnaanhpa(),
            'countPembacaanhpa' => $this->hpaModel->countPembacaanhpa(),
            'countPenulisanhpa' => $this->hpaModel->countPenulisanhpa(),
            'countPemverifikasihpa' => $this->hpaModel->countPemverifikasihpa(),
            'countAuthorizedhpa' => $this->hpaModel->countAuthorizedhpa(),
            'countPencetakanhpa' => $this->hpaModel->countPencetakanhpa(),
            'countProsesfrs' => $this->frsModel->countProsesfrs(),
            'countPenerimaanfrs' => $this->frsModel->countPenerimaanfrs(),
            'countPembacaanfrs' => $this->frsModel->countPembacaanfrs(),
            'countPenulisanfrs' => $this->frsModel->countPenulisanfrs(),
            'countPemverifikasifrs' => $this->frsModel->countPemverifikasifrs(),
            'countAuthorizedfrs' => $this->frsModel->countAuthorizedfrs(),
            'countPencetakanfrs' => $this->frsModel->countPencetakanfrs(),
            'countProsessrs' => $this->srsModel->countProsessrs(),
            'countPenerimaansrs' => $this->srsModel->countPenerimaansrs(),
            'countPembacaansrs' => $this->srsModel->countPembacaansrs(),
            'countPenulisansrs' => $this->srsModel->countPenulisansrs(),
            'countPemverifikasisrs' => $this->srsModel->countPemverifikasisrs(),
            'countAuthorizedsrs' => $this->srsModel->countAuthorizedsrs(),
            'countPencetakansrs' => $this->srsModel->countPencetakansrs(),
            'countProsesihc' => $this->ihcModel->countProsesihc(),
            'countPenerimaanihc' => $this->ihcModel->countPenerimaanihc(),
            'countPembacaanihc' => $this->ihcModel->countPembacaanihc(),
            'countPenulisanihc' => $this->ihcModel->countPenulisanihc(),
            'countPemverifikasiihc' => $this->ihcModel->countPemverifikasiihc(),
            'countAuthorizedihc' => $this->ihcModel->countAuthorizedihc(),
            'countPencetakanihc' => $this->ihcModel->countPencetakanihc(),
        ];
    }

    protected function getUserData()
    {
        return [
            'id_user' => $this->id_user,
            'nama_user' => $this->nama_user,
        ];
    }
}

class SomeController extends BaseController
{
    public function someMethod()
    {
        $penerimaanData = $this->getPenerimaanData(); // Misalnya, fungsi untuk mengambil penerimaan data
        $counts = $this->getCounts();
        $userData = $this->getUserData();

        $data = array_merge(['penerimaanData' => $penerimaanData], $counts, $userData);

        // Gunakan $data untuk view atau proses selanjutnya
        return view('some_view', $data);
    }

    private function getPenerimaanData()
    {
        // Logika untuk mengambil penerimaan data
        return [];
    }
}

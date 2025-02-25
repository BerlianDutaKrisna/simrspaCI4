<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\hpa\hpaModel;
use App\Models\frs\frsModel;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;
    protected $id_user;
    protected $nama_user;
    protected $hpaModel;
    protected $frsModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

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
            'countPenerimaan' => $this->hpaModel->countPenerimaan(),
            'countPengirisan' => $this->hpaModel->countPengirisan(),
            'countPemotongan' => $this->hpaModel->countPemotongan(),
            'countPemprosesan' => $this->hpaModel->countPemprosesan(),
            'countPenanaman' => $this->hpaModel->countPenanaman(),
            'countPemotonganTipis' => $this->hpaModel->countPemotonganTipis(),
            'countPewarnaan' => $this->hpaModel->countPewarnaan(),
            'countPembacaan' => $this->hpaModel->countPembacaan(),
            'countPenulisan' => $this->hpaModel->countPenulisan(),
            'countPemverifikasi' => $this->hpaModel->countPemverifikasi(),
            'countAutorized' => $this->hpaModel->countAutorized(),
            'countPencetakan' => $this->hpaModel->countPencetakan(),
            'countPenerimaanfnab' => $this->frsModel->countPenerimaanfnab(),
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

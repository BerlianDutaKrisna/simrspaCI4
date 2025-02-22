<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;
    protected $id_user;
    protected $nama_user;
    protected $hpaModel;
    protected $fnabModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Inisialisasi session
        $this->session = \Config\Services::session();
        $this->id_user = $this->session->get('id_user');
        $this->nama_user = $this->session->get('nama_user');

        // Inisialisasi model
        $this->hpaModel = new \App\Models\HpaModel();
        $this->fnabModel = new \App\Models\Fnab\FnabModel();
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
            'countPenerimaanfnab' => $this->fnabModel->countPenerimaanfnab(),
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

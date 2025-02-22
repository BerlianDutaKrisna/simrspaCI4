<?php

namespace App\Controllers\Fnab\Proses;

use App\Controllers\BaseController;
use App\Models\Fnab\ProsesModel\PenerimaanModel;
use App\Models\Fnab\ProsesModel\PengirisanModel;
use App\Models\UsersModel;
use App\Models\Fnab\MutuModel;
use Exception;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Penerimaan extends BaseController
{
    protected $penerimaanModel;
    protected $pengirisanModel;
    protected $usersModel;
    protected $mutuModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Memanggil parent initController
        parent::initController($request, $response, $logger);

        // Inisialisasi model khusus untuk Penerimaan
        $this->penerimaanModel = new PenerimaanModel();
        $this->pengirisanModel = new PengirisanModel();
        $this->usersModel = new UsersModel();
        $this->mutuModel = new MutuModel();
    }

    public function index_penerimaan()
    {
        try {
            // Ambil data penerimaan
            $penerimaanData = $this->penerimaanModel->getPenerimaanWithRelations();
            // Ambil hitungan dan data pengguna
            $counts = $this->getCounts();
            $userData = $this->getUserData();

            // Gabungkan data untuk view
            $data = [
                'penerimaanData' => $penerimaanData,
                'counts' => $counts,
                'id_user' => $this->session->get('id_user'),
                'nama_user' => $this->session->get('nama_user'),
            ];
            
            return view('fnab/proses/penerimaan', $data);
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}

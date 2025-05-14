<?php

namespace App\Controllers\Exam;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\Hpa\Proses\Penerimaan_hpa;
use App\Models\Hpa\Proses\Pemotongan_hpa;
use App\Models\Hpa\Proses\pembacaan_hpa;
use App\Models\Hpa\Proses\Penulisan_hpa;
use App\Models\Hpa\Proses\Pemverifikasi_hpa;
use App\Models\Hpa\Proses\Authorized_hpa;
use App\Models\Hpa\Proses\Pencetakan_hpa;
use App\Models\Hpa\Mutu_hpa;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class ExamController extends BaseController
{
    protected $hpaModel;
    protected $usersModel;
    protected $patientModel;
    protected $penerimaan_hpa;
    protected $pemotongan_hpa;
    protected $pembacaan_hpa;
    protected $penulisan_hpa;
    protected $pemverifikasi_hpa;
    protected $authorized_hpa;
    protected $pencetakan_hpa;
    protected $mutu_hpa;
    protected $validation;

    public function __construct()
    {
        $this->hpaModel = new HpaModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaan_hpa = new Penerimaan_hpa();
        $this->pemotongan_hpa = new Pemotongan_hpa();
        $this->pembacaan_hpa = new pembacaan_hpa();
        $this->penulisan_hpa = new Penulisan_hpa();
        $this->pemverifikasi_hpa = new Pemverifikasi_hpa();
        $this->authorized_hpa = new Authorized_hpa();
        $this->pencetakan_hpa = new Pencetakan_hpa();
        $this->mutu_hpa = new Mutu_hpa();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $Data = $this->patientModel->getPatientWithRelations();
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'Data' => $Data
        ];
        
        return view('Exam/index', $data);
    }

    public function search()
    {
        $searchField = $this->request->getGet('searchInput');
        $searchValue = $this->request->getGet('searchValue');
        $startDate = $this->request->getGet('searchDate');
        $endDate = $this->request->getGet('searchDate2');

        if (!empty($searchField) && !empty($searchValue)) {
            // Pencarian berdasarkan input spesifik
            $results = $this->patientModel->searchPatientsWithRelations($searchField, $searchValue, $startDate, $endDate);
        } else {
            // Jika tidak ada input pencarian, ambil semua data berdasarkan rentang tanggal
            $results = $this->patientModel->searchPatientsWithRelations(null, null, $startDate, $endDate);
        }

        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'Data' => $results
        ];

        return view('Exam/index_pencarian', $data);
    }
}

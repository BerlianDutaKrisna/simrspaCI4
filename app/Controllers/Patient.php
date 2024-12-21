<?php
namespace App\Controllers;

use App\Models\PatientModel;

class Patient extends BaseController
{
    protected $PatientModel;

    public function __construct()
    {
        $this->PatientModel = new PatientModel();
    }

    public function index()
    {
        $data['title'] = 'Patient';
        return view('dashboard/patient', $data);
    }

    public function searchPatient()
    {
        $norm = $this->request->getPost('norm'); // Ambil data dari AJAX
        // Cari data pasien berdasarkan norm_pasien
        $patient = $this->PatientModel->where('norm_pasien', $norm)->first();
        // Jika data ditemukan, kembalikan JSON sukses
        if ($patient) {
            return $this->response->setJSON(['success' => true, 'data' => $patient]);
        }
        // Jika data tidak ditemukan, kembalikan JSON error
        return $this->response->setJSON(['success' => false, 'message' => 'Patient not found.']);
    }

    public function register_patient()
{
        // Mengambil id_user dan nama_user dari session
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');
        // Mengirim data ke view untuk ditampilkan
        return view('patient/register_patient', $data);
}
public function insert()
{
    if ($this->request->getMethod() == 'POST') {
        $rules = [
            'norm_pasien' => 'required|min_length[6]|max_length[6]|numeric',
            'nama_pasien' => 'required',
        ];

        if (!$this->validate($rules)) {
            $data['validation'] = \Config\Services::validation();
            return view('pasien/insert', $data);
        }

        $data = [
            'norm_pasien' => $this->request->getPost('norm_pasien'),
            'nama_pasien' => $this->request->getPost('nama_pasien'),
            'alamat_pasien' => $this->request->getPost('alamat_pasien'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'status_pasien' => $this->request->getPost('status_pasien'),
        ];

        $this->PatientModel->insert($data);
        session()->setFlashdata('success', 'Data pasien berhasil ditambahkan');
        return redirect()->to('/dashboard');
    }

    return view('Patient/register_patient');
}
}

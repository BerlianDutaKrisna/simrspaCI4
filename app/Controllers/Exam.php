<?php

namespace App\Controllers;

use App\Models\HpaModel;
use App\Models\PatientModel;
use Exception;

class Exam extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }

    public function register_exam()
    {
        // Inisialisasi array $data dengan nilai default dari session
        $data = [
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'patient' => null, // Default jika tidak ada data pasien ditemukan
        ];

        // Mendapatkan nilai norm_pasien dari query string
        $norm_pasien = $this->request->getGet('norm_pasien');

        // Jika nilai norm_pasien ada, cari data pasien berdasarkan norm_pasien
        if ($norm_pasien) {
            // Inisialisasi model
            $patientModel = new PatientModel();

            // Mencari data pasien berdasarkan norm_pasien
            $patient = $patientModel->where('norm_pasien', $norm_pasien)->first();

            // Menambahkan data pasien ke $data jika ditemukan
            $data['patient'] = $patient ?: null;
        }

        // Mengirim data ke view untuk ditampilkan
        return view('exam/register_exam', $data);
    }

    public function insert()
    {
        $model = new HpaModel();

        try {
            // Validasi data input hanya untuk kode_hpa
            $validation = \Config\Services::validation();
            $validation->setRules([
                'kode_hpa' => [
                    'rules' => 'required|is_unique[hpa.kode_hpa]',
                    'errors' => [
                        'required' => 'Kode Hpa harus diisi.',
                        'is_unique' => 'Kode Hpa sudah terdaftar!',
                    ],
                ],
            ]);

            // Jika validasi gagal, kembalikan ke form dengan error
            if (!$validation->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            // Ambil data dari form
            $data = [
                'kode_hpa'           => $this->request->getPost('kode_hpa'),
                'id_pasien'          => $this->request->getPost('id_pasien'),
                'unit_asal'          => $this->request->getPost('unit_asal') . ' ' . $this->request->getPost('unit_asal_detail'),
                'dokter_pengirim'    => $this->request->getPost('dokter_pengirim') . ' ' . $this->request->getPost('dokter_pengirim_custom'),
                'tanggal_permintaan' => $this->request->getPost('tanggal_permintaan') ?: null,
                'tanggal_hasil'      => $this->request->getPost('tanggal_hasil') ?: null,
                'lokasi_spesimen'    => $this->request->getPost('lokasi_spesimen'),
                'tindakan_spesimen'  => $this->request->getPost('tindakan_spesimen') . ' ' . $this->request->getPost('tindakan_spesimen_custom'),
                'diagnosa_klinik'    => $this->request->getPost('diagnosa_klinik'),
            ];

            // Simpan data menggunakan fungsi insertHpa di model
            if (!$model->insertHpa($data)) {
                throw new Exception('Gagal menyimpan data. Tidak ada perubahan pada database.');
            }

            // Redirect ke dashboard dengan pesan sukses
            return redirect()->to('/dashboard')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

}

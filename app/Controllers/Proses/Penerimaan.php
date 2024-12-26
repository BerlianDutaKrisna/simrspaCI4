<?php

namespace App\Controllers\Proses;
use App\Controllers\BaseController;
use App\Models\ProsesModel\PenerimaanModel;
use App\Models\HpaModel;
use Exception;

class Penerimaan extends BaseController
{
    public function __construct()
    {
        // Mengecek apakah user sudah login dengan menggunakan session
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Login terlebih dahulu');
            return redirect()->to('/login');
        }
    }
    public function index_penerimaan()
    {
        // Mengambil id_user dan nama_user dari session
        $penerimaanModel = new PenerimaanModel();

        // Mengambil data HPA beserta relasinya
        $penerimaanData['penerimaanData'] = $penerimaanModel->getPenerimaanWithRelations();

        // Menggabungkan data dari model dan session
        $data = [
            'penerimaanData' => $penerimaanData['penerimaanData'],
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        // Mengirim data ke view untuk ditampilkan
        return view('proses/penerimaan', $data);
    }
    public function mulai_penerimaan()
    {
        $hpaModel = new HpaModel();
        $penerimaanModel = new PenerimaanModel();

        // Ambil data id_user dari session
        $id_user = session()->get('id_user');

        // Lakukan validasi form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_proses' => [
                'rules' => 'required', // Pastikan id_proses dipilih
                'errors' => [
                    'required' => 'Pilih data terlebih dahulu.',
                ],
            ],
            'btn_proses_mulai' => [
                'rules' => 'required', // Pastikan tombol ditekan
                'errors' => [
                    'required' => 'Tombol mulai pengecekan harus diklik.',
                ],
            ],
        ]);
        // Cek jika validasi gagal
        if (!$validation->run($this->request->getPost())) {
            // Jika validasi gagal, kembali ke form dengan input yang sudah diisi
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {

            // Ambil data id_proses yang dipilih
            $selectedIds = $this->request->getPost('id_proses');
            // Pastikan selectedIds adalah array, jika tidak, buat menjadi array
            if (!is_array($selectedIds)) {
                $selectedIds = [$selectedIds]; // Jika hanya satu value, buat array
            }

            // Set zona waktu Indonesia/Jakarta
            date_default_timezone_set('Asia/Jakarta');

            // Cek jika ada id yang dipilih
            if (!empty($selectedIds)) {
                // Loop untuk setiap data yang dipilih
                foreach ($selectedIds as $id) {
                    // Pisahkan id_penerimaan dan id_hpa
                    list($id_penerimaan, $id_hpa) = explode(':', $id);
                    // Update status_hpa menjadi 'Penerimaan' pada tabel hpa menggunakan model
                    $hpaModel->updateHpa($id_hpa, [
                        'status_hpa' => 'Penerimaan', // Kirim perubahan untuk status_hpa saja
                    ]);
                    // Update data penerimaan
                    $penerimaanModel->updatePenerimaan($id_penerimaan, [
                        'id_user_penerimaan' => $id_user,  // Menggunakan id_user dari session
                        'status_penerimaan' => 'Proses Pemeriksaan', // Update status menjadi 'Proses Pemeriksaan'
                        'mulai_penerimaan' => date('Y-m-d H:i:s'), // Set tanggal dan waktu saat ini
                    ]);
                }

                // Redirect ke halaman index penerimaan
                return redirect()->to('/penerimaan/index_penerimaan');
            }
        } catch (\Exception $e) {
            // Jika ada error, kembalikan ke halaman sebelumnya
            return redirect()->to('/penerimaan/index_penerimaan');
        }
    }
}

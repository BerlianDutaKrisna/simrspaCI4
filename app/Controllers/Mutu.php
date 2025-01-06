<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HpaModel;
use App\Models\MutuModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class mutu extends BaseController
{
    protected $mutuModel;
    protected $userModel;

    public function __construct()
    {
        $this->mutuModel = new mutuModel();
    }


    public function mutu_details()
    {
        // Ambil id_mutu dari parameter GET
        $id_mutu = $this->request->getGet('id_mutu');

        if ($id_mutu) {
            // Muat model mutu
            $model = new mutuModel();

            // Ambil data mutu berdasarkan id_mutu dan relasi yang ada
            $data = $model->select(
                'mutu.*, 
                hpa.*'
            )
                ->join('hpa','mutu.id_hpa = hpa.id_hpa','left')
                ->where('mutu.id_mutu', $id_mutu)
                ->first();

            if ($data) {
                // Kirimkan data dalam format JSON
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'ID mutu tidak ditemukan.']);
        }
    }

    public function edit_mutu()
    {
        $id_mutu = $this->request->getGet('id_mutu');

        if (!$id_mutu) {
            throw new PageNotFoundException('ID mutu tidak ditemukan.');
        }

        // Ambil data mutu beserta relasi dari model getmutuWithRelations
        $mutuData = $this->mutuModel->getmutuWithRelations();

        // Filter data berdasarkan id_mutu
        $mutuData = array_filter($mutuData, function ($mutu) use ($id_mutu) {
            return $mutu['id_mutu'] == $id_mutu;
        });

        // Jika data tidak ditemukan
        if (empty($mutuData)) {
            throw new PageNotFoundException('Data mutu tidak ditemukan.');
        }

        // Ambil data pertama (karena kita filter berdasarkan id_mutu)
        $mutuData = array_values($mutuData)[0];

        // Kirimkan data ke view
        $data = [
            'mutuData' => $mutuData,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        return view('mutu/edit_mutu', $data);
    }

    public function update_mutu()
    {
        $id_mutu = $this->request->getPost('id_mutu');

        $data = [
            'indikator_1' => $this->request->getPost('indikator_1'),
            'indikator_2'  => $this->request->getPost('indikator_2'),
            'indikator_3' => $this->request->getPost('indikator_3'),
            'indikator_4'  => $this->request->getPost('indikator_4'),
            'indikator_5' => $this->request->getPost('indikator_5'),
            'indikator_6'  => $this->request->getPost('indikator_6'),
            'indikator_7' => $this->request->getPost('indikator_7'),
            'indikator_8'  => $this->request->getPost('indikator_8'),
            'indikator_9'  => $this->request->getPost('indikator_9'),
            'indikator_10'  => $this->request->getPost('indikator_10'),
            'total_nilai_mutu'  => $this->request->getPost('total_nilai_mutu'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (!$this->mutuModel->update($id_mutu, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('exam/index_exam'))->with('success', 'Data berhasil diperbarui.');
    }
}

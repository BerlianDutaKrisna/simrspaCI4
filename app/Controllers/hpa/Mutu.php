<?php

namespace App\Controllers\Hpa;

use App\Controllers\BaseController;
use App\Models\Hpa\HpaModel;
use App\Models\Hpa\Mutu_hpa;
use CodeIgniter\Exceptions\PageNotFoundException;

class mutu extends BaseController
{
    protected $hpaModel;
    protected $mutu_hpa;

    public function __construct()
    {
        $this->hpaModel = new hpaModel();
        $this->mutu_hpa = new Mutu_hpa();
    }


    public function mutu_details()
    {
        $id_mutu_hpa = $this->request->getGet('id_mutu_hpa');

        if ($id_mutu_hpa) {
            $data = $this->mutu_hpa->detailsmutu_hpa($id_mutu_hpa);

            if ($data) {
                return $this->response->setJSON($data);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Coba ulangi kembali..']);
        }
    }

    public function edit()
    {
        $id_mutu_hpa = $this->request->getGet('id_mutu_hpa');

        if (!$id_mutu_hpa) {
            throw new PageNotFoundException('ID mutu tidak ditemukan.');
        }

        // Ambil data mutu beserta relasi dari model getmutuWithRelations
        $mutuData = $this->mutu_hpa->getmutuWithRelations();

        // Filter data berdasarkan id_mutu_hpa
        $mutuData = array_filter($mutuData, function ($mutu) use ($id_mutu_hpa) {
            return $mutu['id_mutu_hpa'] == $id_mutu_hpa;
        });

        // Jika data tidak ditemukan
        if (empty($mutuData)) {
            throw new PageNotFoundException('Data mutu tidak ditemukan.');
        }

        // Ambil data pertama (karena kita filter berdasarkan id_mutu_hpa)
        $mutuData = array_values($mutuData)[0];

        // Kirimkan data ke view
        $data = [
            'mutuData' => $mutuData,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        return view('mutu/edit_mutu', $data);
    }

    public function update()
    {
        $id_mutu_hpa = $this->request->getPost('id_mutu_hpa');

        // Ambil semua nilai indikator dari POST
        $indikator = [];
        $total = 0;
        for ($i = 1; $i <= 10; $i++) {
            $nilai = (int) $this->request->getPost("indikator_$i");
            $indikator["indikator_$i"] = $nilai;
            $total += $nilai;
        }

        // Susun array data
        $data = array_merge($indikator, [
            'total_nilai_mutu_hpa' => $total,
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        if (!$this->mutu_hpa->update($id_mutu_hpa, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('mutu_hpa/edit?id_mutu_hpa=' . $id_mutu_hpa))
            ->with('success', 'Data berhasil diperbarui.');
    }
}

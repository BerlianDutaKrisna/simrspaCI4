<?php

namespace App\Controllers\Frs;

use App\Controllers\BaseController;
use App\Models\Frs\FrsModel;
use App\Models\Frs\Mutu_frs;
use CodeIgniter\Exceptions\PageNotFoundException;

class mutu extends BaseController
{
    protected $frsModel;
    protected $mutu_frs;

    public function __construct()
    {
        $this->frsModel = new frsModel();
        $this->mutu_frs = new Mutu_frs();
    }


    public function mutu_details()
    {
        $id_mutu_frs = $this->request->getGet('id_mutu_frs');

        if ($id_mutu_frs) {
            $data = $this->mutu_frs->detailsmutu_frs($id_mutu_frs);

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
        $id_mutu_frs = $this->request->getGet('id_mutu_frs');

        if (!$id_mutu_frs) {
            throw new PageNotFoundException('ID mutu tidak ditemukan.');
        }

        // Ambil data mutu beserta relasi dari model getmutuWithRelations
        $mutuData = $this->mutu_frs->getmutuWithRelations();

        // Filter data berdasarkan id_mutu_frs
        $mutuData = array_filter($mutuData, function ($mutu) use ($id_mutu_frs) {
            return $mutu['id_mutu_frs'] == $id_mutu_frs;
        });

        // Jika data tidak ditemukan
        if (empty($mutuData)) {
            throw new PageNotFoundException('Data mutu tidak ditemukan.');
        }

        // Ambil data pertama (karena kita filter berdasarkan id_mutu_frs)
        $mutuData = array_values($mutuData)[0];

        // Kirimkan data ke view
        $data = [
            'mutuData' => $mutuData,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        return view('Frs/edit_mutu', $data);
    }

    public function update()
    {
        $id_mutu_frs = $this->request->getPost('id_mutu_frs');

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
            'total_nilai_mutu_frs' => $total,
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        if (!$this->mutu_frs->update($id_mutu_frs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('mutu_frs/edit?id_mutu_frs=' . $id_mutu_frs))
            ->with('success', 'Data berhasil diperbarui.');
    }
}

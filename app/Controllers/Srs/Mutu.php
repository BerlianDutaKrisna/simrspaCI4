<?php

namespace App\Controllers\Srs;

use App\Controllers\BaseController;
use App\Models\Srs\SrsModel;
use App\Models\Srs\Mutu_srs;
use CodeIgniter\Exceptions\PageNotFoundException;

class mutu extends BaseController
{
    protected $srsModel;
    protected $mutu_srs;

    public function __construct()
    {
        $this->srsModel = new srsModel();
        $this->mutu_srs = new Mutu_srs();
    }


    public function mutu_details()
    {
        $id_mutu_srs = $this->request->getGet('id_mutu_srs');

        if ($id_mutu_srs) {
            $data = $this->mutu_srs->detailsmutu_srs($id_mutu_srs);

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
        $id_mutu_srs = $this->request->getGet('id_mutu_srs');

        if (!$id_mutu_srs) {
            throw new PageNotFoundException('ID mutu tidak ditemukan.');
        }

        // Ambil data mutu beserta relasi dari model getmutuWithRelations
        $mutuData = $this->mutu_srs->getmutuWithRelations();

        // Filter data berdasarkan id_mutu_srs
        $mutuData = array_filter($mutuData, function ($mutu) use ($id_mutu_srs) {
            return $mutu['id_mutu_srs'] == $id_mutu_srs;
        });

        // Jika data tidak ditemukan
        if (empty($mutuData)) {
            throw new PageNotFoundException('Data mutu tidak ditemukan.');
        }

        // Ambil data pertama (karena kita filter berdasarkan id_mutu_srs)
        $mutuData = array_values($mutuData)[0];

        // Kirimkan data ke view
        $data = [
            'mutuData' => $mutuData,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        return view('srs/edit_mutu', $data);
    }

    public function update()
    {
        $id_mutu_srs = $this->request->getPost('id_mutu_srs');

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
            'total_nilai_mutu_srs' => $total,
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        if (!$this->mutu_srs->update($id_mutu_srs, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('mutu_srs/edit?id_mutu_srs=' . $id_mutu_srs))
            ->with('success', 'Data berhasil diperbarui.');
    }
}

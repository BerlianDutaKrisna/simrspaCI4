<?php

namespace App\Controllers\Ihc;

use App\Controllers\BaseController;
use App\Models\Ihc\IhcModel;
use App\Models\Ihc\Mutu_ihc;
use CodeIgniter\Exceptions\PageNotFoundException;

class mutu extends BaseController
{
    protected $ihcModel;
    protected $mutu_ihc;

    public function __construct()
    {
        $this->ihcModel = new ihcModel();
        $this->mutu_ihc = new Mutu_ihc();
    }


    public function mutu_details()
    {
        $id_mutu_ihc = $this->request->getGet('id_mutu_ihc');

        if ($id_mutu_ihc) {
            $data = $this->mutu_ihc->detailsmutu_ihc($id_mutu_ihc);

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
        $id_mutu_ihc = $this->request->getGet('id_mutu_ihc');

        if (!$id_mutu_ihc) {
            throw new PageNotFoundException('ID mutu tidak ditemukan.');
        }

        // Ambil data mutu beserta relasi dari model getmutuWithRelations
        $mutuData = $this->mutu_ihc->getmutuWithRelations();

        // Filter data berdasarkan id_mutu_ihc
        $mutuData = array_filter($mutuData, function ($mutu) use ($id_mutu_ihc) {
            return $mutu['id_mutu_ihc'] == $id_mutu_ihc;
        });

        // Jika data tidak ditemukan
        if (empty($mutuData)) {
            throw new PageNotFoundException('Data mutu tidak ditemukan.');
        }

        // Ambil data pertama (karena kita filter berdasarkan id_mutu_ihc)
        $mutuData = array_values($mutuData)[0];

        // Kirimkan data ke view
        $data = [
            'mutuData' => $mutuData,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
        ];
        return view('ihc/edit_mutu', $data);
    }

    public function update()
    {
        $id_mutu_ihc = $this->request->getPost('id_mutu_ihc');

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
            'total_nilai_mutu_ihc' => $total,
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        if (!$this->mutu_ihc->update($id_mutu_ihc, $data)) {
            return redirect()->back()->with('error', 'Gagal mengupdate data.')->withInput();
        }

        return redirect()->to(base_url('mutu_ihc/edit?id_mutu_ihc=' . $id_mutu_ihc))
            ->with('success', 'Data berhasil diperbarui.');
    }
}

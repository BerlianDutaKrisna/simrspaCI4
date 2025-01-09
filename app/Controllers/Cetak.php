<?php

namespace App\Controllers;
use App\Models\HpaModel;

class Cetak extends BaseController
{
    public function cetak_form_hpa()
    {
        return view('cetak/cetak_form_hpa');
    }

    public function cetak_makroskopis($id_hpa)
    {
        $model = new HpaModel();

        // Ambil data berdasarkan id_hpa termasuk relasi
        $data = $model->getHpaWithAllRelations($id_hpa);

        if (!$data) {
            return redirect()->to('/')->with('error', 'Data tidak ditemukan.');
        }
        return view('cetak/cetak_makroskopis', ['data' => $data[0]]);
    }
}


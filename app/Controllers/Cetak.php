<?php

namespace App\Controllers;

use App\Models\HpaModel;

class Cetak extends BaseController
{
    public function cetak_form_hpa()
    {
        return view('cetak/cetak_form_hpa');
    }

    public function cetak_proses($id_hpa)
    {
        $model = new HpaModel();

        // Ambil data berdasarkan id_hpa termasuk relasi
        $data = $model->getHpaWithAllRelations($id_hpa);

        if (!$data) {
            return redirect()->to('/')->with('error', 'Data tidak ditemukan.');
        }
        return view('cetak/cetak_proses', ['data' => $data[0]]);
    }

    public function autorized($id_hpa)
    {
        session()->set('previous_url', previous_url());
        $hpaModel = new HpaModel();

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithAllRelations($id_hpa);

        // Jika hpa ditemukan, tampilkan form edit
        if ($hpa) {
            // Menggabungkan data hpa dengan session data
            $data['hpa'] = $hpa;
            // Kirimkan data ke view

            return view('cetak/autorized', $data);
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            return redirect()->back()->withInput()->with('message', [
                'error' => 'hpa tidak ditemukan.'
            ]);
        }
    }

    public function cetak_hpa($id_hpa)
    {
        session()->set('previous_url', previous_url());
        $hpaModel = new HpaModel();

        // Ambil id_user dan nama_user dari session yang sedang aktif
        $data['id_user'] = session()->get('id_user');
        $data['nama_user'] = session()->get('nama_user');

        // Ambil data hpa berdasarkan ID
        $hpa = $hpaModel->getHpaWithAllRelations($id_hpa);

        // Jika hpa ditemukan, tampilkan form edit
        if ($hpa) {
            // Menggabungkan data hpa dengan session data
            $data['hpa'] = $hpa;
            // Kirimkan data ke view

            return view('cetak/cetak_hpa', $data);
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            return redirect()->back()->withInput()->with('message', [
                'error' => 'hpa tidak ditemukan.'
            ]);
        }
    }
}

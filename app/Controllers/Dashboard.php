<?php

namespace App\Controllers;

use App\Models\HpaModel;

class Dashboard extends BaseController
{
    public function __construct()
    {
        session()->set('previous_url', previous_url());
    }

    public function index()
    {
        // Membuat instance dari HpaModel
        $hpaModel = new HpaModel();

        // Mengambil data HPA beserta relasinya
        $hpaData = $hpaModel->getHpaWithRelations();

        // Mengambil jumlah HPA yang statusnya bukan "Sudah Diproses"
        $countHpaProcessed = $hpaModel->countHpaProcessed();

        // Ambil data untuk chart berdasarkan tanggal_permintaan
        $chartData = $hpaModel->getHpaChartData();

        // Konversi data chart ke JSON agar bisa digunakan di JavaScript
        $chartDataJson = json_encode($chartData, JSON_NUMERIC_CHECK);

        // Menggabungkan data dari model dan session
        $data = [
            'hpaData' => $hpaData,
            'countHpa' => $countHpaProcessed,
            'id_user' => session()->get('id_user'),
            'nama_user' => session()->get('nama_user'),
            'chartData' => $chartDataJson // Data dalam format JSON
        ];

        // Mengirim data ke view untuk ditampilkan
        return view('dashboard/dashboard', $data);
    }
}

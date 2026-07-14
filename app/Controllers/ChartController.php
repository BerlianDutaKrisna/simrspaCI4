<?php

namespace App\Controllers;

use App\Models\HpaModel;
use CodeIgniter\Controller;

class ChartController extends Controller
{
    public function getChartData()
    {
        $model = new HpaModel();

        // Query untuk mengambil jumlah sample per bulan dari tabel `hpa`
        $data = $model->select("DATE_FORMAT(tanggal_permintaan, '%b') AS bulan, COUNT(id_hpa) AS jumlah_sample")
            ->groupBy("bulan")
            ->orderBy("FIELD(bulan, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')")
            ->findAll();

        return $this->response->setJSON($data);
    }
}


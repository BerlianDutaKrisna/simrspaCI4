<?php

namespace App\Controllers;

use App\Models\IcdoTopografiModel;
use App\Models\IcdoMorfologiModel;

class IcdoController extends BaseController
{
    protected $topografiModel;
    protected $morfologiModel;

    public function __construct()
    {
        $this->topografiModel = new IcdoTopografiModel();
        $this->morfologiModel = new IcdoMorfologiModel();
    }

    // ===============================
    // Pencarian Topografi
    // ===============================
    public function searchTopografi()
    {
        $q = $this->request->getGet('q');
        $results = $this->topografiModel->search($q);
        // tampilkan kode + nama seperti "C50 - BREAST"
        $data = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['kode_topografi'] . ' - ' . $item['nama_topografi']
            ];
        }, $results);
        return $this->response->setJSON($data);
    }

    // ===============================
    // Pencarian Morfologi
    // ===============================
    public function searchMorfologi()
    {
        $q = $this->request->getGet('q');
        $results = $this->morfologiModel->search($q);
        $data = array_map(function ($item) {
            return ['id' => $item['id'], 'text' => $item['kode_morfologi'] . ' - ' . $item['nama_morfologi']];
        }, $results);
        return $this->response->setJSON($data);
    }
}

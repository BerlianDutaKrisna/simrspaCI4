<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GambarModel;
use App\Models\HPA\HpaModel;

class Gambar extends BaseController
{
    protected $hpaModel;
    protected $gambarModel;

    public function __construct()
    {
        $this->hpaModel    = new HpaModel();
        $this->gambarModel = new GambarModel();
    }

    /* =======================
     * UPLOAD GAMBAR
     * ======================= */
    public function Upload($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        $hpa = $this->hpaModel->find($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('error', 'Data HPA tidak ditemukan.');
        }

        // Ekstraksi kode HPA
        $kode_hpa = $hpa['kode_hpa'];
        preg_match('/H\.(\d+)\/\d+/', $kode_hpa, $m);
        $kode_hpa = $m[1] ?? '000';

        // Validasi
        if (!$this->validate([
            'gambar_makroskopis_hpa' => 'uploaded[gambar_makroskopis_hpa]|ext_in[gambar_makroskopis_hpa,jpg,jpeg,png]'
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file       = $this->request->getFile('gambar_makroskopis_hpa');
        $keterangan = $this->request->getPost('keterangan_gambar_makroskopis');

        if ($file->isValid() && !$file->hasMoved()) {

            $last   = $this->gambarModel->orderBy('id_gambar', 'DESC')->first();
            $nextId = $last ? $last['id_gambar'] + 1 : 1;

            $newFileName = $kode_hpa . date('dmY') . $nextId . '.' . $file->getExtension();

            $uploadPath = ROOTPATH . 'public/uploads/hpa/gambar/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            $tempPath = $file->getTempName();
            [$w, $h]  = getimagesize($tempPath);

            if ($h > $w) {
                $image   = imagecreatefromstring(file_get_contents($tempPath));
                $rotated = imagerotate($image, -90, 0);

                in_array($file->getExtension(), ['jpg', 'jpeg'])
                    ? imagejpeg($rotated, $uploadPath . $newFileName, 90)
                    : imagepng($rotated, $uploadPath . $newFileName);

                imagedestroy($image);
                imagedestroy($rotated);
            } else {
                $file->move($uploadPath, $newFileName);
            }

            $this->gambarModel->insert([
                'id_hpa'     => $id_hpa,
                'nama_file'  => $newFileName,
                'keterangan' => $keterangan
            ]);

            return redirect()->back()->with('success', 'Gambar makroskopis berhasil diupload.');
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }

    /* =======================
     * DELETE GAMBAR
     * ======================= */
    public function delete($id_gambar)
    {
        $gambar = $this->gambarModel->find($id_gambar);
        if (!$gambar) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        $filePath = ROOTPATH . 'public/uploads/hpa/gambar/' . $gambar['nama_file'];
        if (file_exists($filePath)) unlink($filePath);

        $this->gambarModel->delete($id_gambar);

        return redirect()->back()->with('success', 'Gambar makroskopis berhasil dihapus.');
    }

    /* =======================
     * UPDATE KETERANGAN (AJAX)
     * ROUTE: Gambar/update/(:num)
     * ======================= */
    public function update($id_gambar)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $keterangan = $this->request->getPost('keterangan');

        $gambar = $this->gambarModel->find($id_gambar);
        if (!$gambar) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gambar tidak ditemukan'
            ]);
        }

        $this->gambarModel->update($id_gambar, [
            'keterangan' => $keterangan
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}

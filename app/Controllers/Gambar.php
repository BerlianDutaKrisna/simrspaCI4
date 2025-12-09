<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GambarModel;

class Gambar extends BaseController
{
    protected $hpaModel;

    public function __construct()
    {
        $this->hpaModel = new \App\Models\HPA\HpaModel();
    }

    public function UploadGambarMakroskopis($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        $gambarModel = new GambarModel();
        $hpa = $this->hpaModel->find($id_hpa);

        if (!$hpa) {
            return redirect()->back()->with('error', 'Data HPA tidak ditemukan.');
        }

        // Ekstraksi kode HPA
        $kode_hpa = $hpa['kode_hpa'];
        preg_match('/H\.(\d+)\/\d+/', $kode_hpa, $m);
        $kode_hpa = $m[1] ?? '000';

        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar_makroskopis_hpa' => [
                'rules' => 'uploaded[gambar_makroskopis_hpa]|ext_in[gambar_makroskopis_hpa,jpg,jpeg,png]|max_size[gambar_makroskopis_hpa,4096]',
                'errors' => [
                    'uploaded' => 'Harap unggah file gambar makroskopis.',
                    'ext_in' => 'File harus berformat JPG atau PNG.',
                    'max_size' => 'Ukuran file maksimal 4MB.'
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $keterangan = $this->request->getPost('keterangan_gambar_makroskopis');
        $file = $this->request->getFile('gambar_makroskopis_hpa');

        if ($file->isValid() && !$file->hasMoved()) {

            // Ambil ID terakhir untuk penamaan unik
            $last = $gambarModel->orderBy('id_gambar', 'DESC')->first();
            $nextId = $last ? $last['id_gambar'] + 1 : 1;

            // Buat nama file
            $newFileName = $kode_hpa . date('dmY') . $nextId . '.' . $file->getExtension();

            // Lokasi upload
            $uploadPath = ROOTPATH . 'public/uploads/hpa/gambar/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            $tempPath  = $file->getTempName();
            $finalPath = $uploadPath . $newFileName;

            // Cek orientasi gambar
            list($w, $h) = getimagesize($tempPath);

            if ($h > $w) {
                // Rotasi portrait ke landscape
                $image = imagecreatefromstring(file_get_contents($tempPath));
                $rotated = imagerotate($image, -90, 0);

                if (in_array($file->getExtension(), ['jpg', 'jpeg'])) {
                    imagejpeg($rotated, $finalPath, 90);
                } else {
                    imagepng($rotated, $finalPath);
                }

                imagedestroy($image);
                imagedestroy($rotated);
            } else {
                // Landscape → langsung pindahkan
                $file->move($uploadPath, $newFileName);
            }

            // Simpan ke database
            $gambarModel->insert([
                'id_hpa'     => $id_hpa,
                'nama_file'  => $newFileName,
                'keterangan' => $keterangan,
            ]);

            return redirect()->back()->with('success', 'Gambar makroskopis berhasil diupload.');
        }

        return redirect()->back()->with('error', 'File tidak valid atau tidak diunggah.');
    }

    public function delete($id_gambar)
    {
        $gambarModel = new GambarModel();

        // Ambil data berdasarkan ID
        $gambar = $gambarModel->find($id_gambar);
        if (!$gambar) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        // Path ke file fisik
        $filePath = ROOTPATH . 'public/uploads/hpa/gambar/' . $gambar['nama_file'];

        // Hapus file jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus dari database
        $gambarModel->delete($id_gambar);

        return redirect()->back()->with('success', 'Gambar makroskopis berhasil dihapus.');
    }
}

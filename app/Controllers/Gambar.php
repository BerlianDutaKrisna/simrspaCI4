<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GambarModel;

class Gambar extends BaseController
{
    public function uploadGambarMakroskopis($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        $gambarModel = new \App\Models\GambarModel();
        $hpa = $this->hpaModel->find($id_hpa);

        if (!$hpa) {
            return redirect()->back()->with('error', 'Data HPA tidak ditemukan.');
        }

        // Ambil kode HPA lalu ekstrak angkanya: H.123/25 → 123
        $kode_hpa = $hpa['kode_hpa'];
        preg_match('/H\.(\d+)\/\d+/', $kode_hpa, $matches);
        $kode_hpa = isset($matches[1]) ? $matches[1] : '000';

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar_makroskopis_hpa' => [
                'rules' => 'uploaded[gambar_makroskopis_hpa]|ext_in[gambar_makroskopis_hpa,jpg,jpeg,png]',
                'errors' => [
                    'uploaded' => 'Harap unggah file gambar makroskopis.',
                    'ext_in' => 'File harus berformat JPG atau PNG.',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $keterangan = $this->request->getPost('keterangan_gambar_makroskopis');
        $file = $this->request->getFile('gambar_makroskopis_hpa');

        if ($file->isValid() && !$file->hasMoved()) {

            // Hitung ID gambar berikutnya (untuk penamaan unik)
            $last = $gambarModel->orderBy('id_gambar', 'DESC')->first();
            $nextId = $last ? $last['id_gambar'] + 1 : 1;

            // Format nama file: {kode_hpa}{tanggal}{nextId}.{ext}
            $newFileName = $kode_hpa . date('dmY') . $nextId . '.' . $file->getExtension();

            // Folder tujuan
            $uploadPath = ROOTPATH . 'public/uploads/hpa/gambar/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $tempPath  = $file->getTempName();
            $finalPath = $uploadPath . $newFileName;

            // Cek orientasi gambar
            list($width, $height) = getimagesize($tempPath);

            if ($height > $width) {
                // Rotasi portrait → landscape
                $image = imagecreatefromstring(file_get_contents($tempPath));
                $rotatedImage = imagerotate($image, -90, 0);

                if ($file->getExtension() === 'jpg' || $file->getExtension() === 'jpeg') {
                    imagejpeg($rotatedImage, $finalPath, 90);
                } else {
                    imagepng($rotatedImage, $finalPath);
                }
            } else {
                // Landscape → langsung simpan
                move_uploaded_file($tempPath, $finalPath);
            }

            // Simpan ke database tabel "gambar"
            $gambarModel->insert([
                'id_hpa'     => $id_hpa,
                'nama_file'  => $newFileName,
                'keterangan' => $keterangan,
            ]);

            return redirect()->back()->with('success', 'Gambar makroskopis berhasil diupload.');
        }

        return redirect()->back()->with('error', 'File tidak valid atau tidak diunggah.');
    }
}

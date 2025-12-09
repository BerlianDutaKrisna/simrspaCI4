<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FotoMakroskopisModel;

class FotoMakroskopis extends BaseController
{
    protected $fotoMakroModel;
    protected $hpaModel;

    public function __construct()
    {
        $this->fotoMakroModel = new FotoMakroskopisModel();
        $this->hpaModel       = new \App\Models\HPA\HpaModel();
    }

    public function upload($id_hpa)
    {
        date_default_timezone_set('Asia/Jakarta');

        $hpa = $this->hpaModel->find($id_hpa);
        if (!$hpa) {
            return redirect()->back()->with('error', 'Data HPA tidak ditemukan.');
        }

        // Ekstrak angka dari kode HPA
        preg_match('/H\.(\d+)\/\d+/', $hpa['kode_hpa'], $matches);
        $kode_hpa = $matches[1] ?? '000';

        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar_makroskopis_hpa' => [
                'rules'  => 'uploaded[gambar_makroskopis_hpa]|ext_in[gambar_makroskopis_hpa,jpg,jpeg,png]|max_size[gambar_makroskopis_hpa,4096]',
                'errors' => [
                    'uploaded' => 'Harap unggah file foto makroskopis.',
                    'ext_in'   => 'File harus berformat JPG atau PNG.',
                    'max_size' => 'Ukuran file maksimal 4MB.'
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $keterangan = $this->request->getPost('keterangan_foto_makroskopis');
        $file       = $this->request->getFile('gambar_makroskopis_hpa');

        if ($file->isValid() && !$file->hasMoved()) {

            // Ambil ID terakhir untuk penamaan file
            $last   = $this->fotoMakroModel->orderBy('id_foto_makroskopis', 'DESC')->first();
            $nextId = $last ? $last['id_foto_makroskopis'] + 1 : 1;

            $newFileName = $kode_hpa . date('dmY') . $nextId . '.' . $file->getExtension();

            // Folder upload
            $uploadPath = ROOTPATH . 'public/uploads/hpa/makroskopis/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $tempPath  = $file->getTempName();
            $finalPath = $uploadPath . $newFileName;

            // Cek orientasi gambar
            list($w, $h) = getimagesize($tempPath);

            if ($h > $w) {
                // Rotasi portrait → landscape
                $image = imagecreatefromstring(file_get_contents($tempPath));
                $rotated = imagerotate($image, -90, 0);

                if (in_array($file->getExtension(), ['jpg', 'jpeg'])) {
                    imagejpeg($rotated, $finalPath, 90);
                } else {
                    imagepng($rotated, $finalPath);
                }

                // Bersihkan memory
                imagedestroy($image);
                imagedestroy($rotated);
            } else {
                // Landscape → langsung pindahkan
                $file->move($uploadPath, $newFileName);
            }

            // Simpan ke database
            $this->fotoMakroModel->insert([
                'id_hpa'     => $id_hpa,
                'nama_file'  => $newFileName,
                'keterangan' => $keterangan,
            ]);

            return redirect()->back()->with('success', 'Foto makroskopis berhasil diupload.');
        }

        return redirect()->back()->with('error', 'File tidak valid atau gagal diupload.');
    }


    public function delete($id_foto)
    {
        $foto = $this->fotoMakroModel->find($id_foto);
        if (!$foto) {
            return redirect()->back()->with('error', 'Foto tidak ditemukan.');
        }

        // Hapus file fisik
        $path = ROOTPATH . 'public/uploads/hpa/makroskopis/' . $foto['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }

        // Hapus record DB
        $this->fotoMakroModel->delete($id_foto);

        return redirect()->back()->with('success', 'Foto makroskopis berhasil dihapus.');
    }
}

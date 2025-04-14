<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $uri = ltrim(service('uri')->getPath(), '/'); // Menghapus slash awal jika ada

        // Daftar halaman yang dikecualikan dari filter
        $excludedRoutes = [
            'hpa/index_buku_penerima',
            'frs/index_buku_penerima',
            'srs/index_buku_penerima',
            'ihc/index_buku_penerima',
        ];

        // Jika URI saat ini ada dalam daftar pengecualian, izinkan akses
        if (in_array($uri, $excludedRoutes, true)) {
            return;
        }

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah id_user dan nama_user ada dalam sesi
        if (!$session->has('id_user') || !$session->has('nama_user')) {
            log_message('error', 'Data sesi hilang: id_user atau nama_user');
            return redirect()->to('/')->with('error', 'Sesi Anda telah berakhir, silakan login kembali.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request diproses
    }
}

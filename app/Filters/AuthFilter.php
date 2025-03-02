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

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah id_user dan nama_user ada dalam sesi
        if (is_null($session->get('id_user')) || is_null($session->get('nama_user'))) {
            log_message('error', 'Data sesi hilang: id_user atau nama_user');
            return redirect()->to('/')->with('error', 'Sesi Anda telah berakhir, silakan login kembali.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request diproses
    }
}

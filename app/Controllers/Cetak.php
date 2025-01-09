<?php

namespace App\Controllers;

class Cetak extends BaseController
{
    public function cetak_form_hpa()
    {
        return view('cetak/cetak_form_hpa');
    }
    public function cetak_makroskopis()
    {
        return view('cetak/cetak_makroskopis');
    }
}


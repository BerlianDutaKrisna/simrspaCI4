<?php

namespace App\Controllers\Fnab;

use App\Controllers\BaseController;
use App\Models\Fnab\FnabModel;
use App\Models\UsersModel;
use App\Models\PatientModel;
use App\Models\ProsesModel\PenerimaanModel;
use App\Models\ProsesModel\PembacaanModel;
use App\Models\ProsesModel\PenulisanModel;
use App\Models\ProsesModel\PemverifikasiModel;
use App\Models\ProsesModel\AutorizedModel;
use App\Models\ProsesModel\PencetakanModel;
use App\Models\MutuModel;
use Exception;

class Fnab extends BaseController
{
    protected $fnabModel;
    protected $usersModel;
    protected $patientModel;
    protected $penerimaanModel;
    protected $pembacaanModel;
    protected $penulisanModel;
    protected $pemverifikasiModel;
    protected $autorizedModel;
    protected $pencetakanModel;
    protected $mutuModel;

    public function __construct()
    {
        $this->fnabModel = new FnabModel();
        $this->usersModel = new UsersModel();
        $this->patientModel = new PatientModel();
        $this->penerimaanModel = new PenerimaanModel();
        $this->pembacaanModel = new PembacaanModel();
        $this->penulisanModel = new PenulisanModel();
        $this->pemverifikasiModel = new PemverifikasiModel();
        $this->autorizedModel = new AutorizedModel();
        $this->pencetakanModel = new PencetakanModel();
        $this->mutuModel = new MutuModel();
    }
    
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class IcdoTopografiModel extends Model
{
    protected $table            = 'icdo_topografi';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'kode_topografi',
        'nama_topografi',
    ];

    // ❌ tidak pakai timestamps (sesuai migration)
    protected $useTimestamps = false;

    // ==============================
    // Custom helper methods (opsional tapi kepake)
    // ==============================

    /**
     * Ambil semua data berdasarkan kode induk
     * contoh: C00 → C00.0, C00.1, dst
     */
    public function getByKodeInduk(string $kode)
    {
        return $this->like('kode_topografi', $kode, 'after')
            ->orderBy('kode_topografi', 'ASC')
            ->findAll();
    }

    /**
     * Cari berdasarkan nama atau kode (autocomplete)
     */
    public function search(string $keyword)
    {
        return $this->groupStart()
            ->like('kode_topografi', $keyword)
            ->orLike('nama_topografi', $keyword)
            ->groupEnd()
            ->orderBy('kode_topografi', 'ASC')
            ->findAll();
    }
}

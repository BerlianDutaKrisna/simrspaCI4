<?php

namespace App\Models;

use CodeIgniter\Model;

class IcdoMorfologiModel extends Model
{
    protected $table            = 'icdo_morfologi';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'kode_morfologi',
        'nama_morfologi',
    ];

    // Tidak pakai created_at / updated_at
    protected $useTimestamps = false;

    // ==============================
    // Custom methods (praktis & kepake)
    // ==============================

    /**
     * Cari morfologi berdasarkan kode
     * contoh: 8140, 8140/3
     */
    public function getByKode(string $kode)
    {
        return $this->like('kode_morfologi', $kode, 'after')
            ->orderBy('kode_morfologi', 'ASC')
            ->findAll();
    }

    /**
     * Pencarian bebas (kode / nama)
     * cocok untuk autocomplete
     */
    public function search(string $keyword)
    {
        return $this->groupStart()
            ->like('kode_morfologi', $keyword)
            ->orLike('nama_morfologi', $keyword)
            ->groupEnd()
            ->orderBy('kode_morfologi', 'ASC')
            ->findAll();
    }
}

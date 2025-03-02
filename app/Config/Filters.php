<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     *
     * [filter_name => classname]
     * or [filter_name => [classname1, classname2, ...]]
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth' => \App\Filters\AuthFilter::class,
    ];

    /**
     * List of special required filters.
     *
     * The filters listed here are special. They are applied before and after
     * other kinds of filters, and always applied even if a route does not exist.
     *
     * Filters set by default provide framework functionality. If removed,
     * those functions will no longer work.
     *
     * @see https://codeigniter.com/user_guide/incoming/filters.html#provided-filters
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Force Global Secure Requests
            'pagecache',  // Web Page Caching
        ],
        'after' => [
            'pagecache',   // Web Page Caching
            'performance', // Performance Metrics
            'toolbar',     // Debug Toolbar
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'POST' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'dashboard',        // Proteksi akses ke dashboard
                'users/*',          // Proteksi semua endpoint dalam users
                'patient/*',        // Proteksi semua endpoint dalam patient
                'hpa/*',            // Proteksi semua endpoint dalam hpa
                'frs/*',            // Proteksi semua endpoint dalam frs
                'penerimaan_hpa/*', // Proteksi semua endpoint dalam penerimaan_hpa
                'penerimaan_frs/*', // Proteksi semua endpoint dalam penerimaan_frs
                'pengirisan/*',     // Proteksi semua endpoint dalam pengirisan
                'pemotongan/*',     // Proteksi semua endpoint dalam pemotongan
                'pemprosesan/*',    // Proteksi semua endpoint dalam pemprosesan
                'penanaman/*',      // Proteksi semua endpoint dalam penanaman
                'pemotongan_tipis/*', // Proteksi semua endpoint dalam pemotongan tipis
                'pewarnaan/*',      // Proteksi semua endpoint dalam pewarnaan
                'pembacaan/*',      // Proteksi semua endpoint dalam pembacaan
                'penulisan/*',      // Proteksi semua endpoint dalam penulisan
                'pemverifikasi/*',  // Proteksi semua endpoint dalam pemverifikasi
                'autorized/*',      // Proteksi semua endpoint dalam autorized
                'pencetakan/*',     // Proteksi semua endpoint dalam pencetakan
                'mutu/*',           // Proteksi semua endpoint dalam mutu
                'cetak/*',          // Proteksi semua endpoint dalam cetak
                'laporan_jumlah_pasien', // Proteksi akses laporan
            ]
        ]
    ];
}

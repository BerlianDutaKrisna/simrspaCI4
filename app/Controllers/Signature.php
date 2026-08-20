<?php

namespace App\Controllers;

use App\Models\SignatureModel;
use CodeIgniter\RESTful\ResourceController;

class Signature extends ResourceController
{
    protected $modelName = SignatureModel::class;
    protected $format    = 'json';

    public function save()
{
    $data = $this->request->getJSON(true);

    if (!$data) {
        return $this->fail('Data kosong');
    }

    // =========================
    // 1. NORMALISASI DATA KOSONG
    // =========================
    $fieldsToClean = [
        'dokter_pelaksana',
        'pemberi_informasi',
        'hubungan_dengan_pasien',
        'nama_hubungan_pasien'
    ];

    foreach ($fieldsToClean as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '' || strpos($data[$field], '____') !== false) {
            $data[$field] = "";
        }
    }

    // =========================
    // 2. CEK EXISTING DATA
    // =========================
    // Jika ada struktur formrm_json sebagai array/object, simpan sebagai JSON string
    if (isset($data['formrm_json'])) {
        // Extract pasien signature from nested payload if present
        $sig = null;
        if (is_array($data['formrm_json'])) {
            if (isset($data['formrm_json']['informed_data']['consentSignaturePersetujuan'])) {
                $sig = $data['formrm_json']['informed_data']['consentSignaturePersetujuan'];
            } elseif (isset($data['formrm_json']['persetujuan_data']['consentSignaturePersetujuan'])) {
                $sig = $data['formrm_json']['persetujuan_data']['consentSignaturePersetujuan'];
            }
        } elseif (is_string($data['formrm_json'])) {
            $decoded = json_decode($data['formrm_json'], true);
            if (isset($decoded['informed_data']['consentSignaturePersetujuan'])) {
                $sig = $decoded['informed_data']['consentSignaturePersetujuan'];
            } elseif (isset($decoded['persetujuan_data']['consentSignaturePersetujuan'])) {
                $sig = $decoded['persetujuan_data']['consentSignaturePersetujuan'];
            }
        }

        if ($sig) {
            $data['concentSignaturePasien'] = $sig;
        }

        // ensure stored as JSON string
        if (is_array($data['formrm_json'])) {
            $data['formrm_json'] = json_encode($data['formrm_json']);
        }
    }

    // =========================
    // 2.a MAP FORM FIELDS KE NAMA KOLom DB
    // =========================
    // Map common form field names to DB columns if present
    if (isset($data['norm_pasien'])) {
        $data['norm'] = $data['norm_pasien'];
    }
    if (isset($data['nama_pasien'])) {
        $data['nama'] = $data['nama_pasien'];
    }
    if (isset($data['tanggal_lahir_pasien'])) {
        $data['tgl_lahir'] = $data['tanggal_lahir_pasien'];
    }
    if (isset($data['jenis_kelamin_pasien'])) {
        $data['jenis_kelamin'] = $data['jenis_kelamin_pasien'];
    }
    if (isset($data['alamat_pasien'])) {
        $data['alamat'] = $data['alamat_pasien'];
    }

    // Jika ada formrm_json, ambil beberapa nilai dari struktur JSON
    $decodedJson = null;
    if (!empty($data['formrm_json'])) {
        $decodedJson = json_decode($data['formrm_json'], true);
        if (is_array($decodedJson)) {
            // diagnosis_kerja
            if (empty($data['diagnosis_kerja']) && isset($decodedJson['informed_data']['diagnosis_kerja'])) {
                $data['diagnosis_kerja'] = $decodedJson['informed_data']['diagnosis_kerja'];
            }

            // hubungan / nama penandatangan
            if (empty($data['hubungan_dengan_pasien']) && isset($decodedJson['persetujuan_data']['persetujuan_hubungan'])) {
                $data['hubungan_dengan_pasien'] = $decodedJson['persetujuan_data']['persetujuan_hubungan'];
            }
            if (empty($data['nama_hubungan_pasien']) && isset($decodedJson['persetujuan_data']['persetujuan_nama'])) {
                $data['nama_hubungan_pasien'] = $decodedJson['persetujuan_data']['persetujuan_nama'];
            }

            // dateTimeSignature dari consentDatePersetujuan (format ISO T)
            if (empty($data['dateTimeSignature'])) {
                $consent = $decodedJson['informed_data']['consentDatePersetujuan'] ?? $decodedJson['persetujuan_data']['consentDatePersetujuan'] ?? null;
                if ($consent) {
                    $data['dateTimeSignature'] = str_replace('T', ' ', $consent);
                }
            }

            // dokter/petugas/pemberi informasi
            if (empty($data['dokter_pelaksana']) && isset($decodedJson['informed_data']['inform_nama'])) {
                $data['dokter_pelaksana'] = $decodedJson['informed_data']['inform_nama'];
            }
            if (empty($data['petugas_pelaksana']) && isset($decodedJson['informed_data']['pegawai_nama'])) {
                $data['petugas_pelaksana'] = $decodedJson['informed_data']['pegawai_nama'];
            }
            if (empty($data['pemberi_informasi']) && isset($decodedJson['informed_data']['inform_nama'])) {
                $data['pemberi_informasi'] = $decodedJson['informed_data']['inform_nama'];
            }

            // pasien fields inside formrm_json top-level
            if (isset($decodedJson['nama']) && empty($data['nama'])) {
                $data['nama'] = $decodedJson['nama'];
            }
            if (isset($decodedJson['no_rm']) && empty($data['norm'])) {
                $data['norm'] = $decodedJson['no_rm'];
            }
            if (isset($decodedJson['tgl_lahir']) && empty($data['tgl_lahir'])) {
                $data['tgl_lahir'] = $decodedJson['tgl_lahir'];
            }
            if (isset($decodedJson['alamat']) && empty($data['alamat'])) {
                $data['alamat'] = $decodedJson['alamat'];
            }
        }
    }

    $idTrans = $data['id_transaksi'] ?? null;

    $existing = null;
    if (!empty($idTrans)) {
        $existing = $this->model->where('id_transaksi', $idTrans)->first();
    }

    if ($existing && !empty($existing['id'])) {
        // UPDATE by primary key to ensure model handles allowedFields correctly
        $this->model->update($existing['id'], $data);
        $action = 'update';
    } else {
        // INSERT
        $this->model->insert($data);
        $action = 'insert';
    }

     // =========================
        // 3. KIRIM KE SIMRS
        // =========================
        $simrsStatus = false;
        $simrsResponse = null;
        $simrsError = null;

        try {

            $client = \Config\Services::curlrequest();

            $response = $client->post(
                'http://10.250.10.107/apibdrs/apibdrs/postInformed',
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode($data),

                    // agar response 500 tetap bisa dibaca
                    'http_errors' => false
                ]
            );

            $simrsStatus = $response->getStatusCode() >= 200
                        && $response->getStatusCode() < 300;

            $simrsResponse = json_decode($response->getBody(), true);

            if ($simrsResponse === null) {
                $simrsResponse = $response->getBody();
            }

            log_message(
                'info',
                '[SIMRS] HTTP ' . $response->getStatusCode() .
                ' RESPONSE: ' . $response->getBody()
            );

        } catch (\Exception $e) {

            $simrsError = $e->getMessage();

            log_message(
                'error',
                '[SIMRS] Gagal kirim: ' . $simrsError
            );
        }

        // =========================
        // 4. RESPONSE KE BROWSER
        // =========================
        return $this->respond([
            'status' => true,
            'action' => $action,
            'message' => $action === 'update'
                ? 'Data berhasil diupdate'
                : 'Data berhasil disimpan',

            'simrs' => [
                'success' => $simrsStatus,
                'response' => $simrsResponse,
                'error' => $simrsError
            ]
        ]);
    }
}
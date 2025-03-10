<?php

namespace Hitaaksata\Bpjs\Antrian;

use Hitaaksata\Bpjs\BpjsService;

/**
 * Class ReferensiService
 *
 * This class provides methods to interact with the BPJS API for various reference-related functionalities.
 */
class ReferensiService extends BpjsService
{
    /**
     * Mengambil daftar poli dari BPJS
     *
     * @return array The response from the BPJS API.
     */
    public function getPoli(): array
    {
        $response = $this->get('ref/poli');

        // Pastikan response valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Mengambil daftar dokter berdasarkan kode poli dan tanggal
     *
     * @param string $kodePoli The polyclinic code.
     * @param string $tanggal The date in YYYY-MM-DD format.
     * @return array The response from the BPJS API.
     */
    public function getDokter(string $kodePoli, string $tanggal): array
    {
        $response = $this->get('ref/jadwaldokter/kodepoli/'.$kodePoli.'/'.$tanggal);

        // Pastikan response valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Mengambil jadwal dokter berdasarkan kode poli dan tanggal
     *
     * @param string $kodepoli The polyclinic code.
     * @param string $tanggal The date in YYYY-MM-DD format.
     * @return array The response from the BPJS API.
     */
    public function getJadwalDokter(string $kodepoli, string $tanggal): array
    {
        //validasi tanggal
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return ['error' => true, 'message' => 'Invalid date format'];
        }

        $response = $this->get('jadwaldokter/kodepoli/'.$kodepoli.'/tanggal/'.$tanggal);

        // Pastikan response valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Mengambil data pasien berdasarkan NIK
     *
     * @param string $nik The patient's NIK (National Identification Number).
     * @return array The response from the BPJS API.
     */
    public function getPasienFP(string $nik): array
    {
        $response = $this->get('ref/pasien/fp/identitas/'.$nik.'/noidentitas/'.$nik);

        // Pastikan response valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }
}
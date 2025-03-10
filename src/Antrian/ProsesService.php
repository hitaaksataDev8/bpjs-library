<?php

namespace Hitaaksata\Bpjs\Antrian;

use Hitaaksata\Bpjs\BpjsService;

class ProsesService extends BpjsService
{
    /**
     * Update the doctor's schedule.
     *
     * @param array $data The data to update the schedule.
     * @return array The response from the BPJS API.
     */
    public function updateJadwalDokter(array $data): array
    {
        if (!is_array($data) || empty($data)) {
            return ['error' => true, 'message' => 'Invalid data'];
        }

        if (!isset($data['kodepoli'])) {
            return ['error' => true, 'message' => 'Missing kodepoli'];
        }
        if (!isset($data['kodesubspesialis'])) {
            return ['error' => true, 'message' => 'Missing kodesubspesialis'];
        }
        if (!isset($data['kodedokter'])) {
            return ['error' => true, 'message' => 'Missing kodedokter'];
        }
        if (!isset($data['jadwal'])) {
            return ['error' => true, 'message' => 'Missing jadwal'];
        }

        $response = $this->post('jadwaldokter/updatejadwaldokter', $data);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Add a new queue.
     *
     * @param array $data The data to add the queue.
     * @return array The response from the BPJS API.
     */
    public function tambahAntrian(array $data)
    {
        if (!is_array($data) || empty($data)) {
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $requiredKeys = [
            'kodebooking', 'jenispasien', 'nomorkartu', 'nik', 'nohp', 'kodepoli', 'namapoli',
            'pasienbaru', 'norm', 'tanggalperiksa', 'kodedokter', 'namadokter', 'jampraktek',
            'jeniskunjungan', 'nomorreferensi', 'nomorantrean', 'angkaantrean', 'estimasidilayani',
            'sisakuotajkn', 'kuotajkn', 'sisakuotanonjkn', 'kuotanonjkn', 'keterangan'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                return ['error' => true, 'message' => "Missing $key"];
            }
        }

        $response = $this->post('antrean/add', $data);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Add a new pharmacy queue.
     *
     * @param array $data The data to add the pharmacy queue.
     * @return array The response from the BPJS API.
     */
    public function tambahAntrianFarmasi(array $data)
    {
        if (!is_array($data) || empty($data)) {
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $requiredKeys = [
            'kodebooking', 'jenisresep', 'nomorantrean', 'keterangan'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                return ['error' => true, 'message' => "Missing $key"];
            }
        }

        $response = $this->post('antrean/farmasi/add', $data);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Update the queue time.
     *
     * @param array $data The data to update the queue time.
     * @return array The response from the BPJS API.
     */
    public function updateWaktuAtrian(array $data)
    {
        if (!is_array($data) || empty($data)) {
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $requiredKeys = [
            'kodebooking', 'jenisresep', 'waktu'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                return ['error' => true, 'message' => "Missing $key"];
            }
        }

        if (!isset($data['taskid'])) {
            return ['error' => true, 'message' => 'Missing taskid'];
        } else {
            // Convert to int
            $data['taskid'] = (int) $data['taskid'];
        }

        $response = $this->post('antrean/updatewaktu', $data);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Cancel the queue.
     *
     * @param string $kodebooking The booking code.
     * @param string $keterangan The reason for cancellation.
     * @return array The response from the BPJS API.
     */
    public function batalAtrian(string $kodebooking, string $keterangan): array
    {
        if (($kodebooking == "" || $kodebooking == null) || ($keterangan == "" || $keterangan == null)) {
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $data = [
            'kodebooking' => $kodebooking,
            'keterangan' => $keterangan
        ];

        $response = $this->post('antrean/batal', $data);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }
}
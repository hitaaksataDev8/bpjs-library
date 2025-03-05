<?php

namespace Hitaaksata\Bpjs\Antrian;

use Hitaaksata\Bpjs\BpjsService;

class ReferensiService extends BpjsService
{
    /**
     * Mengambil daftar poli dari BPJS
     *
     * @return array
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
}
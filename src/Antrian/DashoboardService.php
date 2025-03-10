<?php

namespace Hitaaksata\Bpjs\Antrian;

use Hitaaksata\Bpjs\BpjsService;

/**
 * Class DashoboardService
 *
 * This class provides methods to interact with the BPJS API for various dashboard and queue-related functionalities.
 */
class DashoboardService extends BpjsService
{
    /**
     * Get the list of tasks by booking code.
     *
     * @param string $kodeBooking The booking code.
     * @return array The response from the BPJS API.
     */
    public function getListWaktuTaskID(string $kodeBooking){
        if($kodeBooking =="" || is_null($kodeBooking)){
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $data = [
            'kodebooking' => $kodeBooking
        ];

        $response = $this->post('antrean/getlisttask', $data);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Get the dashboard data for a specific date.
     *
     * @param string $tanggal The date in YYYY-MM-DD format.
     * @param string $waktu The time context (default is 'server').
     * @return array The response from the BPJS API.
     */
    public function getDashboardPertanggal($tanggal, string $waktu = 'server'){
        // Validate the date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) || $tanggal == "" || is_null($tanggal)) {
            return ['error' => true, 'message' => 'Invalid date format'];
        }

        $response = $this->get('dashboard/waktutunggu/tanggal/'.$tanggal.'/waktu/'.$waktu);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Get the dashboard data for a specific month and year.
     *
     * @param string $bulan The month in MM format.
     * @param string $tahun The year in YYYY format.
     * @param string $waktu The time context (default is 'server').
     * @return array The response from the BPJS API.
     */
    public function getDashboardPerbulan(string $bulan, string $tahun, string $waktu ='server'){
        // Validate the month and year format
        if (!preg_match('/^\d{2}$/', $bulan) || !preg_match('/^\d{4}$/', $tahun) || $bulan == "" || $tahun == "" || is_null($bulan) || is_null($tahun)) {
            return ['error' => true, 'message' => 'Invalid date format'];
        }

        $response = $this->get('dashboard/waktutunggu/bulan/'.$bulan.'/tahun/'.$tahun.'/waktu/'.$waktu);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Get the queue data for a specific date.
     *
     * @param string $tanggal The date in YYYY-MM-DD format.
     * @return array The response from the BPJS API.
     */
    public function getAntrianPertanggal(string $tanggal)
    {
        // Validate the date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) || $tanggal == "" || is_null($tanggal)) {
            return ['error' => true, 'message' => 'Invalid date format'];
        }

        $response = $this->get('antrean/pendaftaran/tanggal/'.$tanggal);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Get the queue data by booking code.
     *
     * @param string $kodebooking The booking code.
     * @return array The response from the BPJS API.
     */
    public function getAntrianPerKodeBooking(string $kodebooking){
        // Validate the booking code
        if($kodebooking == "" || is_null($kodebooking)){
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $response = $this->get('antrean/pendaftaran/kodebooking/'.$kodebooking);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Get the list of queues that have not been served yet.
     *
     * @return array The response from the BPJS API.
     */
    public function getAntrianBelumDilayani()
    {
        $response = $this->get('antrean/pendaftaran/aktif');

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

    /**
     * Get the detailed list of queues that have not been served yet for a specific doctor and schedule.
     *
     * @param string $kodepoli The polyclinic code.
     * @param string $kodedokter The doctor code.
     * @param string $hari The day.
     * @param string $jampraktek The practice hours.
     * @return array The response from the BPJS API.
     */
    public function getAntrianBelumDilayaniDetail(string $kodepoli, string $kodedokter, string $hari, string $jampraktek){
        // Validate the polyclinic code
        if($kodepoli == "" || is_null($kodepoli)){
            return ['error' => true, 'message' => 'Invalid data'];
        }

        // Validate the doctor code
        if($kodedokter == "" || is_null($kodedokter)){
            return ['error' => true, 'message' => 'Invalid data'];
        }

        // Validate the day
        if($hari == "" || is_null($hari)){
            return ['error' => true, 'message' => 'Invalid data'];
        }

        // Validate the practice hours
        if($jampraktek == "" || is_null($jampraktek)){
            return ['error' => true, 'message' => 'Invalid data'];
        }

        $response = $this->get('antrean/pendaftaran/aktif/detail/kodepoli/'.$kodepoli.'/kodedokter/'.$kodedokter.'/hari/'.$hari.'/jampraktek/'.$jampraktek);

        // Ensure the response is valid
        if (!is_array($response) || !isset($response['status'])) {
            return ['error' => true, 'message' => 'Invalid response from BPJS'];
        }

        return $response;
    }

}
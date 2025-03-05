<?php

namespace Hitaaksata\Bpjs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class BpjsService
 *
 * Kelas ini digunakan untuk berkomunikasi dengan layanan BPJS melalui API.
 * Menggunakan GuzzleHttp untuk melakukan HTTP request dengan autentikasi yang sesuai.
 *
 * @package Hitaaksata\Bpjs
 */
class BpjsService
{
       /**
     * Guzzle HTTP Client object
     * @var \GuzzleHttp\Client
     */
    private Client $clients;

    /**
     * Request headers
     * @var array
     */
    private array $headers = [];

    /**
     * X-cons-id header value
     * @var string
     */
    private string $cons_id = '';

    /**
     * X-Timestamp header value
     * @var string
     */
    private string $timestamp = '';

    /**
     * X-Signature header value
     * @var string
     */
    private string $signature = '';

    /**
     * @var string
     */
    private string $secret_key = '';

    /**
     * @var string
     */
    private string $base_url = '';

    /**
     * @var string
     */
    private string $user_key = '';

    /**
     * @var string Nama layanan (misal: "vclaim", "antrean")
     */
    private string $service_name='';

    /**
     * Constructor untuk inisialisasi layanan BPJS.
     *
     * @param array $configurations Konfigurasi BPJS (cons_id, secret_key, base_url, user_key, service_name)
     * @param Client|null $client Dependency Injection untuk HTTP Client (default: Guzzle Client)
     */
    public function __construct(array $configurations, ?Client $client = null)
    {
        foreach ($configurations as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        // Gunakan dependency injection untuk fleksibilitas
        $this->clients = new Client(['verify' => false]);

        $this->setTimestamp();
        $this->setSignature();
        $this->setHeaders();
    }

    /**
     * Mengatur header untuk setiap permintaan API BPJS.
     */
    private function setHeaders(): void
    {
       
        $this->headers = [
            'X-cons-id'   => $this->cons_id,
            'X-Timestamp' => $this->timestamp,
            'X-Signature' => $this->signature,
            'user_key'    => $this->user_key,
            'Accept'      => 'application/json',
        ];
    }

    /**
     * Menghitung timestamp saat ini dalam format UNIX timestamp.
     */
    private function setTimestamp(): void
    {
        date_default_timezone_set('UTC');
        $this->timestamp = (string) time();
    }

    /**
     * Menghasilkan signature yang digunakan untuk autentikasi BPJS.
     */
    private function setSignature(): void
    {
        $data = $this->cons_id . '&' . $this->timestamp;
        $signature = hash_hmac('sha256', $data, $this->secret_key, true);
        $this->signature = base64_encode($signature);
    }

    /**
     * Mengirim request ke API BPJS.
     *
     * @param string $method Metode HTTP (GET, POST, PUT, DELETE)
     * @param string $endpoint Endpoint API yang dituju
     * @param array $data Data yang akan dikirim (opsional)
     * @return array Response dari API dalam bentuk array
     */
    private function sendRequest(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = ['headers' => $this->headers];
            if (!empty($data)) {
                $options['json'] = $data;
            }
    
            $response = $this->clients->request($method, "{$this->base_url}/{$this->service_name}/$endpoint", $options);
            $result = json_decode($response->getBody()->getContents(), true) ?? [];
            var_dump($result);
            // Tangani jika response bukan 200 atau 201
            $statusCode = $result['metadata']['code'] ?? $response->getStatusCode();
            if (!in_array($statusCode, [200, 201])) {
                return [
                    'error'   => true,
                    'message' => $result['metadata']['message'] ?? 'Unexpected response',
                    'code'    => $statusCode,
                ];
            }
            return $result;
        } catch (RequestException $e) {
            // Jika response kosong atau tidak JSON, kembalikan pesan error standar
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            return [
                'error'   => true,
                'message' => $statusCode == 503 ? 'Service Unavailable' : $e->getMessage(),
                'code'    => $statusCode
            ];
        }
    }
    

    /**
     * Melakukan request GET ke API BPJS.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @return array Response dari API BPJS.
     */
    public function get(string $endpoint): array
    {
        return $this->sendRequest('GET', $endpoint);
    }

    /**
     * Melakukan request POST ke API BPJS.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param array $data Data yang akan dikirim.
     * @return array Response dari API BPJS.
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->sendRequest('POST', $endpoint, $data);
    }

    /**
     * Melakukan request PUT ke API BPJS.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param array $data Data yang akan dikirim.
     * @return array Response dari API BPJS.
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->sendRequest('PUT', $endpoint, $data);
    }

    /**
     * Melakukan request DELETE ke API BPJS.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param array $data Data yang akan dikirim (jika diperlukan).
     * @return array Response dari API BPJS.
     */
    public function delete(string $endpoint, array $data = []): array
    {
        return $this->sendRequest('DELETE', $endpoint, $data);
    }
}

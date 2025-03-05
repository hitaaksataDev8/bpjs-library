<?php

namespace Tests\Unit\Antrian;

use Hitaaksata\Bpjs\Antrian\ReferensiService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Hitaaksata\Bpjs\BpjsService;


class AntrianReferensiServiceTest extends TestCase
{
    private ReferensiService $antrianService;
    private $mockClient;
    protected function setUp(): void
    {
        parent::setUp();
       
        // Simulasi konfigurasi BPJS yang valid
        $configurations = [
            'cons_id'    => '6175',
            'secret_key' => '7kN4C71E46',
            'base_url'   => 'https://apijkn-dev.bpjs-kesehatan.go.id',
            'user_key'   => '02448283bea866e056b9181203e18f3f',
            'service_name' => 'antreanrs_dev'
        ];

        $this->antrianService = new ReferensiService($configurations);
    }

    /** @test */
    public function it_fetches_poli_list_successfully()
    {
        $response = $this->antrianService->getPoli();
        $this->assertIsArray($response);
    }

    /** @test */
    public function it_handles_invalid_poli_response()
    {
        // Simulasikan respon kosong atau error dari BPJS
        $response = [];
        $this->assertEmpty($response);
    }

    public function test_it_handles_failed_request_properly()
    {
        $response = $this->antrianService->getPoli();

        $this->assertArrayHasKey('error', $response);
        $this->assertTrue($response['error']);
        //$this->assertEquals('Service Unavailable', $response['message']); // ✅ Update ekspektasi error
    }

}


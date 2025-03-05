<?php

namespace Tests\Unit;

use Hitaaksata\Bpjs\BpjsService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class BpjsServiceTest extends TestCase
{
    private array $config;
    private BpjsService $bpjs;
    private $mockClient;

    protected function setUp(): void
    {
        $this->config = [
            'cons_id'      => '123456',
            'secret_key'   => 'my-secret-key',
            'base_url'     => 'https://apijkn.bpjs-kesehatan.go.id',
            'user_key'     => 'my-user-key',
            'service_name' => 'vclaim',
        ];

        $this->mockClient = $this->createMock(Client::class);
        $this->bpjs = new BpjsService($this->config, $this->mockClient);
    }

    /** @test */
    public function it_initializes_bpjs_service_correctly()
    {
        $this->assertInstanceOf(BpjsService::class, $this->bpjs);
        $this->assertNotEmpty($this->bpjs);
    }

    /** @test */
    public function it_makes_a_successful_get_request()
    {
        $responseBody = json_encode(['status' => 200, 'message' => 'Success', 'data' => ['poli' => 'Umum']]);

        $this->mockClient
            ->method('request')
            ->willReturn(new Response(200, [], $responseBody));

        $response = $this->bpjs->get('ref/poli');

        $this->assertIsArray($response);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Umum', $response['data']['poli']);
    }

    /** @test */
    public function it_makes_a_successful_post_request()
    {
        $responseBody = json_encode(['status' => 201, 'message' => 'Created']);

        $this->mockClient
            ->method('request')
            ->willReturn(new Response(201, [], $responseBody));

        $response = $this->bpjs->post('sep', ['noKartu' => '000123456789']);

        $this->assertIsArray($response);
        $this->assertEquals(201, $response['status']);
    }

    /** @test */
    public function it_handles_failed_request_properly()
    {
        $this->mockClient
            ->method('request')
            ->willThrowException(new RequestException('Network error', new Request('GET', 'ref/poli')));

        $response = $this->bpjs->get('ref/poli');

        $this->assertArrayHasKey('error', $response);
        $this->assertTrue($response['error']);
        $this->assertEquals('Network error', $response['message']);
    }

    /** @test */
    public function it_generates_correct_headers()
    {
        $reflection = new \ReflectionClass($this->bpjs);
        $method = $reflection->getMethod('setHeaders');
        $method->setAccessible(true);
        $method->invoke($this->bpjs);

        $headers = $reflection->getProperty('headers');
        $headers->setAccessible(true);
        $headers = $headers->getValue($this->bpjs);

        $this->assertArrayHasKey('X-cons-id', $headers);
        $this->assertArrayHasKey('X-Timestamp', $headers);
        $this->assertArrayHasKey('X-Signature', $headers);
        $this->assertArrayHasKey('user_key', $headers);

        $this->assertEquals($this->config['cons_id'], $headers['X-cons-id']);
        $this->assertEquals($this->config['user_key'], $headers['user_key']);
    }

    /** @test */
    public function it_generates_correct_signature()
    {
        $reflection = new \ReflectionClass($this->bpjs);
        $method = $reflection->getMethod('setSignature');
        $method->setAccessible(true);
        $method->invoke($this->bpjs);

        $signature = $reflection->getProperty('signature');
        $signature->setAccessible(true);
        $signature = $signature->getValue($this->bpjs);

        $this->assertNotEmpty($signature);
        $this->assertIsString($signature);
    }
}

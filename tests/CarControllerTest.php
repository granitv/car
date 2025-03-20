<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class CarControllerTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'http_errors' => false,
        ]);
    }

    /**
     * Test the GET /cars endpoint to retrieve a list of cars.
     * This function sends a GET request to the /cars endpoint and asserts that the response status code is 200.
     * It then decodes the response body as JSON and asserts that the resulting data is a non-empty array.
     * Finally, it asserts that the first element of the array has keys 'make', 'models', 'years', and 'glass_options'.
     *
     * @return void
     * @throws GuzzleException
     */

    public function testGetCarList()
    {
        $response = $this->client->request('GET', '/cars');

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);

        $this->assertArrayHasKey('make', $responseData[0]);
        $this->assertArrayHasKey('models', $responseData[0]);
        $this->assertArrayHasKey('years', $responseData[0]);
        $this->assertArrayHasKey('glass_options', $responseData[0]);
    }
}

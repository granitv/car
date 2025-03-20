<?php

namespace App\Controllers;

use App\Services\QuoteService;

class QuoteController
{
    private QuoteService $quoteService;
    public function __construct()
    {
        $this->quoteService = new QuoteService();
    }
    /**
     * Processes a quote request by decoding the JSON data from the request body,
     * passing it to the quote service, and sending the response back to the client.
     * @return void
     */
    public function requestQuote(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $this->quoteService->requestQuote($data);
        if (isset($response['error'])) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }

        echo json_encode($response);
    }
}

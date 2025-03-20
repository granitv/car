<?php

namespace App\Controllers;

use App\Services\VendorService;

class VendorController
{
    private VendorService $vendorService;

    public function __construct()
    {
        $this->vendorService = new VendorService();
    }

    /**
     * Retrieves the available vendors based on the selected car make, model, and glass type.
     * @return void
     */
    public function getVendors(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $carMake = $_SESSION['selected_car']['make'] ?? null;
        $carModel = $_SESSION['selected_car']['model'] ?? null;
        $glassType = $data['glass_type'] ?? null;

        if (!$carMake || !$carModel || !$glassType) {
            http_response_code(400);
            echo json_encode(["error" => "Glass type, car make, and model are required"]);
            exit;
        }
        $_SESSION['selected_glass'] = $glassType;
        $availableVendors = $this->vendorService->getAvailableVendors($carMake, $glassType);

        echo json_encode(["vendors" => $availableVendors]);
    }
}

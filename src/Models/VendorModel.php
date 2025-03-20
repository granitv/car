<?php

namespace App\Models;

class VendorModel
{
    private array $vendors = [
        [
            "vendor" => "AutoGlass Co",
            "supports" => [
                "Toyota" => ["windshield", "left door glass"],
                "Ford" => ["windshield", "right door glass"],
                "BMW" => ["rear glass", "windshield"]
            ],
            "price" => 200,
            "warranty" => "1 year",
            "delivery_time" => "3 days",
            "delivery_price" => 50
        ],
        [
            "vendor" => "GlassFix",
            "supports" => [
                "Toyota" => ["rear glass", "right door glass"],
                "Ford" => ["windshield"],
                "BMW" => ["left door glass", "right door glass"]
            ],
            "price" => 250,
            "warranty" => "2 years",
            "delivery_time" => "5 days",
            "delivery_price" => 60
        ],
        [
            "vendor" => "FastGlass",
            "supports" => [
                "Toyota" => ["windshield", "rear glass"],
                "Ford" => ["left door glass"],
                "BMW" => ["windshield", "left door glass"]
            ],
            "price" => 180,
            "warranty" => "1 year",
            "delivery_time" => "2 days",
            "delivery_price" => 40
        ]
    ];

    /**
     * Retrieves the available vendors based on the given car make and glass type.
     * @param string $carMake The make of the car.
     * @param string $glassType The type of glass.
     * @return array An array of available vendors, each containing the vendor name, price, warranty, delivery time, and delivery price.
     */
    public function getVendorsByCarAndGlass(string $carMake, string $glassType): array
    {
        $availableVendors = [];
        foreach ($this->vendors as $vendor) {
            if (isset($vendor['supports'][$carMake]) && in_array($glassType, $vendor['supports'][$carMake])) {
                $availableVendors[] = [
                    "vendor" => $vendor['vendor'],
                    "price" => $vendor['price'],
                    "warranty" => $vendor['warranty'],
                    "delivery_time" => $vendor['delivery_time'],
                    "delivery_price" => $vendor['delivery_price']
                ];
            }
        }
        return $availableVendors;
    }

    /**
     * Retrieves a vendor by their name.
     * @param string $vendorName The name of the vendor to retrieve.
     * @return array|null The vendor data if found, null otherwise.
     */

    public function getVendorByName(string $vendorName): ?array
    {
        foreach ($this->vendors as $vendor) {
            if ($vendor['vendor'] === $vendorName) {
                return $vendor;
            }
        }
        return null;
    }
}

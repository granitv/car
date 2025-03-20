<?php

namespace App\Services;

use App\Models\VendorModel;

class VendorService
{
    private VendorModel $vendorModel;

    public function __construct()
    {
        $this->vendorModel = new VendorModel();
    }

    /**
     * Get a list of vendors available for a specific car and glass type.
     * @param string $carMake The car make.
     * @param string $glassType The glass type.
     * @return array A list of available vendors.
     */
    public function getAvailableVendors(string $carMake, string $glassType): array
    {
        return $this->vendorModel->getVendorsByCarAndGlass($carMake, $glassType);
    }

    /**
     * Get a vendor's details by their name.
     * @param string $vendorName The vendor's name.
     * @return array|null The vendor's data or null if not found.
     */
    public function getVendorByName(string $vendorName): ?array
    {
        return $this->vendorModel->getVendorByName($vendorName);
    }
}

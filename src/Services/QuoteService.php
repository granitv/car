<?php

namespace App\Services;

use App\Models\CarModel;
use App\Models\VendorModel;
use SQLite3;

class QuoteService
{
    private VendorModel $vendorModel;
    private CarModel $carModel;

    public function __construct()
    {
        $this->vendorModel = new VendorModel();
        $this->carModel = new CarModel();
    }

    /**
     * Handle the quote request.
     *
     * @param array $data The quote request data.
     * @return array The response data.
     */
    public function requestQuote(array $data): array
    {
        $selectedCar = $_SESSION['selected_car'] ?? null;
        $selectedGlass = $_SESSION['selected_glass'] ?? null;
        if (!$selectedCar || !$selectedGlass) {
            return ["error" => "Car or glass selection missing"];
        }
        if (!$this->isValidQuoteRequest($data)) {
            return ["error" => "Invalid quote request"];
        }
        if (!$this->isValidCarSelection($selectedCar, $selectedGlass)) {
            return ["error" => "Invalid car or glass selection"];
        }
        if (!$this->isValidVendorSelection($data, $selectedCar, $selectedGlass)) {
            return ["error" => "Invalid vendor or price selection"];
        }
        $this->saveQuote($data, $selectedCar, $selectedGlass);

        return [
            "message" => "Quote requested successfully",
            "quote_details" => [
                "car" => $selectedCar,
                "glass" => $selectedGlass,
                "vendor" => $data['vendor_name'],
                "final_price" => intval($data['price']) + intval($data['delivery_price']),
                "delivery_time" => $data['delivery_time'],
                "warranty" => $data['warranty']
            ]
        ];
    }

    /**
     * Validates a quote request data array.
     * @param array $data The quote request data array.
     * @return bool Returns true if all required fields are set, false otherwise.
     */

    private function isValidQuoteRequest(array $data): bool
    {
        return isset($data['vendor_name'], $data['price'], $data['delivery_time'], $data['warranty'], $data['delivery_price']);
    }
    /**
     * Validates the selected car and glass options.
     * @param array $selectedCar The selected car options.
     * @param mixed $selectedGlass The selected glass option.
     * @return bool Returns true if the selected car and glass options are valid, false otherwise.
     */

    private function isValidCarSelection(array $selectedCar, mixed $selectedGlass): bool
    {
        return in_array($selectedCar["make"], $this->carModel->getCarByMake($selectedCar["make"]))
            && in_array($selectedGlass, $this->carModel->getGlassOptionsForMake($selectedCar["make"]));
    }
    /**
     * Validates the selected vendor, glass, price, warranty, and delivery time against the vendor's details.
     * @param array $data The quote request data array containing vendor name, price, warranty, delivery time, and delivery price.
     * @param array $selectedCar The selected car options array containing make.
     * @param mixed $selectedGlass The selected glass option.
     * @return bool Returns true if the selected vendor, glass, price, warranty, and delivery time are valid, false otherwise.
     */
    private function isValidVendorSelection(array $data, array $selectedCar, mixed $selectedGlass): bool
    {
        $vendor = $this->vendorModel->getVendorByName($data['vendor_name']);

        return $vendor
            && in_array($selectedGlass, $vendor['supports'][$selectedCar["make"]] ?? [])
            && intval($data['price']) === $vendor['price']
            && $data['warranty'] === $vendor['warranty']
            && $data['delivery_time'] === $vendor['delivery_time']
            && intval($data['delivery_price']) === $vendor['delivery_price'];
    }
    /**
     * Saves a quote to the database.
     * @param array $data An associative array containing the following keys:
     *                    - 'vendor_name': The vendor name.
     *                    - 'price': The price of the quote.
     *                    - 'delivery_time': The delivery time of the quote.
     *                    - 'warranty': The warranty of the quote.
     *                    - 'delivery_price': The delivery price of the quote.
     * @param array $selectedCar The selected car options array containing make.
     * @param mixed $selectedGlass The selected glass option.
     * @return void
     */
    private function saveQuote(array $data, array $selectedCar, mixed $selectedGlass): void
    {
        $db = new SQLite3(__DIR__ . '/../../storage/database/quotes.db');
        $db->exec("CREATE TABLE IF NOT EXISTS quotes (
            id INTEGER PRIMARY KEY,
            car TEXT,
            glass TEXT,
            vendor TEXT,
            price INTEGER,
            delivery_time TEXT,
            warranty TEXT,
            final_price INTEGER,
            delivery_price INTEGER,
            date TEXT
        )");

        $stmt = $db->prepare("INSERT INTO quotes (car, glass, vendor, price, delivery_time, warranty, final_price, delivery_price, date) 
                              VALUES (:car, :glass, :vendor, :price, :delivery_time, :warranty, :final_price, :delivery_price, :date)");
        $stmt->bindValue(':car', json_encode($selectedCar), SQLITE3_TEXT);
        $stmt->bindValue(':glass', $selectedGlass, SQLITE3_TEXT);
        $stmt->bindValue(':vendor', $data['vendor_name'], SQLITE3_TEXT);
        $stmt->bindValue(':price', $data['price'], SQLITE3_INTEGER);
        $stmt->bindValue(':delivery_time', $data['delivery_time'], SQLITE3_TEXT);
        $stmt->bindValue(':warranty', $data['warranty'], SQLITE3_TEXT);
        $stmt->bindValue(':delivery_price', $data['delivery_price'], SQLITE3_INTEGER);
        $stmt->bindValue(':final_price', intval($data['price']) + intval($data['delivery_price']), SQLITE3_INTEGER);
        $stmt->bindValue(':date', date('Y-m-d H:i:s'), SQLITE3_TEXT);
        $stmt->execute();
    }
}

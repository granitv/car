<?php

namespace App\Controllers;

use App\Models\CarModel;

class CarController
{
    private CarModel $carModel;
    public function __construct()
    {
        $this->carModel = new CarModel();
    }

    /**
     * Retrieves a list of cars and returns it as a JSON response.
     * @return void
     */
    public function getCarList(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->carModel->getCarList());
    }
    /**
     * Selects a car based on the provided data and stores it in the session.
     * @return void
     */
    public function selectCar(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['make'], $data['model'], $data['year'], $data['type'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid car selection"]);
            return;
        }
        $_SESSION['selected_car'] = $data;
        header('Content-Type: application/json');

        echo json_encode(["message" => "Car selected successfully", "selected_car" => $data]);
    }

    /**
     * Retrieves the glass options for the selected car and returns them as a JSON response.
     * @return void
     */
    public function getGlassOptions(): void
    {
        if (!isset($_SESSION['selected_car'])) {
            http_response_code(400);
            echo json_encode(["error" => "No car selected"]);
            return;
        }
        header('Content-Type: application/json');

        echo json_encode(["glass_options" => ["windshield", "rear glass", "left door glass", "right door glass"]]);
    }

    /**
     * Retrieves the glass options for a specific car make and returns them as a JSON response.
     * @param string $make The make of the car.
     * @return void
     */
    public function getGlassOptionsForSpecificCar(string $make): void
    {
        $glassOptions = $this->carModel->getGlassOptionsForMake($make);
        header('Content-Type: application/json');

        echo json_encode(["glass_options" => $glassOptions ?? []]);
    }

    /**
     * Retrieves the car data for a specific make and returns it as a JSON response.
     * @param string $carMake The make of the car.
     * @return void
     */
    public function getCarDataByMake(string $carMake): void
    {
        $carData = $this->carModel->getCarByMake($carMake);
        header('Content-Type: application/json');

        echo json_encode($carData ?? ["error" => "Car not found"]);
    }
}

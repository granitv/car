<?php

namespace App\Models;

class CarModel
{
    /**
     * @var array List of available cars with their models, years, and glass options.
     */
    public array $cars = [
        ["make" => "Toyota", "models" => ["Camry", "Corolla"], "years" => [2020, 2021], "glass_options" => ["left door glass", "right door glass", "rear glass", "windshield"]],
        ["make" => "Ford", "models" => ["Focus", "Fiesta"], "years" => [2019, 2020], "glass_options" => ["left door glass", "right door glass", "rear glass", "windshield"]],
        ["make" => "BMW", "models" => ["X5", "X3"], "years" => [2018, 2019], "glass_options" => ["left door glass", "right door glass", "rear glass", "windshield"]]
    ];

    /**
     * Get the list of available cars.
     *
     * @return array The list of cars.
     */
    public function getCarList(): array
    {
        return $this->cars;
    }

    /**
     * Get glass options for a specific car make.
     *
     * @param string $make The car make.
     * @return array|null The list of glass options or null if the make is not found.
     */
    public function getGlassOptionsForMake(string $make): ?array
    {
        $index = array_search($make, array_column($this->cars, 'make'));
        return $index !== false ? $this->cars[$index]['glass_options'] : null;
    }

    /**
     * Get car details by its make.
     *
     * @param string $make The car make.
     * @return array|null The car details or null if not found.
     */
    public function getCarByMake(string $make): ?array
    {
        $index = array_search($make, array_column($this->cars, 'make'));
        return $index !== false ? $this->cars[$index] : null;
    }
}

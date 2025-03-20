# Car Glass Quote Application

## Overview
This is a car glass replacement quote system built with pure PHP.

## Installation
1. Clone the repository  
   ```bash
   git clone https://github.com/granitv/car.git
   cd car
   ```

## Install dependencies
 2.
    ```bash
    composer install
    ```
## Run the application
3.
    ```bash
    php -S localhost:8000 -t public
    ```
2. Open your browser and navigate to `http://localhost:8000`
3. Select a car from the dropdown menu and click "Get Glass Options".
4. Select a glass option and click "Get Vendors".
5. Select a vendor and click "Request Quote".
6. Fill in the form with your details and click "Submit".
7. You will receive a confirmation message with the quote details.
8. You can also view the quote in the database.

## Run tests
4.
    ```bash
    ./vendor/bin/phpunit --verbose tests
    ```    
## API Endpoints
5.
|Methods|Endpoint|Description|
| ------------- | ------------- | ------------- |
| GET | /cars | Get all cars|
| POST | /select-car | Select a car|
| GET | /glass-options | Get glass options for the selected car|
| GET | /vendors | Get vendor quotes for the selected car and glass option|
| POST | /request-quote | Request a quote from the selected vendor|

## Technologies Used
6.
PHP 8.x |
SQLite |
PHPUnit

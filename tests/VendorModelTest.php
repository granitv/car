<?php


use App\Models\VendorModel;
use PHPUnit\Framework\TestCase;

class VendorModelTest extends TestCase
{
    private VendorModel $vendorModel;

    protected function setUp(): void
    {
        $this->vendorModel = new VendorModel();
    }

    /**
     * Test the getVendorsByCarAndGlass method.
     * This test checks if the method returns the correct vendors for a given car make and glass type.
     */
    public function testGetVendorsByCarAndGlass()
    {
        $result = $this->vendorModel->getVendorsByCarAndGlass('Toyota', 'windshield');
        $expected = [
            [
                "vendor" => "AutoGlass Co",
                "price" => 200,
                "warranty" => "1 year",
                "delivery_time" => "3 days",
                "delivery_price" => 50
            ],
            [
                "vendor" => "FastGlass",
                "price" => 180,
                "warranty" => "1 year",
                "delivery_time" => "2 days",
                "delivery_price" => 40
            ]
        ];
        $this->assertEquals($expected, $result);

        $result = $this->vendorModel->getVendorsByCarAndGlass('BMW', 'left door glass');
        $expected = [
            [
                "vendor" => "GlassFix",
                "price" => 250,
                "warranty" => "2 years",
                "delivery_time" => "5 days",
                "delivery_price" => 60
            ],
            [
                "vendor" => "FastGlass",
                "price" => 180,
                "warranty" => "1 year",
                "delivery_time" => "2 days",
                "delivery_price" => 40
            ]
        ];
        $this->assertEquals($expected, $result);

        $result = $this->vendorModel->getVendorsByCarAndGlass('Ford', 'rear glass');
        $this->assertEmpty($result);
    }

    /**
     * Test the getVendorByName method.
     * This test checks if the method returns the correct vendor data for a given vendor name.
     */
    public function testGetVendorByName()
    {
        $result = $this->vendorModel->getVendorByName('GlassFix');
        $expected = [
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
        ];
        $this->assertEquals($expected, $result);

        $result = $this->vendorModel->getVendorByName('NonExistentVendor');
        $this->assertNull($result);
    }
}
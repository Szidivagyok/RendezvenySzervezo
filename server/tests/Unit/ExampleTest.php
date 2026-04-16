<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A képen látható 'locations' tábla első sorának ellenőrzése
     */
    public function test_location_data_integrity(): void
    {
        $location = [
            'id' => 1,
            'cityName' => 'Szolnok',
            'zipCode' => 5052,
            'street' => 'Gőz street',
            'houseNumber' => 12,
            'locationName' => 'restaurant',
            'maxCapacity' => 100,
            'priceSlashPerson' => 12000
        ];

        $this->assertEquals('Szolnok', $location['cityName']);
        $this->assertIsInt($location['zipCode']);
        $this->assertGreaterThan(0, $location['maxCapacity']);
        $this->assertStringContainsString('street', $location['street'], "A 'street' elírás ellenőrzése");
    }

    /**
     * Az 'orders' és 'users' közötti kapcsolat tesztelése (Foreign Key logika)
     */
    public function test_order_belongs_to_user(): void
    {
        // A képen az 1-es id-jű order a 2-es id-jű userhez (booker) tartozik
        $order = [
            'id' => 1,
            'userId' => 2,
            'howManyPeople' => 72,
            'howManyDays' => 3
        ];

        $user = [
            'id' => 2,
            'name' => 'booker',
            'email' => 'megrendelo@gmail.com'
        ];

        $this->assertEquals($user['id'], $order['userId'], "A rendelés nem a megfelelő felhasználóhoz kötődik.");
        $this->assertLessThanOrEqual(100, $order['howManyPeople'], "A létszám túllépi az étterem kapacitását.");
    }

    /**
     * A 'services' és 'service_types' összekapcsolásának ellenőrzése
     */
    public function test_service_type_mapping(): void
    {
        // 'helyszín' szolgáltatás (id: 1) -> serviceTypeId: 1
        $service = [
            'id' => 1,
            'service' => 'helyszín',
            'serviceTypeId' => 1,
            'price' => 70000
        ];

        $serviceType = [
            'id' => 1,
            'serviceTypeName' => 'helyszín'
        ];

        $this->assertEquals($serviceType['id'], $service['serviceTypeId']);
        $this->assertIsNumeric($service['price']);
        $this->assertEquals(70000, $service['price']);
    }

    /**
     * A képen pirossal keretezett 'pictures' tábla részletének ellenőrzése
     */
    public function test_pictures_collection(): void
    {
        $pictures = [
            ['id' => 6, 'pictureName' => 'élő zene_1.jpg', 'serviceId' => 2],
            ['id' => 7, 'pictureName' => 'élő zene_2.jpg', 'serviceId' => 2],
            ['id' => 8, 'pictureName' => 'élő zene_3.jpg', 'serviceId' => 2],
            ['id' => 9, 'pictureName' => 'dj_1.jpg', 'serviceId' => 3],
        ];

        $this->assertCount(4, $pictures);
        $this->assertEquals('dj_1.jpg', $pictures[3]['pictureName']);
        
        // Ellenőrizzük, hogy az első kép valóban az "élő zene" service-hez (id: 2) tartozik-e
        $this->assertEquals(2, $pictures[0]['serviceId']);
    }
}
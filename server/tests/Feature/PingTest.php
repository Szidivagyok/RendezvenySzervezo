<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Location;
use App\Models\Service;
use App\Models\Order;
use App\Models\ServiceType;
use Tests\TestBase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;

class PingTest extends TestBase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Felhasználók biztosítása
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'admin', 'password' => bcrypt('123'), 'role' => 1]
        );

        User::firstOrCreate(
            ['email' => 'megrendelo@gmail.com'],
            ['name' => 'booker', 'password' => bcrypt('456'), 'role' => 2]
        );

        // Alapadatok kényszerekhez (Foreign Keys)
        if (!ServiceType::where('id', 1)->exists()) {
            ServiceType::factory()->create(['id' => 1, 'serviceTypeName' => 'helyszín']);
        }
        if (!Location::where('id', 1)->exists()) {
            Location::factory()->create(['id' => 1, 'cityName' => 'Szolnok']);
        }
        if (!Service::where('id', 1)->exists()) {
            Service::factory()->create(['id' => 1, 'service' => 'Alap szolgáltatás', 'serviceTypeId' => 1, 'price' => 1000]);
        }
    }

    private static function getStaticData($type)
    {
        $data = [
            "user" => ["name" => "Uj", "email" => "u".uniqid()."@a.hu", "password" => "123", "role" => 2],
            "location" => ["cityName" => "Eger", "zipCode" => "3300", "street" => "D", "houseNumber" => "1", "locationName" => "L".uniqid(), "maxCapacity" => 500, "minCapacity" => 10, "priceSlashPerson" => 2000, "roomPriceSlashDay" => 50000],
            "service" => ["service" => "S".uniqid(), "serviceTypeId" => 1, "price" => 25000],
            "order" => ["userId" => 2, "locationId" => 1, "howManyPeople" => 50, "howManyDays" => 2, "orderTime" => "2026-05-10 10:00:00"],
            "serviceType" => ["serviceTypeName" => "T".uniqid()]
        ];
        return $data[$type];
    }

    public static function tablesGetDataProvider(): array
    {
        return [
            'get users admin: 200' => ['users', 'admin@example.com', '123', 200],
            'get locations admin: 200' => ['locations', 'admin@example.com', '123', 200],
            'get services admin: 200' => ['services', 'admin@example.com', '123', 200],
            'get orders admin: 200' => ['orders', 'admin@example.com', '123', 200],
            'get users booker: 403' => ['users', 'megrendelo@gmail.com', '456', 403],
            'get locations booker: 200' => ['locations', 'megrendelo@gmail.com', '456', 200],
        ];
    }

    public static function tablesPostDeleteDataProvider(): array
    {
        return [
            'post-delete locations admin' => ['locations', 'admin@example.com', '123', true, true, 'location'],
            'post-delete services admin' => ['services', 'admin@example.com', '123', true, true, 'service'],
            'post-delete service_types admin' => ['service_types', 'admin@example.com', '123', true, true, 'serviceType'],
            'post-delete orders booker' => ['orders', 'megrendelo@gmail.com', '456', true, false, 'order'],
            'post-delete locations booker' => ['locations', 'megrendelo@gmail.com', '456', false, false, 'location'],
        ];
    }

    #[DataProvider('tablesGetDataProvider')]
    public function test_table_user_login_get_logout($route, $email, $password, $expectedStatus): void
    {
        $response = $this->login($email, $password);
        $response->assertStatus(200);
        $token = $this->myGetToken($response);

        $response = $this->myGet("/api/$route", $token);
        $response->assertStatus($expectedStatus);

        $this->logout($token);
    }

    #[DataProvider('tablesPostDeleteDataProvider')]
    public function test_table_user_login_post_delete_logout($route, $email, $password, $expectedPost, $expectedDelete, $dataType): void 
    {
        $response = $this->login($email, $password);
        $response->assertStatus(200);
        $token = $this->myGetToken($response);
        
        $currentUser = User::where('email', $email)->first();
        $data = self::getStaticData($dataType);

        // POST
        $response = $this->myPost("/api/$route", $data, $token);
        $expectedPost ? $response->assertSuccessful() : $response->assertClientError();

        // DELETE rekord előkészítése minden kötelező mezővel
        $model = match($route) {
            'users' => User::factory()->create(),
            'locations' => Location::factory()->create(),
            'services' => Service::factory()->create([
                'service' => 'Test Service', 
                'serviceTypeId' => 1,
                'price' => 5000 // Ez hiányzott az előbb!
            ]),
            'orders' => Order::factory()->create([
                'userId' => $currentUser->id, // A bejelentkezett useré a rendelés
                'locationId' => 1
            ]),
            'service_types' => ServiceType::factory()->create([
                'serviceTypeName' => 'Test Type'
            ]),
            default => null
        };

        if ($model) {
            $response = $this->myDelete("/api/$route/{$model->id}", $token);
            $expectedDelete ? $response->assertSuccessful() : $response->assertStatus(403);
        }

        $this->logout($token);
    }
}
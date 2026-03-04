<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Location;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderService;
use Tests\TestBase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;

class PingTest extends TestBase
{
    use DatabaseTransactions;

    private static $dataUser = [
        "name" => "Teszt Elek",
        "email" => "teszt@example.com",
        "password" => "123",
        "role" => "user",
        "idNumber" => "AB123456",
        "city" => "Debrecen",
        "street" => "Kossuth",
        "postCode" => "4024",
        "houseNumber" => "12"
    ];

    private static $dataLocation = [
        "cityName" => "Debrecen",
        "zipCode" => "4024",
        "street" => "Piac",
        "houseNumber" => "10",
        "locationName" => "Hotel Teszt",
        "maxCapacity" => 10,
        "minCapacity" => 2,
        "priceSlashPerson" => 5000,
        "roomPriceSlashDay" => 20000
    ];

    private static $dataService = [
        "service" => "Reggeli",
        "price" => 3000
    ];

    private static $dataOrder = [
        "userId" => 1,
        "locationId" => 1,
        "howManyPeople" => 2,
        "howManyDays" => 3,
        "orderTime" => "2026-01-01 12:00:00"
    ];

    private static $dataOrderService = [
        "orderId" => 1,
        "serviceId" => 1
    ];
    public static function tablesGetDataProvider(): array
    {
        return [
            'get users admin: 200' => ['users', 'admin@example.com', '123', 200],
            'get locations admin: 200' => ['locations', 'admin@example.com', '123', 200],
            'get services admin: 200' => ['services', 'admin@example.com', '123', 200],
            'get orders admin: 200' => ['orders', 'admin@example.com', '123', 200],
            'get order_services admin: 200' => ['order_services', 'admin@example.com', '123', 200],
            'get locations_pictures admin: 200' => ['locations_pictures', 'admin@example.com', '123', 200],
            'get pictures admin: 200' => ['pictures', 'admin@example.com', '123', 200],
            'get service_types admin: 200' => ['service_types', 'admin@example.com', '123', 200],




            'get users user: 403' => ['users', 'megrendelo@example.com', '123', 403],
            'get locations user: 200' => ['locations', 'megrendelo@example.com', '123', 200],
            'get services user: 200' => ['services', 'megrendelo@example.com', '123', 200],
            'get orders user: 200' => ['orders', 'megrendelo@example.com', '123', 200],
            'get order_services user: 200' => ['order_services', 'megrendelo@example.com', '123', 200],
            'get locations_pictures user: 200' => ['locations_pictures', 'megrendelo@example.com', '123', 200],
            'get pictures user: 200' => ['pictures', 'megrendelo@example.com', '123', 200],
            'get service_types user: 200' => ['service_types', 'megrendelo@example.com', '123', 200],


        ];
    }

    public static function tablesPostDeleteDataProvider(): array
    {
        return [
            'post-delete users admin' => ['users', 'admin@example.com', '123', true, true, self::$dataUser],
            'post-delete locations admin' => ['locations', 'admin@example.com', '123', true, true, self::$dataLocation],
            'post-delete services admin' => ['services', 'admin@example.com', '123', true, true, self::$dataService],
            'post-delete orders admin' => ['orders', 'admin@example.com', '123', true, true, self::$dataOrder],
            'post-delete order_services admin' => ['order_services', 'admin@example.com', '123', true, true, self::$dataOrderService],
            'post-delete locations_pictures admin' => ['locations_pictures', 'admin@example.com', '123', true, true, self::$dataOrderService],
            'post-delete pictures admin' => ['pictures', 'admin@example.com', '123', true, true, self::$dataOrderService],
            'post-delete service_types admin' => ['service_types', 'admin@example.com', '123', true, true, self::$dataOrderService],


            'post-delete users user' => ['users', 'megrendelo@example.com', '123', false, false, self::$dataUser],
            'post-delete locations user' => ['locations', 'megrendelo@example.com', '123', false, false, self::$dataLocation],
            'post-delete services user' => ['services', 'megrendelo@example.com', '123', false, false, self::$dataService],
            'post-delete orders user' => ['orders', 'megrendelo@example.com', '123', true, false, self::$dataOrder],
            'post-delete order_services user' => ['order_services', 'megrendelo@example.com', '123', false, false, self::$dataOrderService],
            'post-delete locations_pictures user' => ['locations_pictures', 'megrendelo@example.com', '123', false, false, self::$dataOrderService],
            'post-delete pictures user' => ['pictures', 'megrendelo@example.com', '123', false, false, self::$dataOrderService],
            'post-delete service_types user' => ['service_types', 'megrendelo@example.com', '123', false, false, self::$dataOrderService],

        ];
    }

    #[DataProvider('tablesGetDataProvider')]
    public function test_table_user_login_get_logout($route, $email, $password, $expectedStatus): void
    {
        $response = $this->login($email, $password);
        $response->assertStatus(200);

        $token = $this->myGetToken($response);

        $uri = "/api/$route";
        $response = $this->myGet($uri, $token);
        $response->assertStatus($expectedStatus);

        $response = $this->logout($token);
        $response->assertStatus(200);
    }

    #[DataProvider('tablesPostDeleteDataProvider')]
    public function test_table_user_login_post_delete_logout(
        $route,
        $email,
        $password,
        $expectedPostAccess,
        $expectedDeleteAccess,
        $data
    ): void {

        $response = $this->login($email, $password);
        $response->assertStatus(200);

        $token = $this->myGetToken($response);

        $uri = "/api/$route";

        // POST
        $response = $this->myPost($uri, $data, $token);
        $expectedPostAccess
            ? $response->assertSuccessful()
            : $response->assertClientError();

        // DELETE
        if ($route == "users") {
            $model = User::factory()->create();
        } elseif ($route == "locations") {
            $model = Location::factory()->create();
        } elseif ($route == "services") {
            $model = Service::factory()->create();
        } elseif ($route == "orders") {
            $model = Order::factory()->create();
        } elseif ($route == "order_services") {
            $model = OrderService::factory()->create();
        }

        $deleteUri = $uri . "/" . $model->id;
        $response = $this->myDelete($deleteUri, $token);

        $expectedDeleteAccess
            ? $response->assertSuccessful()
            : $response->assertClientError();

        $response = $this->logout($token);
        $response->assertStatus(200);
    }
}
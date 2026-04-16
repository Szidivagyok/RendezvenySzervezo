<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DatabaseServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public static function tablesProvider(): array
    {
        return [
            'services tábla' => ['services'],
            'users tábla' => ['users'],
            'orders tábla' => ['orders'],
            'order_services tábla' => ['order_services'],
            'locations tábla' => ['locations'],
            'service_types' => ['service_types'],
            'locations_pictures' => ['locations_pictures'],
            'pictures' => ['pictures'],

        ];
    }

    #[DataProvider('tablesProvider')]
    public function test_exists_all_tables($table): void
    {
        $this->assertTrue(
            Schema::hasTable($table),
            "A '$table' tábla nem létezik"
        );
    }
}

<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class OrderServiceTest extends TestCase
{
    protected $table = 'order_services';

    /**
     * Adatszolgáltató az orderServices tábla elvárt szerkezetéhez.
     */
    public static function expectedSchemaDataProvider(): array
    {
        return [
            'id'        => ['id', 'bigint'],
            'orderId'   => ['orderId', 'bigint'],
            'serviceId' => ['serviceId', 'bigint'],
        ];
    }

    /**
     * Ellenőrzi, hogy a tábla létezik-e az adatbázisban.
     */
    public function test_exists_order_services_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table), 
            "A '{$this->table}' tábla nem létezik."
        );
    }

    /**
     * Ellenőrzi, hogy a tábla tartalmazza-e az összes elvárt mezőt.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_order_services_table_contain_all_fields(string $expectedColumn, string $expectedType): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn), 
            "A(z) '$expectedColumn' oszlop hiányzik a '{$this->table}' táblából."
        );
    }

    /**
     * Ellenőrzi, hogy az oszlopok típusa megfelelő-e (bigint).
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_order_services_table_columns_have_the_expected_types(string $expectedColumn, string $expectedType): void
    {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertEquals(
            $expectedType, 
            $actualDbSqlType, 
            "A '{$expectedColumn}' oszlop típusa nem egyezik. Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }
}
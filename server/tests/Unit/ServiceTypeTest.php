<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ServiceTypeTest extends TestCase
{
    // A tábla neve a képek alapján
    protected $table = 'service_types';

    /**
     * Adatszolgáltató a service_types tábla oszlopaihoz.
     */
    public static function expectedSchemaDataProvider(): array
    {
        return [
            'id'              => ['id', 'bigint'],
            'serviceTypeName' => ['serviceTypeName', 'varchar'],
        ];
    }

    /**
     * Ellenőrzi, hogy a 'service_types' tábla létezik-e.
     */
    public function test_exists_service_types_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table), 
            "A '{$this->table}' tábla nem létezik az adatbázisban."
        );
    }

    /**
     * Ellenőrzi, hogy a tábla tartalmazza-e a várt mezőket.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_service_types_table_contain_all_fields(string $expectedColumn, string $expectedType): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn), 
            "A '$expectedColumn' oszlop hiányzik a '{$this->table}' táblából."
        );
    }

    /**
     * Ellenőrzi, hogy az oszlopok típusa megfelelő-e.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_service_types_table_columns_have_the_expected_types(string $expectedColumn, string $expectedType): void
    {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertEquals(
            $expectedType, 
            $actualDbSqlType, 
            "A '{$expectedColumn}' oszlop típusa nem megfelelő. Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }
}
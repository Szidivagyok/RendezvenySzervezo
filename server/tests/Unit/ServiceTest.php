<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ServiceTest extends TestCase
{
    // A tábla neve a képek alapján
    protected $table = 'services';

    /**
     * Adatszolgáltató a services tábla oszlopaihoz.
     */
    public static function expectedSchemaDataProvider(): array
    {
        return [
            'id'            => ['id', 'bigint'],
            'service'       => ['service', 'varchar'],
            'serviceTypeId' => ['serviceTypeId', 'bigint'],
            'price'         => ['price', 'decimal'],
        ];
    }

    /**
     * Ellenőrzi, hogy a 'services' tábla létezik-e.
     */
    public function test_exists_services_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table), 
            "A '{$this->table}' tábla nem létezik az adatbázisban."
        );
    }

    /**
     * Ellenőrzi, hogy minden várt mező szerepel-e a táblában.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_services_table_contain_all_fields(string $expectedColumn, string $expectedType): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn), 
            "A(z) '$expectedColumn' oszlop hiányzik a '{$this->table}' táblából."
        );
    }

    /**
     * Ellenőrzi, hogy az oszlopok típusa megfelelő-e.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_services_table_columns_have_the_expected_types(string $expectedColumn, string $expectedType): void
    {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertEquals(
            $expectedType, 
            $actualDbSqlType, 
            "A '{$expectedColumn}' oszlop típusa nem egyezik. Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }
}
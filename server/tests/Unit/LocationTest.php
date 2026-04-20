<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class LocationTest extends TestCase
{
    protected $table = 'locations';

    /**
     * Adatszolgáltató a locations tábla oszlopaihoz és típusaihoz
     */
    public static function expectedSchemaDataProvider(): array
    {
        return [
            'id'                => ['id', 'bigint'],
            'cityName'          => ['cityName', 'varchar'],
            'zipCode'           => ['zipCode', 'varchar'],
            'street'            => ['street', 'varchar'],
            'houseNumber'       => ['houseNumber', 'varchar'],
            'locationName'      => ['locationName', 'varchar'],
            'maxCapacity'       => ['maxCapacity', 'int'],
            'minCapacity'       => ['minCapacity', 'int'],
            'priceSlashPerson'  => ['priceSlashPerson', 'decimal'],
            'roomPriceSlashDay' => ['roomPriceSlashDay', 'decimal'],
        ];
    }

    /**
     * Ellenőrzi, hogy a tábla létezik-e
     */
    public function test_exists_locations_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table), 
            "A '{$this->table}' tábla nem létezik."
        );
    }

    /**
     * Ellenőrzi az összes oszlop meglétét
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_locations_table_contain_all_fields(string $expectedColumn, string $expectedType): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn), 
            "A(z) '$expectedColumn' oszlop hiányzik a '{$this->table}' táblából."
        );
    }

    /**
     * Ellenőrzi az oszlopok típusát
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_locations_table_columns_have_the_expected_types(string $expectedColumn, string $expectedType): void
    {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertEquals(
            $expectedType, 
            $actualDbSqlType, 
            "A '{$expectedColumn}' típusa hibás. Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }
}
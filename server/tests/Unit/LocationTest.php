<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;

class LocationTest extends TestCase
{
    use DatabaseTransactions;

    protected string $table = 'locations';

    public static function expectedSchemaDataProvider(): array
    {
        return [
            'cityName oszlop'           => ['cityName', 'varchar'],
            'zipCode oszlop'            => ['zipCode', 'varchar'],
            'street oszlop'             => ['street', 'varchar'],
            'houseNumber oszlop'        => ['houseNumber', 'varchar'],
            'locationName oszlop'       => ['locationName', 'varchar'],
            'maxCapacity oszlop'        => ['maxCapacity', 'int'],
            'minCapacity oszlop'        => ['minCapacity', 'int'],
            'priceSlashPerson oszlop'   => ['priceSlashPerson', 'decimal'],
            'roomPriceSlashDay oszlop'  => ['roomPriceSlashDay', 'decimal'],
        ];
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_locations_table_contain_all_fields(
        string $expectedColumn,
        string $expectedType
    ): void {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn),
            "A '{$expectedColumn}' oszlop nem létezik"
        );
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_locations_table_columns_have_expected_types(
        string $expectedColumn,
        string $expectedType
    ): void {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertSame(
            $expectedType,
            $actualDbSqlType,
            "A '{$expectedColumn}' oszlop típusa nem egyezik.
            Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }

    public function test_exists_locations_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table),
            "A locations tábla nem létezik"
        );
    }
}

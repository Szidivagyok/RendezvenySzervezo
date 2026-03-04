<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;

class LocationPictureTest extends TestCase
{
    use DatabaseTransactions;

    protected string $table = 'locations_pictures';

    public static function expectedSchemaDataProvider(): array
    {
        return [
            'locationId oszlop' => ['locationId', 'bigint'],
            'pictureId oszlop'  => ['pictureId', 'bigint'],
        ];
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_locations_pictures_table_contain_all_fields(
        string $expectedColumn,
        string $expectedType
    ): void {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn),
            "A '{$expectedColumn}' oszlop nem létezik"
        );
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_locations_pictures_table_columns_have_expected_types(
        string $expectedColumn,
        string $expectedType
    ): void {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertSame(
            $expectedType,
            $actualDbSqlType,
            "A '{$expectedColumn}' oszlop típusa nem egyezik.
            Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'"
        );
    }

    public function test_exists_locations_pictures_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table),
            "A locations_pictures tábla nem létezik"
        );
    }

    public function test_locationId_pictureId_unique_index_exists(): void
    {
        $connection = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $connection->listTableIndexes($this->table);

        $found = false;
        foreach ($indexes as $index) {
            if ($index->isUnique() && $index->getColumns() === ['locationId', 'pictureId']) {
                $found = true;
                break;
            }
        }

        $this->assertTrue(
            $found,
            "A locations_pictures táblában nem található unique index a ['locationId', 'pictureId'] oszlopokra."
        );
    }
}
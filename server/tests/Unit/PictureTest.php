<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PictureTest extends TestCase
{
    // A képeden a tábla neve: pictures
    protected $table = 'pictures';

    /**
     * Adatszolgáltató a pictures tábla oszlopaihoz.
     */
    public static function expectedSchemaDataProvider(): array
    {
        return [
            'id'          => ['id', 'bigint'],
            'pictureName' => ['pictureName', 'varchar'],
            'serviceId'   => ['serviceId', 'bigint'],
        ];
    }

    /**
     * Ellenőrzi, hogy a 'pictures' tábla létezik-e.
     */
    public function test_exists_pictures_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table), 
            "A '{$this->table}' tábla nem létezik az adatbázisban."
        );
    }

    /**
     * Ellenőrzi, hogy minden oszlop megvan-e.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_pictures_table_contain_all_fields(string $expectedColumn, string $expectedType): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn), 
            "A(z) '$expectedColumn' oszlop hiányzik a '{$this->table}' táblából."
        );
    }

    /**
     * Ellenőrzi az oszlopok típusait.
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_pictures_table_columns_have_the_expected_types(string $expectedColumn, string $expectedType): void
    {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertEquals(
            $expectedType, 
            $actualDbSqlType, 
            "A '{$expectedColumn}' oszlop típusa nem megfelelő. Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }
}
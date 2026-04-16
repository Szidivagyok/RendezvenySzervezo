<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class LocationPictureTest extends TestCase
{
    protected $table = 'locations_pictures';

    /**
     * Adatszolgáltató a locations_pictures tábla szerkezetéhez
     */
    public static function expectedSchemaDataProvider(): array
    {
        return [
            'id oszlop'        => ['id', 'bigint'],
            'pictureId oszlop'  => ['pictureId', 'bigint'],
            'locationId oszlop' => ['locationId', 'bigint'],
        ];
    }

    /**
     * Ellenőrizzük, hogy a tábla egyáltalán létezik-e az adatbázisban
     */
    public function test_exists_locations_pictures_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table), 
            "A '{$this->table}' tábla nem létezik az adatbázisban."
        );
    }

    /**
     * Ellenőrizzük, hogy minden várt oszlop szerepel-e a táblában
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_locations_pictures_table_contain_all_fields(string $expectedColumn, string $expectedType): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn), 
            "A(z) '$expectedColumn' oszlop nem található a '{$this->table}' táblában."
        );
    }

    /**
     * Ellenőrizzük, hogy az oszlopok típusa megfelel-e az elvárásoknak
     */
    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_locations_pictures_table_columns_have_the_expected_types(string $expectedColumn, string $expectedType): void
    {
        $actualDbSqlType = Schema::getColumnType($this->table, $expectedColumn);

        $this->assertEquals(
            $expectedType, 
            $actualDbSqlType, 
            "A '{$expectedColumn}' oszlop típusa nem egyezik. Várt: '{$expectedType}', Kapott: '{$actualDbSqlType}'."
        );
    }
}
<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    protected string $table = 'orders';

    public static function expectedSchemaDataProvider(): array
    {
        return [
            'userId oszlop'          => ['userId', 'bigint'],
            'locationId oszlop'      => ['locationId', 'bigint'],
            'howManyPeople oszlop'   => ['howManyPeople', 'int'],
            'howManyDays oszlop'     => ['howManyDays', 'int'],
            'orderTime oszlop'       => ['orderTime', 'datetime'],
        ];
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_orders_table_contain_all_fields(
        string $expectedColumn,
        string $expectedType
    ): void {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn),
            "A '{$expectedColumn}' oszlop nem létezik"
        );
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_orders_table_columns_have_expected_types(
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

    public function test_exists_orders_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table),
            "Az orders tábla nem létezik"
        );
    }
}

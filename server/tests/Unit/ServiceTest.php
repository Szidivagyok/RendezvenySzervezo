<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;

class ServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected string $table = 'services';

    public static function expectedSchemaDataProvider(): array
    {
        return [
            'service oszlop' => ['service', 'varchar'],
            'price oszlop'   => ['price', 'decimal'],
        ];
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_services_table_contain_all_fields(
        string $expectedColumn,
        string $expectedType
    ): void {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn),
            "A '{$expectedColumn}' oszlop nem létezik"
        );
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_services_table_columns_have_expected_types(
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

    public function test_exists_services_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table),
            "A services tábla nem létezik"
        );
    }
}

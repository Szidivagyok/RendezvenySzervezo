<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected string $table = 'users';

    public static function expectedSchemaDataProvider(): array
    {
        return [
            'name oszlop'     => ['name', 'varchar'],
            'role oszlop'     => ['role', 'int'],
            'email oszlop'    => ['email', 'varchar'],
            'password oszlop' => ['password', 'varchar'],
        ];
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_does_the_users_table_contain_all_fields(
        string $expectedColumn,
        string $expectedType
    ): void {
        $this->assertTrue(
            Schema::hasColumn($this->table, $expectedColumn),
            "A '{$expectedColumn}' oszlop nem létezik"
        );
    }

    #[DataProvider('expectedSchemaDataProvider')]
    public function test_the_users_table_columns_have_expected_types(
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

    public function test_exists_users_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table),
            "A users tábla nem létezik"
        );
    }
}

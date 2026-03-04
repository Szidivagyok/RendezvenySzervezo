<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class ServiceTypeTest extends TestCase
{
    use DatabaseTransactions;

    protected string $table = 'service_types';

    public function test_exists_service_types_table(): void
    {
        $this->assertTrue(
            Schema::hasTable($this->table),
            "A service_types tábla nem létezik"
        );
    }

    public function test_serviceTypeName_column_exists_and_type(): void
    {
        $this->assertTrue(
            Schema::hasColumn($this->table, 'serviceTypeName'),
            "A 'serviceTypeName' oszlop nem létezik"
        );

        $actualDbSqlType = Schema::getColumnType($this->table, 'serviceTypeName');

        $this->assertSame(
            'varchar',
            $actualDbSqlType,
            "A 'serviceTypeName' oszlop típusa nem egyezik. Várt: 'varchar', Kapott: '{$actualDbSqlType}'"
        );
    }

    public function test_serviceTypeName_unique_index_exists(): void
    {
        $connection = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $connection->listTableIndexes($this->table);

        $found = false;
        foreach ($indexes as $index) {
            if ($index->isUnique() && $index->getColumns() === ['serviceTypeName']) {
                $found = true;
                break;
            }
        }

        $this->assertTrue(
            $found,
            "A service_types táblában nem található unique index a 'serviceTypeName' oszlopra."
        );
    }
}
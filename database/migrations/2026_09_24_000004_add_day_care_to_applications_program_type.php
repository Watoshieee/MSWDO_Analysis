<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addDayCareToProgramTypeEnum();
    }

    public function down(): void
    {
        // Keep Day_Care in the enum to avoid data loss if applications already exist.
    }

    private function addDayCareToProgramTypeEnum(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        if (!Schema::hasColumn('applications', 'program_type')) {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM applications LIKE 'program_type'");
        if (!$column || !isset($column->Type) || !str_starts_with(strtolower($column->Type), 'enum(')) {
            return;
        }

        preg_match_all("/'((?:\\\\'|[^'])*)'/", $column->Type, $matches);
        $values = $matches[1] ?? [];

        if (in_array('Day_Care', $values, true)) {
            return;
        }

        $values[] = 'Day_Care';
        $quoted = implode(', ', array_map(fn ($value) => "'" . str_replace("'", "''", $value) . "'", $values));
        $nullable = strtoupper((string) ($column->Null ?? '')) === 'YES' ? 'NULL' : 'NOT NULL';

        DB::statement("ALTER TABLE applications MODIFY COLUMN program_type ENUM({$quoted}) {$nullable}");
    }
};

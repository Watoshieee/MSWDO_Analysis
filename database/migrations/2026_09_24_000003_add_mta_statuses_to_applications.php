<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql' || !Schema::hasColumn('applications', 'status')) {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM applications LIKE 'status'");
        if (!$column || !isset($column->Type) || !str_starts_with(strtolower($column->Type), 'enum(')) {
            return;
        }

        preg_match_all("/'((?:\\\\'|[^'])*)'/", $column->Type, $matches);
        $values = $matches[1] ?? [];
        foreach (['in_progress', 'submitted'] as $extra) {
            if (!in_array($extra, $values, true)) {
                $values[] = $extra;
            }
        }

        $quoted = implode(', ', array_map(fn ($value) => "'" . str_replace("'", "''", $value) . "'", $values));
        $nullable = strtoupper((string) ($column->Null ?? '')) === 'YES' ? 'NULL' : 'NOT NULL';
        $default = isset($column->Default) && $column->Default !== null
            ? "DEFAULT '" . str_replace("'", "''", $column->Default) . "'"
            : '';

        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM({$quoted}) {$nullable} {$default}");
    }

    public function down(): void
    {
        // Keep extra statuses to avoid failing on existing MTA rows.
    }
};

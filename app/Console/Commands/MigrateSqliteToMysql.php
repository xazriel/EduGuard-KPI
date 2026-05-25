<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import-sqlite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from SQLite database to the active MySQL database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration from SQLite to MySQL...');

        // 1. Confirm MySQL connection
        try {
            DB::connection()->getPdo();
            $this->info('Successfully connected to target MySQL database.');
        } catch (\Exception $e) {
            $this->error('Could not connect to the default (MySQL) database: ' . $e->getMessage());
            return 1;
        }

        // 2. Configure SQLite connection dynamically
        $sqlitePath = database_path('database.sqlite');
        if (!file_exists($sqlitePath)) {
            $this->error("SQLite database file not found at: {$sqlitePath}");
            return 1;
        }

        config(['database.connections.sqlite_import' => [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);

        try {
            DB::connection('sqlite_import')->getPdo();
            $this->info('Successfully connected to source SQLite database.');
        } catch (\Exception $e) {
            $this->error('Could not connect to the source SQLite database: ' . $e->getMessage());
            return 1;
        }

        // 3. Define the tables to copy in order of dependencies
        $tables = [
            'classes',
            'users',
            'students',
            'violations',
            'student_kpis',
            'statement_letters',
            'activity_logs',
        ];

        // 4. Disable foreign keys in MySQL
        $this->info('Disabling foreign key checks on MySQL...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 5. Copy tables
        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}...");

            if (!Schema::connection('sqlite_import')->hasTable($table)) {
                $this->warn("Table '{$table}' does not exist in SQLite database. Skipping.");
                continue;
            }

            if (!Schema::hasTable($table)) {
                $this->error("Table '{$table}' does not exist in MySQL database. Please run 'php artisan migrate' first.");
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return 1;
            }

            // Truncate the destination table in MySQL
            DB::table($table)->truncate();

            // Fetch records from SQLite in chunks
            $count = 0;
            DB::connection('sqlite_import')->table($table)->orderBy('id')->chunk(100, function ($rows) use ($table, &$count) {
                $data = [];
                foreach ($rows as $row) {
                    $data[] = (array) $row;
                }
                DB::table($table)->insert($data);
                $count += count($data);
            });

            $this->info("Successfully migrated {$count} records for table: {$table}.");
        }

        // 6. Re-enable foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('Foreign key checks re-enabled.');
        $this->info('Data migration from SQLite to MySQL completed successfully!');

        return 0;
    }
}

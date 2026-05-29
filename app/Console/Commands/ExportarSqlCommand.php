<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportarSqlCommand extends Command
{
    protected $signature = 'db:export-sql {--path=database/appsalon_completo.sql}';

    protected $description = 'Exporta la base de datos completa (estructura + datos) a un archivo SQL';

    public function handle(): int
    {
        $path = base_path($this->option('path'));
        $database = config('database.connections.mysql.database');

        $sql = [];
        $sql[] = '-- AppSalon - Script SQL completo';
        $sql[] = '-- Generado: '.now()->toDateTimeString();
        $sql[] = '-- Base de datos: '.$database;
        $sql[] = '';
        $sql[] = 'SET NAMES utf8mb4;';
        $sql[] = 'SET FOREIGN_KEY_CHECKS = 0;';
        $sql[] = "CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $sql[] = "USE `{$database}`;";
        $sql[] = '';

        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($row) => array_values((array) $row)[0])
            ->sort()
            ->values();

        foreach ($tables as $table) {
            $create = DB::select("SHOW CREATE TABLE `{$table}`")[0];
            $createSql = array_values((array) $create)[1];

            $sql[] = "-- Estructura: {$table}";
            $sql[] = "DROP TABLE IF EXISTS `{$table}`;";
            $sql[] = $createSql.';';
            $sql[] = '';

            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) {
                continue;
            }

            $columns = Schema::getColumnListing($table);
            $columnList = implode('`, `', $columns);

            $sql[] = "-- Datos: {$table}";
            foreach ($rows as $row) {
                $values = collect($columns)->map(function ($col) use ($row) {
                    $value = $row->{$col};
                    if ($value === null) {
                        return 'NULL';
                    }
                    if (is_int($value) || is_float($value)) {
                        return $value;
                    }

                    return "'".addslashes((string) $value)."'";
                })->implode(', ');

                $sql[] = "INSERT INTO `{$table}` (`{$columnList}`) VALUES ({$values});";
            }
            $sql[] = '';
        }

        $sql[] = 'SET FOREIGN_KEY_CHECKS = 1;';
        $sql[] = '-- Fin del script';

        file_put_contents($path, implode(PHP_EOL, $sql));

        $this->info("Exportado: {$path} ({$tables->count()} tablas)");

        return self::SUCCESS;
    }
}

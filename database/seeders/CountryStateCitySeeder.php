<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountryStateCitySeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Truncate tables in reverse order
        DB::table('cities')->truncate();
        DB::table('states')->truncate();
        DB::table('countries')->truncate();
        
        $this->command->info('🔄 Importing countries...');
        $this->importCountries();
        
        $this->command->info('🔄 Importing states...');
        $this->importStates();
        
        $this->command->info('🔄 Importing cities...');
        $this->importCities();
        
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
        
        // Show counts
        $this->command->info('✅ All location data imported successfully!');
        $this->command->info("📊 Countries: " . DB::table('countries')->count());
        $this->command->info("📊 States: " . DB::table('states')->count());
        $this->command->info("📊 Cities: " . DB::table('cities')->count());
    }
    
    private function importCountries()
    {
        $sqlPath = database_path('sql/countries.sql');
        
        if (!file_exists($sqlPath)) {
            $this->command->error('❌ Countries SQL file not found: ' . $sqlPath);
            return;
        }
        
        $sql = file_get_contents($sqlPath);
        
        // Remove comments and split queries
        $queries = array_filter(array_map('trim', explode(";\n", $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                try {
                    DB::unprepared($query);
                } catch (\Exception $e) {
                    $this->command->warn('Error in countries query: ' . $e->getMessage());
                }
            }
        }
        
        $this->command->info('   ✅ Countries imported: ' . DB::table('countries')->count());
    }
    
    private function importStates()
    {
        $sqlPath = database_path('sql/states.sql');
        
        if (!file_exists($sqlPath)) {
            $this->command->error('❌ States SQL file not found: ' . $sqlPath);
            return;
        }
        
        $sql = file_get_contents($sqlPath);
        
        // Remove comments and split queries
        $queries = array_filter(array_map('trim', explode(";\n", $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                try {
                    DB::unprepared($query);
                } catch (\Exception $e) {
                    $this->command->warn('Error in states query: ' . $e->getMessage());
                }
            }
        }
        
        $this->command->info('   ✅ States imported: ' . DB::table('states')->count());
    }
    
    private function importCities()
    {
        $sqlPath = database_path('sql/cities.sql');
        
        if (!file_exists($sqlPath)) {
            $this->command->error('❌ Cities SQL file not found: ' . $sqlPath);
            return;
        }
        
        // For large files, read line by line
        $handle = fopen($sqlPath, "r");
        $buffer = "";
        $count = 0;
        
        DB::beginTransaction();
        
        try {
            while (($line = fgets($handle)) !== false) {
                $buffer .= $line;
                
                if (strpos($line, ';') !== false) {
                    $query = trim($buffer);
                    if (!empty($query)) {
                        DB::unprepared($query);
                        $count++;
                        
                        if ($count % 1000 == 0) {
                            DB::commit();
                            $this->command->info("   Processed {$count} cities...");
                            DB::beginTransaction();
                        }
                    }
                    $buffer = "";
                }
            }
            
            DB::commit();
            fclose($handle);
            
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error('❌ Error importing cities: ' . $e->getMessage());
        }
        
        $this->command->info('   ✅ Cities imported: ' . DB::table('cities')->count());
    }
}
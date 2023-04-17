<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
class DBClearOldMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear_old_migration_table {database?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WARNING: to clear old migration table, run only once before new migration takes place, after that please delete this file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $databaseName = $this->argument('database');

        $merchants = DB::table('merchants')
        ->when($databaseName != '', function($query) use($databaseName) {
            $query->where('company_url',$databaseName);
        })
        ->select('company_url')
        ->get();

        $progressBar = $this->output->createProgressBar($merchants->count());

        $progressBar->start();

        $count = 0;
        if(isset($merchants) && count($merchants) > 0){

            foreach($merchants as $merchant){
                
                $db_name =  Str::lower($merchant->company_url).'_wosul';
    
                $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                $db = DB::select($query, [$db_name]);
               
                if (!empty($db)) {
    
                    config(["database.connections.mysql.database" => $db_name]);
                    DB::purge('mysql');
                    DB::reconnect('mysql');
                    
                    DB::table('migrations')->truncate();

                    $this->info("\n". $db_name ." updated successfully");            
                    $count++;

                }
            
            }

        }
      
        $progressBar->finish();
        $this->info("\n total ". $count ." merchants updated");
    }
}

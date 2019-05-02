<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class inputRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {path_to_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports CSV table to the database';

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
        $CSVFile = $this->argument('path_to_file');
        if(!file_exists($CSVFile) || !is_readable($CSVFile)){
            echo "Can't access CSV file"."\n";
            return false;
        }

        $header = null;
        $data = array();

        if (($handle = fopen($CSVFile,'r')) !== false){
            while (($row = fgetcsv($handle, 1000, ',')) !==false){
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        $dataCount = count($data);
        for ($i = 0; $i < $dataCount; $i ++){
            \App\Record::firstOrCreate($data[$i]);
        }
    }

}

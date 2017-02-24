<?php

namespace Basemkhirat\Elasticsearch\Commands;

use Illuminate\Console\Command;

use Basemkhirat\Elasticsearch\Facades\ES;

class ListIndicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:indices {--connection= : Elasticsearch connection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all indices';

    /**
     * Indices headers
     * @var array
     */
    protected $headers = ["health", "status", "index", "uuid", "pri", "rep", "docs.count", "docs.deleted", "store.size", "pri.store.size"];


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $connection = $this->option("connection") ? $this->option("connection") : "default";

        $indices = ES::connection($connection)->raw()->cat()->indices();

        if(count($indices) > 0) {

             $this->table($this->headers, $this->getIndices($indices));

        } else {

            $this->warn('No indices found.');

        }

    }

    /**
     * Convert lines into array
     * @param $indices
     * @return array
     */
    function getIndices($indices){

        $lines = explode("\n", trim($indices));

        $data = [];

        foreach ($lines as $line){

            $row = [];

            $line_array = explode(" ", trim($line));

            foreach ($line_array as $item){

                if(trim($item) != ""){

                    $row[] = $item;

                }

            }

            $data[] = $row;
        }

        return $data;

    }

}

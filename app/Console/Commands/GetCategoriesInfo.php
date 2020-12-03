<?php

namespace App\Console\Commands;

use App\Service\ParseService;
use Illuminate\Console\Command;

class GetCategoriesInfo extends Command
{

    protected $parserService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ParseService $parserService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parserService->getCategoriesToDB();
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Subcategory;
use App\Service\ParseService;
use Illuminate\Console\Command;

class GetSubcategoriesInfo extends Command
{

    protected $parserService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:subcategories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param ParseService $parserService
     */
    public function __construct(ParseService $parserService)
    {
        parent::__construct();
        $this->parserService = $parserService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Subcategory::truncate();
        $categories = $this->parserService->getCategoriesDB();
        $this->parserService->fillSubcategories($categories);
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Marka;
use App\Models\Subcategory;
use App\Service\ParseService;
use Illuminate\Console\Command;

class GetMarkaInfo2 extends Command
{
    protected $parserService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:markas2';

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
        $this->parserService = $parserService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subcategories = Subcategory::query()->where('id', '>', 3500)->get();
        $this->parserService->fillMarkas($subcategories);
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Marka;
use App\Models\Razmer;
use App\Service\ParseService;
use Illuminate\Console\Command;

class GetRazmenInfoTest extends Command
{

    protected $parserService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:razmer:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param ParseService $parseService
     */
    public function __construct(ParseService $parseService)
    {
        parent::__construct();
        $this->parserService = $parseService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Razmer::truncate();
        $markas = Marka::query()->where('subcategory_id', 103)->get();
        $this->parserService->fillRazmers($markas);
        return 0;
    }
}

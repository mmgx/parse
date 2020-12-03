<?php

namespace App\Console\Commands;

use App\Models\Razmer;
use App\Service\ParseService;
use Illuminate\Console\Command;

class GetRazmenInfo extends Command
{

    protected $parserService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:razmer';

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
        $markas = $this->parserService->getMarkas();
        $this->parserService->fillRazmers($markas);
        return 0;
    }
}

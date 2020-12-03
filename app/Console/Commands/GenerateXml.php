<?php

namespace App\Console\Commands;

use App\Service\ParseService;
use App\Service\XmlService;
use Illuminate\Console\Command;

class GenerateXml extends Command
{

    protected $parserService;
    protected $xmlService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:xml';

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
    public function __construct(ParseService $parseService, XmlService $xmlService)
    {
        parent::__construct();
        $this->parserService = $parseService;
        $this->xmlService = $xmlService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->xmlService->makeXmlBody();
        return 0;
    }
}

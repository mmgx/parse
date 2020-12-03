<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Service\ParseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class XmlController extends BaseController
{
    protected $parseService;

    public function __construct(ParseService $parseService)
    {
        $this->parseService = $parseService;
    }

    public function makeXml()
    {
        Artisan::call('make:xml');
    }
}

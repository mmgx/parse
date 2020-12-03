<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Service\ParseService;
use App\Models\Marka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MarkaController extends BaseController
{
    /**
     * @var ParseService
     */
    protected $parserService;

    /**
     * CategoryController constructor.
     * @param ParseService $parserService
     */
    public function __construct(ParseService $parserService)
    {
        $this->parserService = $parserService;
    }

    /**
     * Получить марки в базу
     */
    public function getMarkas()
    {
        Artisan::call('get:markas');
    }
}

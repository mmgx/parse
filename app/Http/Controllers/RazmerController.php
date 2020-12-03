<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Service\ParseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RazmerController extends BaseController
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
     * Получить размеры в базу
     */
    public function getMarkas()
    {
        Artisan::call('get:razmer');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Models\Subcategory;
use App\Service\ParseService;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SubcategoryController extends BaseController
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
     * Получить подкатегории в базу
     */
    public function getSubcategories()
    {
        Artisan::call('get:subcategories');
    }

}

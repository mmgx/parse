<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Service\ParseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CategoryController extends BaseController
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
     * Загрузить в базу главные категории
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getCategories()
    {
        Artisan::call('get:categories');
    }
}

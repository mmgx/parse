<?php
namespace App\Service;

use App\Models\Category;
use App\Models\Razmer;
use Carbon\Carbon;
use DOMDocument;

class XmlService extends Base\BaseService
{
    protected $parseService;

    public function __construct(ParseService $parseService)
    {
        $this->parseService = $parseService;
    }

    public function getFilesCount()
    {
        $offersCount = Razmer::query()->count();
        $splitCount = env('XML_SPLIT', 1000);
        $count = ($offersCount % $splitCount > 0) ? 1: 0;
        (int)$filesCount = $offersCount / $splitCount + $count;
        return $filesCount;
    }

    public function makeXmlBody()
    {
        for ($i = 1; $i <= $this->getFilesCount(); $i++) {
            dump($i);

            $this->makeFile();
        }

    }

    private function makeFile()
    {
        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d');
        $time = $datetime->setTimezone('Europe/Moscow')->format('H:i:s');
        $dateString = $date.'T'.$time.'+03:00';

        $dom = new domDocument("1.0", "utf-8");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $root = $dom->createElement("yml_catalog");
        $root->setAttribute('date',$dateString);
        $dom->appendChild($root);

        $shop = $dom->createElement("shop");
        $root->appendChild($shop);

        $name = $dom->createElement('name', 'BestSeller');
        $shop->appendChild($name);

        $company = $dom->createElement('company', 'Tne Best inc.');
        $shop->appendChild($company);

        $url = $dom->createElement('url', 'http://best.seller.ru');
        $shop->appendChild($url);

        $currencies = $dom->createElement('currencies');
        $shop->appendChild($currencies);

        $currencyId = $dom->createElement('currency');
        $currencyId->setAttribute('id', 'RUR');
        $currencyId->setAttribute('rate', '1');
        $currencies->appendChild($currencyId);

        $categoriesDom = $dom->createElement('categories');
        $shop->appendChild($categoriesDom);

        $categories = Category::query()->get();


        foreach($categories as $category){
            $categoryDom = $dom->createElement('category', $category->title);
            $categoryDom->setAttribute('id', $category->id);
            $categoriesDom->appendChild($categoryDom);

            foreach($category->subcategories as $subcategory){
                $subcategoryDom = $dom->createElement('category', $subcategory->title);
                $subcategoryDom->setAttribute('id', $subcategory->subcategory_id);
                $subcategoryDom->setAttribute('parentId', $subcategory->category_id);
                $categoriesDom->appendChild($subcategoryDom);
            }
        }

        $deliveryOptions = $dom->createElement('delivery-options');
        $shop->appendChild($deliveryOptions);

        $option = $dom->createElement('option');
        $option->setAttribute('cost', '200');
        $option->setAttribute('days', '1');
        $deliveryOptions->appendChild($option);

        $offers = $dom->createElement('offers');
        $shop->appendChild($offers);

        Razmer::chunk(env('XML_CHUNK', 100), function ($elements) use ($offers, $dom) {
            foreach ($elements as $element) {
                $offer = $dom->createElement('offer');
                $offer->setAttribute('id', $element->id);
                $offer->setAttribute('bid', 80);
                $offers->appendChild($offer);

                $offerName = $dom->createElement('name', 'Кабель ' .$element->title. ' в Москве');
                $offer->appendChild($offerName);

                $offerUrl = $dom->createElement('url', $this->parseService->getUrl($element->url));
                $offer->appendChild($offerUrl);

                $offerPrice = $dom->createElement('price', $element->price);
                $offer->appendChild($offerPrice);

                $offerCurrencyId = $dom->createElement('currencyId', 'RUR');
                $offer->appendChild($offerCurrencyId);

                $offerCategoryId = $dom->createElement('categoryId', $element->marka->subcategory->subcategory_id);
                $offer->appendChild($offerCategoryId);

                $offerPicture = $dom->createElement('picture', $this->parseService->getUrl($element->image));
                $offer->appendChild($offerPicture);

                $offerDelivery = $dom->createElement('delivery', 'true');
                $offer->appendChild($offerDelivery);

                $offerStore = $dom->createElement('store', 'true');
                $offer->appendChild($offerStore);

                $description = $dom->createElement('description');
                $offer->appendChild($description);

                $cdata = $dom->createCDATASection($element->description);
                $description->appendChild($cdata);
            }
        });

        dump($dom);
        $dom->save("file.xml"); // Сохраняем полученный XML-документ в файл
    }

}

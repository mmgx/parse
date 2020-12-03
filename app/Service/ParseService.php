<?php
namespace App\Service;

use App\Contracts\ParseServiceContract;
use App\Models\Category;
use App\Models\Marka;
use App\Models\Razmer;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\Collection;
use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Psr\Http\Client\ClientExceptionInterface;

class ParseService extends Base\BaseService implements ParseServiceContract
{
    /**
     * Получить домен сайта для парсинга
     * @return mixed
     */
    public function getSiteHost()
    {
        return env('PARSED_SITE', 'www.tdsevcable.ru');
    }

    /**
     * Получить протокол для сайта
     * @return string
     */
    public function getSiteProtocol()
    {
        return 'https://';
    }

    /**
     * Получить домен сайта вместе с
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->getSiteProtocol() . $this->getSiteHost();
    }

    /**
     * Путь к странице с категориями
     * @return string
     */
    public function getCategoryPage()
    {
        return $this->getSiteUrl() .'/catalog.html';
    }

    /**
     * Получить DOM-элементы указанной страницы и класса
     * @param string $link
     * @param string $class
     * @return mixed|Dom\Node\Collection|null
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ClientExceptionInterface
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    public function getDomElementsByClass($link, $class)
    {
        $dom = new Dom;
        $dom->loadFromUrl($link);
        try {
            return $dom->getElementsByClass($class);

        } catch (ChildNotFoundException $e) {
            throw new ChildNotFoundException('ChildNotFoundException');
        } catch (NotLoadedException $e) {
            throw new ChildNotFoundException('NotLoadedException');
        }
    }

    /**
     * Получить DOM-элементы указанной страницы и ID
     * @param string $page
     * @param string $id
     * @return mixed|Collection|null
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ClientExceptionInterface
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    public function getDomElementsById($page, $id)
    {
        $dom = new Dom;
        $dom->loadFromUrl($page);
        try {
            return $dom->getElementById($id);

        } catch (ChildNotFoundException $e) {
            throw new ChildNotFoundException('ChildNotFoundException');
        } catch (NotLoadedException $e) {
            throw new ChildNotFoundException('NotLoadedException');
        }
    }

    /**
     * Получить имя категории
     * @param HtmlNode $node
     * @return mixed
     * @throws ChildNotFoundException
     */
    public function getCategoryTitle(HtmlNode $node)
    {
        return $node->find('.catalog_item_title_body text')->text;
    }

    /**
     * Получить изображения категорий
     * @param HtmlNode $node
     * @return mixed
     * @throws ChildNotFoundException
     */
    public function getCategoryPicture(HtmlNode $node)
    {
        return $node->find('.catalog_item_img_body img')->src;
    }

    /**
     * Получить ссылку на раздел категории
     * @param HtmlNode $node
     * @return mixed
     * @throws ChildNotFoundException
     */
    public function getCategoryLink(HtmlNode $node)
    {
        return $node->find('.catalog_item_title_body a')->href;
    }

    /**
     * Получить массив с элементами (заголовок, изображение, ссылка)
     * @param Collection $node
     * @return array
     * @throws ChildNotFoundException
     */
    public function getArrayItems(Collection $node)
    {
        $arr = [];
        $counter = 1;
        foreach ($node as $item)
        {
            $arr[] = [
                'id' => $counter,
                'title' => $this->getCategoryTitle($item),
                'picture' => $this->getCategoryPicture($item),
                'url' => $this->getCategoryLink($item),
            ];
            $counter++;
        }
        return $arr;
    }

    /**
     * Получить объекты категорий
     * @param $link
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ClientExceptionInterface
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    public function getCategoryItems($link)
    {
        $items = $this->getDomElementsByClass($link, 'catalog');
        $categories = $items->find('.catalog_item');
        return $this->getArrayItems($categories);
    }

    /**
     * Загрузить в базу все основные категории
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ClientExceptionInterface
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    public function getCategoriesToDB()
    {
        Category::truncate();
        DB::table('categories')->delete();
        $categories = $this->getCategoryItems($this->getCategoryPage());

        foreach ($categories as $category)
        {
            $newCategory = new Category([
                'title' => $category['title'],
                'image' => $category['picture'],
                'url' => $category['url'],
            ]);
            $newCategory->save();
        }
    }


    /**
     * Получить коллекцию главных категорий
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesDB()
    {
        return Category::query()->get();
    }

    /**
     * Запулнить подкатегории выбранной категории
     * @param Category $category
     * @return bool
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ClientExceptionInterface
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    public function fillSubcategory(Category $category)
    {
        $page = $this->getDomElementsByClass($this->getUrl($category->url), 'cable_text');
        $subcategories = $this->getDomElementsByClass($this->getUrl($category->url), 'cabel_it li a');
        $id = 1;

        for ($i = 1; $i <= $subcategories->count()-1; $i++) {
            if ($subcategories[$i]->href){
                $newSubcategory = new Subcategory([
                    'subcategory_id' => ($category->id * 10000) + $id,
                    'category_id' => $category->id,
                    'title' => $subcategories[$i]->text,
                    'url' => $subcategories[$i]->href,
                    'image' => $page->find('#largeImage')->src,
                ]);
                $newSubcategory->save();
                $id++;
            }
        }
        return true;
    }

    /**
     * Получить полный путь к ссылке с учетом домена
     * @param $url
     * @return string
     */
    public function getUrl($url)
    {
        return $this->getSiteUrl() . '/' .$url;
    }

    /**
     * Получение списка марок
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|null[]|object[]
     */
    public function getMarkas()
    {
        return Marka::query()->first()->get();
    }

    public function fillRazmers(\Illuminate\Database\Eloquent\Collection $markas)
    {
        foreach ($markas as $marka) {
            $this->fillRazmer($marka);
        }
        return true;
    }

    /**
     * Получить заголовок подкатегории
     * @param HtmlNode $node
     * @return string
     */
    private function getSubcategoryTitle(HtmlNode $node)
    {
        return $node->find('a text')->text() ?: '+';
    }

    /**
     * Получить адрес подкатегории
     * @param Category $category
     * @return string
     */
    private function getSubcategoryUrl($node)
    {
        return $node->find('a')->href;
    }

    /**
     * Получить изображение подкатегории
     * @param $subcategory
     */
    private function getSubcategoryImage($node)
    {
        return $node->find('#largeImage')->src;
    }


    /**
     * Заполнить подкатегории для всех категорий
     * @param \Illuminate\Database\Eloquent\Collection $categories
     * @return bool
     */
    public function fillSubcategories(\Illuminate\Database\Eloquent\Collection $categories)
    {
        foreach ($categories as $category) {
                $this->fillSubcategory($category);
        }
        return true;
    }


    /**
     * Получение коллекции подкатегорий
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getSubcategories()
    {
        return Subcategory::query()->get();
    }

    public function fillMarkas(\Illuminate\Database\Eloquent\Collection $subcategories)
    {
        foreach ($subcategories as $subcategory) {
                $this->fillMarkaDB($subcategory);
        }
        return true;
    }

    public function fillMarkaDB(Subcategory $subcategory)
    {
        $workspace = $this->getDomElementsByClass($this->getUrl($subcategory->url), 'second_content .workspace');
//        $workspace = $this->getDomElementsByClass('http://web.loc/marka4.html', 'second_content .workspace');
        $elements = $workspace->find('.card_wind_over .basket_form');

        if ($elements->count() > 0){

            $description = $workspace->find('#card_wind_1 p');
            $descriptionText = ( $description->count() > 0) ? $description->innerHtml : null;

            return $this->fillMarkaDBRecord($elements, $subcategory, $descriptionText);
        }
        return;
    }

    /**
     * @param $elements
     * @param Subcategory $subcategory
     * @param $description
     * @return bool
     */
    protected function fillMarkaDBRecord($elements, Subcategory $subcategory, $description)
    {
        $id = 1;
        foreach ($elements as $element) {

                $price = $element->find('.table_sech_pod')[1]->text ?: null;
                $title = $element->find('a text')->text ?: null;
                $link = $element->find('a')[0]->href;

                $newMarkas = new Marka([
                    'marka_id' => ($subcategory->id * 100 + $id) ?: null,
                    'subcategory_id' => $subcategory->subcategory_id?: null,
                    'title' => $title ?: null,
                    'image' => $subcategory->image ?: null,
                    'price' => $price ?: null,
                    'url' => $link ?: null,
                    'description' => $description ?: null,
                ]);
                $newMarkas->save();
                $id++;
        }

        return true;
    }

    /**
     * Получить id последнего элемента в таблице
     * @param $model
     * @param $column
     * @param $id
     * @return mixed
     */
    public function getLastTableId($model, $column, $id)
    {
        return $model::query()->orderBy($column, 'desc')->first()->$id;
    }

    private function fillRazmer($razmer)
    {
        $page = $this->getDomElementsByClass($this->getUrl($razmer->url), 'second_content .workspace');
            $description = $page->find('#card_wind_1');
            $specification = $page->find('#card_wind_2');
            $descriptionText = ( $description->count() > 0) ? $description->innerHtml : null;
            $specificationText = ( $specification->count() > 0) ? $specification->innerHtml : null;

            return $this->fillRazmerDbRecord($page, $razmer, $descriptionText, $specificationText);
    }

    private function fillRazmerDbRecord($element, $razmer, $descriptionText, $specificationText)
    {
        $price = $element->find('.moon span')->text ?: null;
        $id = 1;

        $newRazmer = new Razmer([
            'marka_id' => $razmer->marka_id ?: null,
            'title' => $razmer->title ?: null,
            'image' => $razmer->image ?: null,
            'price' => $price ?: null,
            'specifications' => $specificationText ?: null,
            'description' => $descriptionText ?: null,
        ]);
        $newRazmer->save();
        return true;
    }
}

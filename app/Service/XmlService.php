<?php
namespace App\Service;

use App\Models\Category;
use Carbon\Carbon;
use DOMDocument;

class XmlService extends Base\BaseService
{
    protected $parseService;

    public function __construct(ParseService $parseService)
    {
        $this->parseService = $parseService;
    }

    public function makeXmlBody()
    {
        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d');
        $time = $datetime->setTimezone('Europe/Moscow')->format('H:i:s');
        $dateString = $date.'T'.$time.'+03:00';

        $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
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


        $logins = array("User1", "User2", "User3"); // Логины пользователей
        $passwords = array("Pass1", "Pass2", "Pass3"); // Пароли пользователей
//        for ($i = 0; $i < count($logins); $i++) {
//            $id = $i + 1; // id-пользователя
//            $user = $dom->createElement("user"); // Создаём узел "user"
//            $user->setAttribute("id", $id); // Устанавливаем атрибут "id" у узла "user"
//            $login = $dom->createElement("login", $logins[$i]); // Создаём узел "login" с текстом внутри
//            $password = $dom->createElement("password", $passwords[$i]); // Создаём узел "password" с текстом внутри
//            $user->appendChild($login); // Добавляем в узел "user" узел "login"
//            $user->appendChild($password);// Добавляем в узел "user" узел "password"
//            $root->appendChild($user); // Добавляем в корневой узел "users" узел "user"
//        }








        dd($dom);
        $dom->save("users.xml"); // Сохраняем полученный XML-документ в файл
    }

}

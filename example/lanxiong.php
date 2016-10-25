<?php
/**
 * Created by PhpStorm.
 * User: zoco
 * Date: 16/7/5
 * Time: 15:45
 */

/**
 * @param $array
 * @return array
 */
function arrayCastRecursive($array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = arrayCastRecursive($value);
            }
            if ($value instanceof stdClass) {
                $array[$key] = arrayCastRecursive((array)$value);
            }
        }
    }
    if ($array instanceof stdClass) {
        return arrayCastRecursive((array)$array);
    }
    return $array;
}

require __DIR__ . '/bootstrap.php';
use Illuminate\Database\Capsule\Manager as Capsule;

$config = [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'test',
    'username'  => 'root',
    'password'  => '123456789',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
];
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();

$client = new GuzzleHttp\Client();
for($i = 2315;$i < 2415;$i++) {
    $arr = [];
    $res = $client->request('GET', 'http://www.lanxiongsports.com/?c=posts&a=view&id='.$i);
    $html = $res->getBody();
    $html = new \HtmlParser\ParserDom($html);
    $a = $html->find('.txt h1');
    if(empty($a[0]->node->nodeValue)) {
        continue;
    }
    $b = $html->find('.index_bottom');
//    var_dump($a[0]->node->nodeValue);
//    var_dump($b[0]->node->textContent);
    $arr['user_id'] = 1;
    $arr['title'] = $a[0]->node->nodeValue;
    $arr['content'] = trim($b[0]->node->textContent,"\"");
    $arr['tags'] = rand(1,16);
    $arr['pic'] = '';
    $arr['created_at'] = time();
    $arr['updated_at'] = time();

//    var_dump($arr);
    Capsule::table('posts')->insert($arr);
}
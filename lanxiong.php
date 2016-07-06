<?php
/**
 * Created by PhpStorm.
 * User: zoco
 * Date: 16/7/5
 * Time: 15:45
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

require __DIR__.'/bootstrap.php';
use Illuminate\Database\Capsule\Manager as Capsule;

$config = [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'test_1',
    'username'  => 'root',
    'password'  => '123456789',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
];
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$moto = Capsule::table('d_posts')->select('*')->find(1);
$moto = arrayCastRecursive($moto);
var_dump($moto);

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
//    $arr = $moto;
    $arr['post_date'] = $moto['post_date'];
    $arr['to_ping'] = $moto['to_ping'];
    $arr['pinged'] = $moto['pinged'];
    $arr['post_content_filtered'] = $moto['post_content_filtered'];

    $arr['post_date_gmt'] = $moto['post_date_gmt'];
    $arr['post_excerpt'] = $moto['post_excerpt'];
    $arr['post_modified'] = $moto['post_modified'];
    $arr['post_modified_gmt'] = $moto['post_modified_gmt'];
    $arr['post_title'] = $a[0]->node->nodeValue;
    $arr['id'] = $i;
    $arr['post_content'] = trim($b[0]->node->textContent,"\"");
//    var_dump($arr);
    Capsule::table('d_posts')->insert($arr);


}
//echo $res->getStatusCode();
// "200"
//echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
// {"type":"User"...'

//// Send an asynchronous request.
//$request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
//$promise = $client->sendAsync($request)->then(function ($response) {
//    echo 'I completed! ' . $response->getBody();
//});
//$promise->wait();


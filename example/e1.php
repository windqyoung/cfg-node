<?php


use Wqy\Config\Node;
use Wqy\Config\NodeFactory;

require __DIR__ . '/../vendor/autoload.php';


$cfg = [
    'a' => 'avalue',
    'a.a' => 'a2.value',
    'a.a.a' => 'a3.value',
    'a.a.b' => 'b1.value',
];


$node = NodeFactory::create($cfg);



$node->user->name = 'hy';
$node->user->age = 32;
$node->user = 'user1';

$node['a.b.c'] = 'ccc';
$node['a.b.c.d'] = 'eee';

var_dump($node);
// var_dump(isset($node['a.b.c']));
// unset($node['a.b.c']);
// var_dump($node);
// var_dump($node['user.name']);
// var_dump(json_encode($node));
foreach ($node as $k => $v) {
//     var_dump($k, $v->getValue());
}

$node->foreachRecursive(function ($n, $keys) {
    var_dump($keys, $n->getValue());
//     var_dump(func_get_args());
    echo '<hr>';
});

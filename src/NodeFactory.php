<?php


namespace Wqy\Config;


class NodeFactory
{
    public static function create($cfg)
    {
        $n = new Node();
        foreach ($cfg as $k => $v) {
            $n[$k] = $v;
        }
        return $n;
    }
}

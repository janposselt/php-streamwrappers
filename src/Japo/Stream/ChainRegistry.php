<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

namespace Japo\Stream;

class ChainRegistry
{

    protected $streamsById = [];
    protected static $registries = [];

    public function register($id, $streams)
    {
        $this->streamsById[$id] = $streams;
    }

    public function get($id)
    {
        return $this->streamsById[$id];
    }

    public function has($id)
    {
        return isset($this->streamsById[$id]);
    }

    public static function getRegistryInstance($name)
    {
        if (!isset(self::$registries[$name])) {
            self::$registries[$name] = new ChainRegistry();
        }

        return self::$registries[$name];
    }

}

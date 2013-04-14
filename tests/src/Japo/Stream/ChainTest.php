<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

require_once __DIR__ . '/../../../../src/Japo/Stream/Chain.php';

class StringTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        stream_wrapper_register('chain', '\\Japo\\Stream\\Chain');
    }

    public function testSimpleChain()
    {
        $context = stream_context_create([
            'chain' => [
                'streams' => [
                    fopen('data://text/plain,' . 'foo', 'r'),
                    fopen('data://text/plain,' . 'bar', 'r'),
                ]
            ]
        ]);
        
        $content = file_get_contents('chain://', false, $context);

        $this->assertEquals('foobar', $content);
    }
}

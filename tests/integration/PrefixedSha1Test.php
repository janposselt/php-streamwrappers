<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

require_once __DIR__ . '/../../src/Japo/Stream/Chain.php';
require_once __DIR__ . '/../../src/Japo/Stream/String.php';

/**
 * A test to show how to use the stream chain to prefix data that is read
 * by a stream wrapper supporting function (sha1_file).
 */
class PrefixedSha1Test extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        stream_wrapper_register('chain', '\\Japo\\Stream\\Chain');
        stream_wrapper_register('string', '\\Japo\\Stream\\String');
    }

    public function testSha1File()
    {

        $sha1registry = \Japo\Stream\ChainRegistry::getRegistryInstance('sha1');
        $sha1registry->register('1', [
            fopen('data://text/plain,' . 'prefix', 'r'),
            fopen('data://text/plain,' . 'sha1_content', 'r'), // this would be a real file
        ]);

        $sha1_result = sha1_file('chain://sha1/1');

        $expected = '2d05aab135fce96ca213a7051156481f4f5ea661';

        $this->assertEquals($expected, $sha1_result);
    }

}

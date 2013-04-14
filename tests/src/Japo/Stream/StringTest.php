<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

require_once __DIR__ . '/../../../../src/Japo/Stream/String.php';

class StringTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        stream_wrapper_register('string', '\\Japo\\Stream\\String');
    }

    public function testStringCreatedWithoutValue()
    {
        $content = file_get_contents('string://');
        
        $this->assertEquals('', $content);
    }
    
    public function testStringContainsInitialValue()
    {
        $content = file_get_contents('string://foo+bar');
        
        $this->assertEquals('foo bar', $content);
    }
    
    public function testStringSupportsPrepend()
    {
        $stream = fopen('string://foo+bar', 'r+');
        
        fwrite($stream, 'pre ');
        
        fseek($stream, 0);
        
        $this->assertEquals('pre foo bar', fread($stream, 8192));
    }
    
    public function testStringSupportsAppend()
    {
        $stream = fopen('string://foo+bar', 'a+');
        
        fwrite($stream, ' post');
        
        fseek($stream, 0);
        
        $this->assertEquals('foo bar post', fread($stream, 8192));
    }
}

<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

namespace Japo\Stream;

abstract class AbstractStream
{

    protected $readonly = false;
    protected $writeonly = false;

    protected function initStream($mode)
    {
        $modePattern = '/(?P<mode>[rwaxc]?)(?P<type>[bt]?)(?P<pointer>\\+?)/';

        $matches = [];
        preg_match($modePattern, $mode, $matches);

        $accessMode = $matches['mode'] . $matches['pointer'];

        switch ($accessMode) {
            case 'r':
                $this->readonly = true;
                break;
            case 'w':
            case 'c':
                $this->writeonly = true;
                break;
            case 'w+':
            case 'c+':
                $this->clear();
                break;
            case 'a':
                $this->writeonly = true;
                $this->position = strlen($this->content);
            case 'a+':
                $this->position = strlen($this->content);
                break;
            case 'x':
            case 'x+':
                if ($this->content) {
                    trigger_error('String already filled', E_WARNING);
                    return false;
                }
                if ($accessMode == 'x') {
                    $this->writeonly = true;
                }
                break;
        }

        return true;
    }

    abstract protected function clear();
    
            
    public function stream_stat()
    {
        $stat = [
            'dev' => 12,
            'ino' => 0,
            'mode' => 33206,
            'nlink' => 1,
            'uid' => 0,
            'gid' => 0,
            'rdev' => -1,
            'size' => -1,
            'atime' => 0,
            'atime' => 0,
            'mtime' => 0,
            'ctime' => 0,
            'blksize' => -1,
            'blocks' => -1
            
        ];
        
        return array_merge(array_values($stat), $stat);
        
    }
    
    abstract public function stream_open($path, $mode, $options, &$opened_path);
    abstract public function stream_read($count);
    abstract public function stream_write($data);
    abstract public function stream_tell();
    abstract public function stream_eof();
    abstract public function stream_seek($offset, $whence);

}

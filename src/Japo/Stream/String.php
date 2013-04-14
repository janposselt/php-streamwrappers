<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

namespace Japo\Stream;

require_once __DIR__ . '/AbstractStream.php';


class String extends AbstractStream
{

    protected $position = 0;
    protected $content = '';

    protected function clear() {
        $this->content = '';
    }
        
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
            'size' => strlen($this->content),
            'atime' => 0,
            'atime' => 0,
            'mtime' => 0,
            'ctime' => 0,
            'blksize' => -1,
            'blocks' => -1
            
        ];
        
        return array_merge(array_values($stat), $stat);
        
    }
    
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        
        $this->content = urldecode($url['host']);
        
        $this->initStream($mode);
        
        return true;
    }

    public function stream_read($count)
    {
        if ($this->writeonly) {
            throw new \Exception('stream is in write-only mode');
        }
        
        $content = substr($this->content, $this->position, $count);
        $this->position = min(strlen($this->content), $this->position + $count);

        return $content;
    }

    public function stream_write($data)
    {
        if ($this->readonly) {
            throw new \Exception('stream is in read-only mode');
        }
        
        $left = substr($this->content, 0, $this->position);
        $right = substr($this->content, $this->position);

        $this->content = $left . $data . $right;

        $this->position += strlen($data);

        return strlen($data);
    }

    public function stream_tell()
    {
        return $this->position;
    }

    public function stream_eof()
    {
        return $this->position >= strlen($this->content);
    }

    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->content) && $offset >= 0) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen($this->content) + $offset >= 0) {
                    $this->position = strlen($this->content) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

}
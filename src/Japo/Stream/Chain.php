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
require_once __DIR__ . '/ChainRegistry.php';

class Chain extends AbstractStream
{

    protected $position = 0;
    protected $streamIndex = -1;
    protected $streams = [];

    protected function clear()
    {
        throw new \Exception('chains are not writable');
    }

    public function stream_eof()
    {
        $r = $this->streamIndex >= count($this->streams) - 1;
        ;
        return $r;
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $success = $this->initStream($mode);

        if ($this->context) {
            $context = stream_context_get_options($this->context);
            if (isset($context['chain']['streams'])) {
                $this->streams = $context['chain']['streams'];
            }
        }

        if (!$this->streams) {
            $data = parse_url($path);
            $registryName = $data['host'];
            $id = ltrim($data['path'], '/');

            $registry = ChainRegistry::getRegistryInstance($registryName);
            if ($registry->has($id)) {
                $this->streams = $registry->get($id);
            }
        }

        if (!$this->streams) {
            throw new \Exception('no streams attached');
        }

        if (!$this->readonly) {
            throw new \Exception('chain must be read-only');
        }

        fseek($this->streams[0], 0);
        
        $this->streamIndex = 0;

        return $success;
    }

    public function stream_read($count)
    {
        $content = '';

        $pending = $count;

        while ($pending >= 0) {
            if ($this->streamIndex >= count($this->streams)) {
                break;
            }

            $tmp = fread($this->streams[$this->streamIndex], $count);
            $content .= $tmp;

            $read = strlen($tmp);

            $pending -= $read;

            if ($pending) {
                ++$this->streamIndex;
                if ($this->streamIndex < count($this->streams)) {
                    fseek($this->streams[$this->streamIndex], 0);
                    break;
                }
            }
        }

        $this->position += strlen($content);

        return $content;
    }

    public function stream_seek($offset, $whence)
    {
        throw new \Exception('seeking not supported');
    }

    public function stream_tell()
    {
        return $this->position;
    }

    public function stream_write($data)
    {
        throw new \Exception('chains are not writable');
    }

}


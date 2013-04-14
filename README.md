# Two Handy Stream Wrappers

## String Stream

Create strings by using stream functions (`fwrite`, `file_put_contents`, ...).

    $string = file_put_contents('string://', 'hello world');
    echo $string; // hello world

    $handler = fopen('string://foo+bar');
    echo fread($handler, 8192); // foo bar

## Stream Chain

Wraps multiple streams to a single read-only stream. Chains can be configured
by using a context (`stream_context_create`) or using the ChainRegistry. I
used this wrapper to generate sha1 sums of file contents that needed to be prefixed
with some header information before doing the hash.

See unit tests for examples.
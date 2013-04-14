<?php

/*
 * Work in progress.
 * 
 * (c) 2013 Jan Posselt <public@janposselt.de>
 * 
 * See LICENCE file for full copyright and licence information.
 */

// PSR-0 autoloading is also supported

require_once __DIR__ . '/src/Japo/Stream/AbstractStream.php';
require_once __DIR__ . '/src/Japo/Stream/Chain.php';
require_once __DIR__ . '/src/Japo/Stream/ChainRegistry.php';
require_once __DIR__ . '/src/Japo/Stream/String.php';

// register wrappers
stream_wrapper_register('chain', '\\Japo\\Stream\\Chain');
stream_wrapper_register('string', '\\Japo\\Stream\\String');
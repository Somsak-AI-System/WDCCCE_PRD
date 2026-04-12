<?php

// Sample configuration for the logging library

$config = array(
    'simple' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{message}",
        'file_path' => ''
    ),
    'file_debug' => array(
        'level' => 'DEBUG',
         'type' => 'file',
        'format' => "{date} - {level}: {message}",
        'file_path' => '../log/debug'
    ),
	
);
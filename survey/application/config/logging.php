<?php

// Sample configuration for the logging library

$config = array(
    'simple' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{message}",
        'file_path' => ''
    ),
    'email_criticals' => array(
        'level' => 'CRITICAL',
        'type' => 'email',
        'format' => "{date} - {level}: {message}",
        'to' => 'sjwood25890@gmail.com',
        'from' => 'noreply@example.com',
        'subject' => 'New critical logging message'
    ),
    'file_debug' => array(
        'level' => 'DEBUG',
         'type' => 'file',
        'format' => "{date} - {level}: {message}",
        'file_path' => '../log/debug'
    )
);
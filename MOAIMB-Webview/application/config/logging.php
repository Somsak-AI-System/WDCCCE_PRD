<?php

// Sample configuration for the logging library

$config = array(
    'info' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'error' => array(
        'level' => 'ERROR',
        'type' => 'file',
        'format' => "{date} - {level}: {message}",
        'file_path' => '../../log/error'
    ),
    'email_criticals' => array(
        'level' => 'CRITICAL',
        'type' => 'email',
        'format' => "{date} - {level}: {message}",
        'to' => 'takko2526@hotmail.com',
        'from' => 'panudda@aisyst.com',
        'subject' => 'PATPPC:[INT] Cannot connect to server'
    ),
    'file_debug' => array(
        'level' => 'DEBUG',
        'type' => 'file',
        'format' => "{date} - {level}: {message}",
        'file_path' => '../../log/debug'
    ),
    'Access' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'User' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'Profile' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'Login' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'Quotes' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'Projects' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    ),
    'Samplerequisition' => array(
        'level' => 'INFO',
        'type' => 'file',
        'format' => "{date} - {message}",
        'file_path' => '../../log/info'
    )
);
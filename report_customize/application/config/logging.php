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
    ),
	'cancel_checkin' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'cancel_weightin' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'cancel_weightout' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'cancel_gateout' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'weightout' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'weightin' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'gateout' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'cancel_gatein' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'cancel_reject' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'cancel_shipment' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	),
	'BulkControl' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
,	'Bagcurrentload' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,
	'BagRealtimeLoadingData' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,
	'ParkingExitRelease' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,
	'TruckOutbound' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
		,
	'TruckInbound' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,
	'ParkingExit' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)
	,	
	'Queue' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,	
	'ChannelChange' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	,	
	'SiloMaster' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,	
	'ManageStation' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,		
		'DispatcherMaster' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)				
	,		
		'MachineMaster' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)		
,	
'Users' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
	,	
'CallVAJA' => array(
			'level' => 'INFO',
			'type' => 'file',
			'format' => "{date} - {message}",
			'file_path' => '../../log/info'
	)	
);
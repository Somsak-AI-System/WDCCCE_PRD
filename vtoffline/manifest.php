<?php
header('Content-type: text/plain');

function getVersion($files) {
	$version = 0;
	foreach($files as $filename) {
		if(file_exists($filename)) {
			$v = filemtime($filename);
			if($v > $version) $version = $v;
		}
	}
	return $version;
}

$files = Array(
	'vtwsclib/third-party/js/jquery.js',
	'vtwsclib/third-party/js/md5.js',
	'vtwsclib/Vtiger/WSClient.js',
	'vtwsclib/Vtiger/Gears.js',
	//'vtwsclib/third-party/js/gears_init.js',
	'js/jquery-jtemplates.js',
	'js/vtoffline.lang.js',
	'js/vtoffline.js',
	'vtoffline.css',

	'js/templates/Home.jstpl',
	'js/templates/Login.jstpl',
	'js/templates/CreateView.jstpl',
	'js/templates/ModuleView.jstpl',
	'js/templates/ColumnsView.jstpl',
	'js/templates/SyncView.jstpl',

	'index.php'
);

$entries = '';
for($index = 0; $index < count($files); ++$index) {
	$file = $files[$index];
	$entries .= '{"url" : "' . $file . '", "src" : "' . $file . '"}';
	if($index != count($files)-1) $entries .= ",";
}

echo '{
	"betaManifestVersion" : 1,
	"version" : "' . getVersion($files) .'",
	"entries" : [' . $entries .']
}';
?>

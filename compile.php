<?php
set_time_limit(0);
header('Content-Type: text/plain');

$target_file = 'compiled.xml';
$template = file_get_contents('head.xml') . file_get_contents('body.xml');
$comments_pattern = "/<!--\s*#file=([a-zA-z0-9]+\.xml)\s*-->/";

function replace_comments_callback($matches){
	global $comments_pattern;
	$file = $matches[1];
	echo "Tratando de incluir $file...\n";
	if( file_exists($file) ) {
		$contents = file_get_contents($file);
		$contents = preg_replace_callback($comments_pattern, 'replace_comments_callback', $contents);
		echo "$file incluído\n";
		return $contents;
	}
	return '';
}

echo "Empezando a compilar la plantilla\n";
$template = preg_replace_callback($comments_pattern, 'replace_comments_callback', $template);

if( $target_file ) {
	file_put_contents($target_file, $template);
}

echo "Compilado! Revisa $target_file";
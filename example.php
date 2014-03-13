<?php
include('REST.php');
include('Flickr.php');

$flickr		=	new Flickr('API_KEY', 'SECRET', 'RADIUS_UNITS');
$response	=	$flickr->get_nearPhotos(44.842466, -0.574409);

print 	'<pre>';
print_r($response);
print 	'</pre>';
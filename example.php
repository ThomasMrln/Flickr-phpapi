<?php
include('REST.php');
include('Flickr.php');

$flickr		=	new Flickr('API_KEY', 'SECRET', 'RADIUS_UNIT');
$response	=	$flickr->get_photoUrls(11468258323);

print 	'<pre>';
print_r($response);
print 	'</pre>';
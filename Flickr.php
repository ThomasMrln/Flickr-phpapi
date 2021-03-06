<?php

/**
 *	@resume		PHP Class for send request with the FlickR API
**/
class Flickr extends Exception {

	private $api_key;
	private $api_secret;
	private $radius_units;
	
	/**
	 *	@resume		Constructor of the class, put into your configurations (api key, secret and radius_units for geo-search)
	**/
	public function __construct($api_key, $secret, $radius_units='km') {
			$this->api_key		=	$api_key;
			$this->api_secret	=	$secret;
			$this->radius_units	=	$radius_units;
	}
	
	public function __get($el) 			{	return $this->$el;		}
	public function __set($el, $value) 	{	$this->$el	=	$value;	}
	
	
	/**
	 *	@resume		Get photos from a geo-place.
	 *	@params		latidute, longitude, the radius for the geo-search, and the number of photos returned.
	 *	@return		An array which contain some information from the photos and his owner
	**/
	public function get_nearPhotos($lat, $lon, $radius = 1, $count = 10) {
			$rest 		=	new REST();
			$response	=	$rest	->setUrl('https://api.flickr.com/services/rest/?method=flickr.photos.search'
			  						    		.'&api_key='.$this->api_key
			  						    		.'&lat='.$lat
			  						    		.'&lon='.$lon
			  						    		.'&radius='.$radius
			  						    		.'&radius_units='.$this->radius_units
			  						    		.'&per_page=10'
			  						    		.'&format=json'
			  						    		.'&nojsoncallback=1')
			  						->get();
			  	
			$tab_response	=	array();
			$response		=	json_decode($response['content']);
			
			foreach ($response->photos->photo as $photo) {
					$tab_response[]	=	array(
											'name'		=>	$photo->title,
											'id'		=>	$photo->id,
											'photo_url'	=>	array(
													'small'		=>	'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_s.jpg',
													'medium'	=>	'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_z.jpg',
													'high'		=>	'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_b.jpg'
											),
											'user_info'	=>	$this->get_userInfo($photo->owner)
										);
			}
			
			return $tab_response;
	}
	
	
	/**
	 *	@resume		Allow to obtain information of a user
	 *	@params		The user_id
	 * 	@return 	An array which contain information of the user
	**/
	public function get_userInfo($user_id) {
			$rest		=	new REST();
			$response	=	$rest	->setUrl('https://api.flickr.com/services/rest/?method=flickr.people.getInfo'
												.'&api_key='.$this->api_key
												.'&user_id='.$user_id
			  						    		.'&format=json'
			  						    		.'&nojsoncallback=1')
									->get();
			
			$response 		=	json_decode($response['content']);
			
			return 	$tab_response	=	array(
			    							'id'			=>	$response->person->id,
			    							//'is_pro'		=>	$response->person->ispro,
			    							'name'			=>	utf8_decode($response->person->realname->_content),
			    							'location'		=>	utf8_decode($response->person->location->_content),
			    							'url_profil'	=>	$response->person->profileurl->_content,
			    							'url_photos'	=>	$response->person->photosurl->_content,
			    							'nb_photos'		=>	$response->person->photos->count->_content
			    						);
	}
	
	
	/**
	 *	@resume		Get url of the source with size of a photo
	 *	@params		photo_id
	 *	@return 	An array with size of the photo and the source url
	**/
	public function get_photoUrls($photo_id) {
			$rest		=	new REST();
			$response	=	$rest	->setUrl('https://api.flickr.com/services/rest/?method=flickr.photos.getSizes'
												.'&api_key='.$this->api_key
												.'&photo_id='.$photo_id
			  						    		.'&format=json'
			  						    		.'&nojsoncallback=1')
									->get();
			
			$response 		=	json_decode($response['content']);

			$tab_response	=	array();
			foreach ($response->sizes->size as $size) {
					$tab_response[]	=	array(
							'width'	=>	$size->width,
							'height'=>	$size->height,
							'url' 	=>	$size->url
					);
			}
			
			return $tab_response;
	}
	
	
}
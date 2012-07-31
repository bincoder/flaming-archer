<?php

namespace Tsf;

/**
 * Tsf Library
 * 
 * @category Tsf
 * @package Tsf_Flickr
 * @author Jeremy Kendall <jeremy@jeremykendall.net>
 */

/**
 * Flickr class
 * 
 * @category Tsf
 * @package Tsf_Flickr
 * @author Jeremy Kendall <jeremy@jeremykendall.net>
 */
class Flickr
{

    /**
     * Flickr API key
     * 
     * @var string 
     */
    private $key;

    /**
     * Public constructor
     * 
     * @param string $key Flickr API key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Returns sizes array for photo identified by Flickr photo id
     * 
     * @param int $photoId
     * @return array Array of photo size information 
     */
    public function getSizes($photoId)
    {
        if (apc_fetch($photoId) === false) {
            $options = array(
                'method' => 'flickr.photos.getSizes',
                'api_key' => $this->key,
                'photo_id' => $photoId,
                'format' => 'json',
                'nojsoncallback' => 1
            );

            $url = 'http://api.flickr.com/services/rest/?' . http_build_query($options);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            
            $sizes = json_decode($result, true);
            apc_store($photoId, $sizes);
        } else {
            $sizes = apc_fetch($photoId);
        }

        return $sizes;
    }

}

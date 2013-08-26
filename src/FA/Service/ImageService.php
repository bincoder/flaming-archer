<?php

/**
 * Flaming Archer
 *
 * @link      http://github.com/jeremykendall/flaming-archer for the canonical source repository
 * @copyright Copyright (c) 2012 Jeremy Kendall (http://about.me/jeremykendall)
 * @license   http://github.com/jeremykendall/flaming-archer/blob/master/LICENSE MIT License
 */

namespace FA\Service;

use FA\Dao\ImageDao;
use FA\Service\FlickrInterface;

/**
 * Image service abstracts application requests for image data
 */
class ImageService
{
    /**
     * Image Dao
     *
     * @var ImageDao
     */
    private $dao;

    /**
     * Instance of object honoring the FlickrInterface
     *
     * @var FlickrInterface
     */
    private $flickr;

    /**
     * Public constructor
     *
     * @param ImageDao        $dao
     * @param FlickrInterface $flickr
     */
    public function __construct(ImageDao $dao, FlickrInterface $flickr)
    {
        $this->dao = $dao;
        $this->flickr = $flickr;
    }

    /**
     * Find image by day
     *
     * @param  int   $day Project day (1-366)
     * @return array Image data
     */
    public function find($day)
    {
        $image = $this->dao->find($day);

        if (!$image) {
            return null;
        }

        $sizes = $this->flickr->getSizes($image['photo_id']);

        return array_merge($image, $sizes);
    }

    /**
     * Find all images
     *
     * @return array All images
     */
    public function findAll()
    {
        $images = $this->dao->findAll();
        $result = array();

        foreach ($images as $image) {
            $result[] = array_merge($image, $this->flickr->getSizes($image['photo_id']));
        }

        return $result;
    }

    /**
     * Save new image
     *
     * @param  array $data Array containing 'day' and 'photo_id' keys
     * @return bool  True on success, false on failure
     */
    public function save(array $data)
    {
        return $this->dao->save($data);
    }

    /**
     * Delete image
     *
     * @param  int  $day Project day (1-366)
     * @return bool True on success, false on failure
     */
    public function delete($day)
    {
        return $this->dao->delete($day);
    }
}

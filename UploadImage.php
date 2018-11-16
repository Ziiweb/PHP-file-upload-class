<?php

namespace Ziiweb;

require_once 'Upload.php';

use Ziiweb\Upload;


class UploadImage extends Upload {

    /**
     * Extension associated to the mime type
     *
     * @var string
     */
    protected $extension;

    /**
     * Max. image width (pixels)
     *
     * @var int
     */
    protected $max_width;

    /**
     * Max. image height (pixels)
     *
     * @var int
     */
    protected $max_height;

    /**
     * Return upload object
     *
     * $destination		= 'path/to/your/file/destination/folder';
     *
     * @param string $destination
     * @param string $root
     * @return Upload
     */
    public static function factory($destination, $root = false) {
        return parent::factory($destination, $root);
    }

    /**
     * Set unique filename
     *
     * @return string
     */
    protected function create_new_filename() {
        $filename = sha1(mt_rand(1, 9999) . $this->destination . uniqid()) . time();

        //get the extension
        if ($this->get_file_mime() == 'image/jpeg') {
            $this->extension = '.jpg';
        } else {
            $this->extension = '.png';
        }

        $this->set_filename($filename . $this->extension);
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function get_filename() {
        return $this->filename;
    }

    /**
     * Image width validation callback
     *
     * @param object $object
     */
    protected function check_image_width($object) {
        if (!empty($object->max_width)) {

            $image_width = getimagesize($object->file['tmp_name'])[0];

            if ($image_width > $object->max_width) {
                $object->set_error('Image is too wide (max: 1000 pixels)');
            }
        }
    }

    /**
     * Set max. width of the image (pixels)
     *
     * @param int $width
     */
    public function set_max_image_width($width) {
        $this->max_width = $width;

        //if max image width is set -> set callback
        $this->callbacks[] = 'check_image_width';
    }

    /**
     * Image height validation callback
     *
     * @param object $object
     */
    protected function check_image_height($object) {
        if (!empty($object->max_height)) {

            $image_height = getimagesize($object->file['tmp_name'])[1];
            
            if ($image_height > $object->max_height) {
                $object->set_error('Image is too high (max: 1000 pixels)');
            }
        }
    }

    /**
     * Set max. height of the image (pixels)
     *
     * @param int $height
     */
    public function set_max_image_height($height) {
        $this->max_height = $height;

        //if max image height is set -> set callback
        $this->callbacks[] = 'check_image_height';
    }

}

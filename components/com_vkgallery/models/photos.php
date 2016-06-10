<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

require_once JPATH_COMPONENT . '/helpers/vkapi.class.php';
/**
 * This models supports retrieving a list of tags.
 *
 * @since  3.1
 */
class VkgalleryModelPhotos extends JModelList
{

    protected $_vkApiModel;
    protected $_vkApiAppId;
    protected $_vkApiSecret;
    protected $_vkApiGId;
    protected $photos;
    
    public function __construct($config = array())
    {
        $this->_vkApiModel = new vkApi();
        parent::__construct($config);
    }
    
    protected function populateState($ordering = null, $direction = null)
    {
            $app = JFactory::getApplication();

            $params = $app->getParams();
            $this->setState('params', $params);
    }

    public function getPhotos($groupId, $albumId)
    {
        if ($this->_vkApiModel) {
            
            $vk_photos = $this->_vkApiModel->api('photos.get', array('gid' => $groupId, 'album_id' => $albumId));
            if ($vk_photos) {
                $this->photos = $vk_photos['response'];
            }
        }
        return $this->photos;
    }
}

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
class VkgalleryModelAlbums extends JModelList
{

    protected $_vkApiModel;
    protected $_vkApiAppId;
    protected $_vkApiSecret;
    protected $_vkApiGId;
    protected $albums;
    
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

    public function getAlbums($groupId)
    {
        if ($this->_vkApiModel) {
            
            $vk_albums = $this->_vkApiModel->api('photos.getAlbums', array('gid' => $groupId, 'count' => 10));
            if ($vk_albums) {
                $this->albums = $vk_albums['response'];
            }
            foreach ($this->albums as $id => $album) {
                $thumb = $this->_vkApiModel->api('photos.getById', array(
                    'photos' => $album['owner_id'] . '_' . $album['thumb_id'],
                ));
                $this->albums[$id]['thumb'] = isset($thumb['response'][0]) ? $thumb['response'][0] : null ;
            }
        }
        return $this->albums;
    }
}

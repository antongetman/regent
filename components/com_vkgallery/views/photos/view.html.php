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

/**
 * HTML View class for the Tags component
 *
 * @since  3.1
 */
class VkgalleryViewPhotos extends JViewLegacy {

    protected $photos;
    protected $state;
    protected $params;

    public function display($tpl = null) {
        $app = JFactory::getApplication();
        $JInput = JFactory::getApplication()->input;
        $albumId = $JInput->get('aid');
        $groupId = $JInput->get('gid');
        
        $params = $app->getParams();
//        echo"<pre>";
//        print_r($JInput->get('aid'));
//        echo"</pre>";
        // Get some data from the models
        $state = $this->get('State');
        $items = $this->get('Items');
        $item = $this->get('Item');
        $pagination = $this->get('Pagination');
        
        $model = $this->getModel();
        $this->photos = $model->getPhotos($groupId, $albumId);
        $this->params = $state->get('params');
        $active = $app->getMenu()->getActive();
        $temp = clone $this->params;

        // Check to see which parameters should take priority
        if ($active) {
            $currentLink = $active->link;

            // If the current view is the active item and the tags view, then the menu item params take priority
            if (strpos($currentLink, 'view=albums')) {
                $this->params = $active->params;
                $this->params->merge($temp);

                // Load layout from active query (in case it is an alternative menu item)
                if (isset($active->query['layout'])) {
                    $this->setLayout($active->query['layout']);
                }
            } else {
                // Current view is not a single tag, so the tag params take priority here
                // Merge the menu item params with the tag params so that the tag params take priority
                $temp->merge($item->params);
                $item->params = $temp;

                // Check for alternative layouts (since we are not in a single-article menu item)
                // Single tag menu item layout takes priority over alt layout for a tag
                if ($layout = $item->params->get('tag_layout')) {
                    $this->setLayout($layout);
                }
            }
        } elseif (!empty($items[0])) {
            // Merge so that tag params take priority
            $temp->merge($items[0]->params);
            $items[0]->params = $temp;

            // Check for alternative layouts (since we are not in a single-tag menu item)
            // Single-tag menu item layout takes priority over alt layout for a tag
            if ($layout = $items[0]->params->get('albums_layout')) {
                $this->setLayout($layout);
            }
        }

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     *
     * @return void
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();

        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_TAGS_DEFAULT_PAGE_TITLE'));
        }

        if ($menu && ($menu->query['option'] != 'com_tags')) {
            $this->params->set('page_subheading', $menu->title);
        }

        // Set metadata for all tags menu item
        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }

        // If this is not a single tag menu item, set the page title to the tag titles
        $title = '';

        if (!empty($this->item)) {
            foreach ($this->item as $i => $itemElement) {
                if ($itemElement->title) {
                    if ($i != 0) {
                        $title .= ', ';
                    }

                    $title .= $itemElement->title;
                }
            }

            if (empty($title)) {
                $title = $app->get('sitename');
            } elseif ($app->get('sitename_pagetitles', 0) == 1) {
                $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
            } elseif ($app->get('sitename_pagetitles', 0) == 2) {
                $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
            }

            $this->document->setTitle($title);

            foreach ($this->item as $itemElement) {
                if ($itemElement->metadesc) {
                    $this->document->setDescription($this->item->metadesc);
                } elseif ($this->params->get('menu-meta_description')) {
                    $this->document->setDescription($this->params->get('menu-meta_description'));
                }

                if ($itemElement->metakey) {
                    $this->document->setMetadata('keywords', $this->tag->metakey);
                } elseif ($this->params->get('menu-meta_keywords')) {
                    $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
                }

                if ($this->params->get('robots')) {
                    $this->document->setMetadata('robots', $this->params->get('robots'));
                }

                if ($app->get('MetaAuthor') == '1') {
                    $this->document->setMetaData('author', $itemElement->created_user_id);
                }

                $mdata = $this->item->metadata->toArray();

                foreach ($mdata as $k => $v) {
                    if ($v) {
                        $this->document->setMetadata($k, $v);
                    }
                }
            }
        }

        // Add alternative feed link
        if ($this->params->get('show_feed_link', 1) == 1) {
            $link = '&format=feed&limitstart=';
            $attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
            $this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
            $attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
            $this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
        }
    }

}

<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');


//
//$document = JFactory::getDocument();
//$url = JUri::base() . 'templates/custom/js/sample.js';
//
//$document->addStyleSheet($url);
//$document->addScript($url);
JHtml::_('jquery.framework');
JHtml::script('media/com_vkgallery/js/jquery.blueimp-gallery.min.js');
JHtml::script('media/com_vkgallery/js/bootstrap-image-gallery.js');
JHtml::script('media/com_vkgallery/js/gallery.js');
JHtml::stylesheet('com_vkgallery/bootstrap-image-gallery.css', array(), true);
JHtml::stylesheet('com_vkgallery/blueimp-gallery.min.css', array(), true);
JHtml::stylesheet('com_vkgallery/demo.css', array(), true);
?>
<div>
<?php
//echo "<pre>";
//print_r($this->albums);
//echo "</pre>";
?>
    <?php echo __FILE__; ?>
</div>

<div id="links">
    <?php
    $ci = 0;//column increment
    foreach ($this->photos as $photo) {
        if( $ci%4 == 0 ) {
    ?>
    <div class="items-row cols-4 row-fluid">
    <?php
        }
    ?>
        <div class="item span3 center album">
        <a href="<?php echo $photo['src_xbig'];?>" title="<?php echo $photo['text'];?>" data-gallery="">
                <img src="<?php echo $photo['src'];?>">
        </a>
        </div>
    <?php
        $ci++;
        if( $ci%4 == 0 && $ci !=1 ) {
        ?>    
    </div>
        <?php
        }
    }
    ?>
</div>

    <div id="blueimp-gallery" class="blueimp-gallery">
        <!-- The container for the modal slides -->
        <div class="slides">
            
        </div>
        <!-- Controls for the borderless lightbox -->
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
        <!-- The modal dialog, which will be used to wrap the lightbox content -->
    </div>
<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('jquery.framework');
JHtml::script('media/com_vkgallery/js/jquery.blueimp-gallery.min.js');
JHtml::script('media/com_vkgallery/js/bootstrap-image-gallery.js');
JHtml::script('media/com_vkgallery/js/demo.js');
JHtml::stylesheet('com_vkgallery/bootstrap-image-gallery.css', array(), true);
JHtml::stylesheet('com_vkgallery/blueimp-gallery.min.css', array(), true);
JHtml::stylesheet('com_vkgallery/demo.css', array(), true);
?>
<div class="albums">
    <?php
    $ci = 0;//column increment
    foreach ($this->albums as $album) {
        if( $ci%3 == 0 ) {
    ?>
        <div class="items-row cols-3 row-fluid">
    <?php
        }
    ?>
        
            <div class="item span4 center album">
                <a href="<?php echo JURI::base().'index.php?option=com_vkgallery&view=photos&gid='.$this->groupId.'&aid='.$album['aid']; ?>">
                    <?php if ($album['thumb']['src']) { ?>
                    <img class="img-polaroid" src="<?php echo $album['thumb']['src_big'];?>" alt="<?php echo $album['title'];?>">
                    <?php }?>
                        <span class=""><?php echo $album['title'];?></span>
                        <small class=""><?php echo $album['description'];?></small>
                </a>
            </div>
            <?php
            $ci++;
            if( $ci%3 == 0 && $ci !=1 ) {
            ?>    
            </div>
            <?php
            }
        }
        ?>

</div>

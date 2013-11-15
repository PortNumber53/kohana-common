<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 7/7/13
 * Time: 2:36 AM
 * Something meaningful about this file
 *
 */

?><ul class="breadcrumb">
        <?php
        if (!empty($breadcrumbs))
        {
                $counter = 0;
                foreach ($breadcrumbs as $label=>$url)
                {
                        $counter++;
                        ?><li><a href="<?php echo $url; ?>"><?php echo $label; ?></a><?php
                        if ($counter < count($breadcrumbs))
                        {
                                ?>
                                <span class="divider">/</span>
                        <?php }
                        ?></li><?php
                }
        }
        ?>
</ul>

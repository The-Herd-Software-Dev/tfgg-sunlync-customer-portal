<?php

function tfgg_scp_cart_description(){
    echo '<p>Cart Settings</p>';
}

function display_tfgg_cart_slug(){
    ?>
    <input type="text" name="tfgg_scp_cart_slug" value="<?php echo get_option('tfgg_scp_cart_slug'); ?>" style="width: 60%" />
    <?php
}

?>
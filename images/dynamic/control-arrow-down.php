<?php
header("Content-Type: image/svg+xml");

define('WP_USE_THEMES', false); 
require(dirname(__DIR__, 5) . '/wp-load.php');

$color_list = kf_color_id_list();
$color_id = array_key_exists($_GET['color'], $color_list) ? $_GET['color'] : 'a5';
?>
<svg xmlns="http://www.w3.org/2000/svg" width="26" height="17" viewBox="0 0 26.34 17">
    <polygon style="fill: <?php echo $color_list[$color_id]; ?>;" points="26.34 3.84 22.5 0 13.17 9.33 3.84 0 0 3.84 13.17 17 26.34 3.84"/>
</svg>

<?php
header("Content-Type: image/svg+xml");

define('WP_USE_THEMES', false); 
require(dirname(__DIR__, 5) . '/wp-load.php');

$color_list = kf_color_id_list();
$color_id = array_key_exists($_GET['color'], $color_list) ? $_GET['color'] : 'a5';
?>
<svg xmlns="http://www.w3.org/2000/svg" width="17" height="27" viewBox="0 0 17.45 27.04">
    <polygon style="fill: <?php echo $color_list[$color_id]; ?>;" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/>
</svg>

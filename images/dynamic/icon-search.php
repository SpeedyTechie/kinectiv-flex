<?php
header("Content-Type: image/svg+xml");

define('WP_USE_THEMES', false); 
require(dirname(__DIR__, 5) . '/wp-load.php');

$color_list = kf_color_id_list();
$color_id = array_key_exists($_GET['color'], $color_list) ? $_GET['color'] : 'a5';
?>
<svg xmlns="http://www.w3.org/2000/svg" width="19.27" height="19.28" viewBox="0 0 19.27 19.28">
    <path style="fill: <?php echo $color_list[$color_id]; ?>;" d="M.53,18.75a1.8,1.8,0,0,0,2.54,0h0L7.32,14.5A7.83,7.83,0,1,0,4.77,12L.53,16.2a1.79,1.79,0,0,0,0,2.54ZM6.61,7.85a4.81,4.81,0,1,1,4.81,4.81h0A4.82,4.82,0,0,1,6.61,7.85Z"/>
</svg>
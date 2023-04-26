<?php
/*
Plugin Name: folder tree
Plugin URI: https://github.com/tguruslan/docprew-wp
Description: для додавання використайте: [docPrew src=""]
Author: Tgu
Version: 1.0.0
Author URI: https://github.com/tguruslan
*/

add_shortcode( 'docPrew', function( $atts, $content="" ){
    require_once ABSPATH . 'wp-admin/includes/file.php';
    extract(shortcode_atts(array("src" => '',), $atts));
    $src = ltrim(rtrim($src, "/"), "/");
    $folder = wp_upload_dir()['basedir'].'/'.$src.'/';
    wp_enqueue_style( 'font-awesome-4.7.0', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css' );
    wp_enqueue_style('docPrew', plugins_url('style.css',__FILE__ ));

    function list_folder_econom($dir=''){
    $scandir = scandir($dir);
    $addr = '/wp-content/uploads/'.ltrim(rtrim(str_replace(wp_upload_dir()['basedir'],'',$dir), "/"), "/").'/';
    $html = '<ul class="rs_list">';
        foreach ($scandir as $d){
        if ($d == '.' || $d == '..') continue;
        if (is_file($dir.$d)){
            $html .= '<li class="rs_file"><a target="blank" href="'.$addr.$d.'">'.$d.'</a></li>';
        }else{
            $html .= '<li class="rs_dir">'.$d.list_folder_econom($dir.$d.'/').'</li>';
        }
        }
        $html .= '</ul>';
        return $html;
    }
    add_action('wp_footer', function(){ ?>
        <script>
        (function($){
        $(document).ready(function(){
            $('.rs_dir').click(function(e){
            if(e.target != this) return;
            $(this).toggleClass('open');
            $(this).find('>.rs_list').toggle('slow');
            });
        });
        })(jQuery);
    </script>
        <?php });

    return list_folder_econom($folder);
});

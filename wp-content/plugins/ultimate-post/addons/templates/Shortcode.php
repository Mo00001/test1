<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Shortcode {
    public function __construct() {
        add_shortcode('gutenberg_post_blocks', array($this, 'shortcode_callback'));
        add_shortcode('postx_template', array($this, 'shortcode_callback'));
    }

    // Shortcode Callback
    function shortcode_callback( $atts = array(), $content = null ) {
        extract(shortcode_atts(array(
         'id' => ''
        ), $atts));

        $id = is_numeric( $id ) ? (float) $id : false;

        if ($id) {
            $content = '';
            if (!isset($GLOBALS['wp_scripts']->registered['ultp-script'])) {
                ultimate_post()->register_scripts_common();
            }
            $css = ultimate_post()->set_css_style($id, true);
            $content_post = get_post($id);
            if ($content_post) {
                if ($content_post->post_status == 'publish' && $content_post->post_password == '') {
                    $content = $content_post->post_content;
                    $content = apply_filters('the_content', $content);
                    $content = str_replace(']]>', ']]&gt;', $content);
                    return $css.'<div class="ultp-shortcode" data-postid="'.$id.'">' . $content . '</div>';
                }
            }
        }
        return '';
    }
    
}
<?php
/*
Plugin Name: BannerFlow Ad Shortcode
Description: Adds a shortcode to Wordpress to show BannerFlow ad (including responsive, preload and adblocking redirecting).
Version: 1.1.1
Author: A.F.Schuurman
Author URI: https://github.com/drsdre/wp_bannerflow_ad_shortcode
Text Domain: bf_ad_sc
Domain Path: /languages
*/

namespace drsdre\shortcodes;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function bf_ad_add_buttons( $plugin_array ) {
	$plugin_array['bf_ad_sc'] = plugins_url( '/js/mce-button.js', __FILE__ );

	return $plugin_array;
}

function bf_ad_register_buttons( $buttons ) {
	array_push( $buttons, 'bf_ad_sc' );

	return $buttons;
}

function bf_ad_init() {
	load_plugin_textdomain('bf_ad_sc', false, basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	if ( get_user_option( 'rich_editing' ) !== 'true' ) {
		return;
	}

	add_filter( 'mce_external_plugins', 'drsdre\shortcodes\bf_ad_add_buttons' );
	add_filter( 'mce_buttons', 'drsdre\shortcodes\bf_ad_register_buttons' );
}

add_action( 'init', 'drsdre\shortcodes\bf_ad_init' );

function bf_ad_tinymce_extra_vars() { ?>
    <script type="text/javascript">
        var bf_tinyMCE_object = <?php echo json_encode(
				[
					'button_name'       => esc_html__( 'BannerFlow Ad', 'bf_ad_sc' ),
					'window_title'      => esc_html__( 'BannerFlow Ad Shortcode', 'bf_ad_sc' ),
					'bfid_landscape'    => esc_html__( 'Banner ID Landscape', 'bf_ad_sc' ),
					'bfid_portrait'     => esc_html__( 'Banner ID Portrait', 'bf_ad_sc' ),
					'responsive'        => esc_html__( 'Responsive (fill screen)', 'bf_ad_sc' ),
					'politeloading'     => esc_html__( 'Polite loading (show GIF before loading)', 'bf_ad_sc' ),
					'target_url'        => esc_html__( 'Target URL', 'bf_ad_sc' ),
					'targetwindow'      => esc_html__( 'Target option', 'bf_ad_sc' ),
					'adblock_detection' => esc_html__( 'Ad block detection and auto redirect', 'bf_ad_sc' ),
				]
			);
			?>;
    </script><?php
}

add_action( 'after_wp_tiny_mce', 'drsdre\shortcodes\bf_ad_tinymce_extra_vars' );

function bf_ad_scripts() {
	wp_register_script( 'block_detector', plugins_url( '/js/detector.js', __FILE__ ), [], '1.0.0', 'all' );
}

add_action( 'wp_enqueue_scripts', 'drsdre\shortcodes\bf_ad_scripts' );

function bf_ad_shortcode( $atts ) {
	static $bf_ad_sc_id = 1;

	$atts = shortcode_atts( [
		'bfid_landscape'    => '',
		'bfid_portrait'     => '',
		'responsive'        => 'on',
		'politeloading'     => 'off',
		'target_url'        => '',
		'targetwindow'      => '_top',
		'adblock_detection' => 'false',
	], $atts, 'bannerflow_landingpage' );

	if ( $atts['adblock_detection'] == 'true' ) {
		wp_enqueue_script( 'block_detector' );
	}

	// For Mobile Casino theme
	if ( function_exists( 'add_trackers_to_url' ) ) {
		$atts['target_url'] = add_trackers_to_url( $atts['target_url'] );
	}

	ob_start();
	?>
    <span id="bannerflow_sc_<?php echo $bf_ad_sc_id ?>_ad"></span>
    <script>
        var bf_ad_sc<?php echo $bf_ad_sc_id ?>_current_bf_id;

        if (typeof loadjscssfile === 'undefined') {
            function loadjscssfile(filename, filetype, insertElement) {
                if (filetype == "js") { //if filename is a external JavaScript file
                    var fileref = document.createElement('script');
                    fileref.setAttribute("type", "text/javascript");
                    fileref.setAttribute("src", filename);
                }
                else if (filetype == "css") { //if filename is an external CSS file
                    var fileref = document.createElement("link");
                    fileref.setAttribute("rel", "stylesheet");
                    fileref.setAttribute("type", "text/css");
                    fileref.setAttribute("href", filename);
                }
                if (typeof fileref != "undefined") {
                    insertElement.appendChild(fileref);
                }
            }
        }

        function show_bf<?php echo $bf_ad_sc_id ?>() {
            var bf_id;
            var bfid_landscape = '<?php echo $atts['bfid_landscape'] ?>';
            var bfid_portrait = '<?php echo $atts['bfid_portrait'] ?>';
            var responsive = '<?php echo $atts['responsive'] ?>';
            var politeloading = '<?php echo $atts['politeloading'] ?>';
            var target_url = '<?php echo $atts['target_url'] ?>';
            var targetwindow = '<?php echo $atts['targetwindow'] ?>';

            if (window.matchMedia("(orientation: portrait)").matches) {
                bf_id = bfid_portrait ? bfid_portrait : bfid_landscape;
            } else {
                bf_id = bfid_landscape ? bfid_landscape : bfid_portrait;
            }

            if (bf_ad_sc<?php echo $bf_ad_sc_id ?>_current_bf_id !== bf_id) {
                bf_ad_sc<?php echo $bf_ad_sc_id ?>_current_bf_id = bf_id;

                var element = document.querySelectorAll('[id^=bf]');
                Array.prototype.forEach.call(element, function (node) {
                    node.parentNode.removeChild(node);
                });
                loadjscssfile('https://embed.bannerflow.com/' + bf_id +
                    '?targeturl=' + target_url +
                    '&targetwindow=' + targetwindow +
                    '&responsive=' + responsive +
                    '&politeloading=' + politeloading,
                    'js',
                    document.getElementById('bannerflow_sc_<?php echo $bf_ad_sc_id ?>_ad')
                );
            }
        }

        window.addEventListener('orientationchange', show_bf<?php echo $bf_ad_sc_id ?>);
        window.addEventListener('resize', show_bf<?php echo $bf_ad_sc_id ?>);

		<?php if ( $atts['adblock_detection'] == 'true' ): ?>
        window.onload = function () {
            if (typeof blockAdBlock !== 'undefined') {
                blockAdBlock.onDetected(function() {
                    var query_string = location.search.substring(1);
                    var target_url = '<?php echo $atts['target_url'] ?>';
                    window.location.href = target_url + (target_url.includes('?') ? '&' : '?') + query_string;
                });
                blockAdBlock.onNotDetected(show_bf<?php echo $bf_ad_sc_id ?>);
            }

        }
		<?php else: ?>
        show_bf<?php echo $bf_ad_sc_id ?>();
		<?php endif; ?>
    </script>
	<?php

	// Get the buffered content into a var
	$sc = ob_get_contents();

	// Clean buffer
	ob_end_clean();

	$bf_ad_sc_id++;

	// Return the content as usual
	return $sc;
}

add_shortcode( 'bannerflow_ad', 'drsdre\shortcodes\bf_ad_shortcode' );
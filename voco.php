<?php
/*
* Plugin Name: VOCO Chat
* Version: 1.1
* Description: VOCO Chat plugin to interact with your customers.
* Author: Cell Buddy
* Author URI: http://voconet.io/
* Requires at least: 4.0
* Tested up to: 4.0
*
* Text Domain: voco
* Domain Path: /lang/
*
* @package WordPress
* @author Cell Buddy
* @since 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-voco.php' );
require_once( 'includes/class-voco-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-voco-admin-api.php' );
require_once( 'includes/lib/class-voco-post-type.php' );
require_once( 'includes/lib/class-voco-taxonomy.php' );

/**
* Returns the main instance of Voco to prevent the need to use globals.
*
* @since  1.0.0
* @return object Voco
*/
function Voco () {
	$instance = Voco::instance( __FILE__, '1.0.0' );
	
	if ( is_null( $instance->settings ) ) {
		$instance->settings = Voco_Settings::instance( $instance );
	}

	return $instance;
}

function addVocoChat() {
	?>
	<script type="text/javascript">

	var excludedPosts = '<?php echo get_option('wpt_Post_Page_Id') ?>'.split(',');
	var postId = '<?php echo get_the_ID() ?>';

	if ( undefined !== window.jQuery && excludedPosts.indexOf(postId) === -1) {
		
		var openedChatButtonSrc = '<?php echo plugin_dir_url( __FILE__ ) . 'images/VOCO_Chat_wordpress_plugin_off.png'; ?>';
		var closedChatButtonSrc = '<?php echo plugin_dir_url(  __FILE__  ) . 'images/VOCO_Chat_wordpress_plugin_on.png'; ?>';
	
	    var imgurltext = '<?php echo wp_get_attachment_url (get_option('wpt_an_image'))?>';
		var introImg = '<?php echo wp_get_attachment_url (get_option('wpt_an_image'))?>';
		if(introImg === '')
			introImg = '<?php echo plugin_dir_url(  __FILE__  ) . 'images/VOCO_Web_Chat_Avatar.png'; ?>'
		var settingIntro = '<?php echo get_option('wpt_intro') ?>';
		var chatUrl = 'https://vococ.net/?c_m_u<?php echo get_option('wpt_voco_credentials') ?>';
		
		var intro = '';
		if (settingIntro !== '')
			intro = "<div id='vocoChatIntro'><img src='"+introImg+"' height='50' width='50'>"+
			"<p id='vocoChatIntroText'>"+settingIntro+"</p></div>";
		
		var button = "<div id='vocoChatButton'><img src='"+closedChatButtonSrc+"' height='70' width='70'></div>";
		var frame =  "<div id='vocoChatFrame'\>" + 
		"<iframe src='"+chatUrl+"' width='346' height='100%'\>" +
			"<p>Your browser does not support iframes.</p\>" +
			"</iframe\>" +
			"</div>";
		var chat = "<div id='vocoChat'>"+intro + frame+"</div>";
		document.body.innerHTML += chat + button;
			
			jQuery("#vocoChat").hide();
			jQuery("#vocoChatButton").css({
				'background': '<?php echo get_option('wpt_button_background') ?>',
    			'border-radius': '100px',
				'border': '1px solid <?php echo get_option('wpt_button_border') ?>',
			}); 
			
			jQuery("#vocoChatIntro").css({
				'background': '<?php echo get_option('wpt_intro_background') ?>',
    		});
    		jQuery("#vocoChatFrame").css({
				'background-color': '<?php echo get_option('wpt_intro_background') ?>',
    		});
			
			//jQuery("#vocoChatButton").css({'margin-top':'510px'}); 
			jQuery("#vocoChatButton").toggle(function () {
					if(window.innerWidth <= 800)
						(window.open(chatUrl, '_blank')).focus();
					else{
					jQuery("#vocoChat").css({'z-index':'999'}); 
					jQuery("#vocoChatButton img").attr("src",openedChatButtonSrc);
					jQuery("#vocoChat").show();
				}
				}, function () {
					if(window.innerWidth <= 800 )
						(window.open(chatUrl, '_blank')).focus();
					else{
					jQuery("#vocoChatButton img").attr("src",closedChatButtonSrc);
					jQuery("#vocoChat").css({'z-index':'-1'}); 
					jQuery("#vocoChat").hide();
					}
				});
		}
		</script>
		<?php

	}
	add_action( 'wp_footer', 'addVocoChat' );
	
	function myscript_jquery() {
		wp_enqueue_script( 'jquery' );
	}
	add_action( 'wp_head' , 'myscript_jquery' );
	
	Voco();


	
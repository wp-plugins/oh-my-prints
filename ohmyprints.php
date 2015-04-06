<?php
/**
 * @package oh-my-prints
 * @version 1.2
 */
/*
Plugin Name: Oh my prints
Plugin URI: http://www.funsite.eu/plugins/oh-my-prints
Description: Link to a page on the "Oh my prints" site for selling the photo or painting on canvas..
Author: Gerhard Hoogterp
Version: 1.2
Author URI: http://www.funsite.eu/
Text Domain: ohmyprints
Domain Path: /languages
*/

if (!class_exists('basic_plugin_class')) {
	require(plugin_dir_path(__FILE__).'basics/basic_plugin.class');
}

class ohmyprints_widget extends WP_Widget {

	const FS_TEXTDOMAIN = ohmyprints_class::FS_TEXTDOMAIN; 

	// constructor
	function ohmyprints_widget() {
		parent::WP_Widget(false, 
							$name = __('Oh my prints', self::FS_TEXTDOMAIN),
							array('description' => __('Link to an page on "Oh my prints".',self::FS_TEXTDOMAIN))
						);
	}

	// widget form creation
	function form($instance) {
	    // Check values
	    if( $instance) {
			$title 			= esc_attr($instance['title']);
			$description	= esc_textarea($instance['description']);
			$selltext		= esc_textarea($instance['selltext']);
			$defaulturl		= $instance['defaulturl'];
			$language		= $instance['language'];
	    } else {
			$title 			= __('Prints for sale',self::FS_TEXTDOMAIN);
			$description	= __('This photo is not for sale. Please contact me if you are interested or <a %s>click here</a> to go to the shop.',self::FS_TEXTDOMAIN);
			$selltext		= __('Buy this photo, <a %s>click here</a>.',self::FS_TEXTDOMAIN);
			$defaulturl		= __('http://yoursitename.ohmyprints.com',self::FS_TEXTDOMAIN);
			$language		= '';
	    }

	    $languages  = array(
						''		=> 'site language',
						'en'	=> 'English',
						'nl'	=> 'Nederlands',
						'de'	=> 'Deutsch',
						'fr'	=> 'Français'
						);	
	    ?>

	    <p>
	    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', self::FS_TEXTDOMAIN); ?></label>
	    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
    
	    <p>
	    <label for="<?php echo $this->get_field_id('defaulturl'); ?>"><?php _e('Your site', self::FS_TEXTDOMAIN); ?></label>
	    <input class="widefat" id="<?php echo $this->get_field_id('defaulturl'); ?>" name="<?php echo $this->get_field_name('defaulturl'); ?>" type="text" value="<?php echo $defaulturl; ?>" />
	    </p>
	    
   	    <p>
	    <label for="<?php echo $this->get_field_id('language'); ?>"><?php _e('Force language', self::FS_TEXTDOMAIN); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('language'); ?>" name="<?php echo $this->get_field_name('language'); ?>">
		<?php 
		foreach($languages as $tag=>$name):
			print '<option value="'.$tag.'"';
			if ($tag==$language) {
				print " selected";
			}
			print '>'.$name."</option>\n";
			endforeach;
		?>
		</select><br>
	    </p>

    
		<p>
		<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', self::FS_TEXTDOMAIN); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo $description; ?></textarea>
	    </p>
    
   		<p>
		<label for="<?php echo $this->get_field_id('selltext'); ?>"><?php _e('For sale text', self::FS_TEXTDOMAIN); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('selltext'); ?>" name="<?php echo $this->get_field_name('selltext'); ?>"><?php echo $selltext; ?></textarea>
	    </p>
    
	    <?php
	}

	// widget update
	function update($new_instance, $old_instance) {
	    $instance = $old_instance;
	    // Fields
	    $instance['title'] 			= strip_tags($new_instance['title']);
	    $instance['description']	= $new_instance['description'];
	    $instance['selltext']		= $new_instance['selltext'];
	    $instance['defaulturl'] 	= $new_instance['defaulturl'];
	    $instance['language']		= $new_instance['language'];
	    return $instance;
	}

	
	// widget display
	function widget($args, $instance) {
		extract( $args );
		$wadm_id 		= get_post_meta( $GLOBALS['post']->ID, 'wadm_id', true );
		$title			= apply_filters('widget_title', $instance['title']);
		$description 	= apply_filters('widget_text', $instance['description']);
		$selltext 		= apply_filters('widget_text', $instance['selltext']);
		$defaulturl 	= $instance['defaulturl']; 
		$language		= $instance['language'];
		
		$useLanguage 	= __('en', self::FS_TEXTDOMAIN);
		$useDomain 		= __('www.ohmyprints.com', self::FS_TEXTDOMAIN);

		$useLanguage	= $language?$language:$useLanguage;
		
		echo $before_widget;
	  
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box ohmyprints_hook">';

		// Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		$nonce = wp_create_nonce("forsale_nonce");
		
		$attribs['class']			= 'oh_my_prints_link';
		$attribs['data-nonce']			= $nonce;
		$attribs['data-post_id']		= $GLOBALS['post']->ID;
		$attribs['data-value-wadm']		= $wadm_id;
		$attribs['data-value-lang']		= $useLanguage;
		$attribs['data-value-defUrl']	= $defaulturl;
		$attribs['data-value-domain']	= $useDomain;
		
		foreach($attribs as $tag=>$val):
			$linkAttribs .= $tag.'= "'.$val.'" ';
		endforeach;
		
		echo '<div class="entry">';
			if ($wadm_id) {
				print '<a class="button oh_my_prints_link"'.$linkAttribs.'><img src="http://'.$useDomain.'/apple-touch-icon.png" class="frame" alt=""></a>';
				print sprintf($selltext,$linkAttribs);
			} else {
				if (substr($defaulturl,-1)==='/') { $defaulturl = substr($defaulturl,0,-1); }
				print '<a class="button oh_my_prints_link"'.$linkAttribs.'><img src="http://'.$useDomain.'/apple-touch-icon.png" class="frame" alt=""></a>';
				print sprintf($description,$linkAttribs);
			}		
			echo '</div>';
			
		echo '</div>';
		echo $after_widget;
	}
}

class ohmyprints_class  extends basic_plugin_class {

	function getPluginBaseName() { return plugin_basename(__FILE__); }
	function getChildClassName() { return get_class($this); }

	const FS_TEXTDOMAIN = 'ohmyprints';	
	const FS_PLUGINNAME = 'oh-my-prints';

	function pluginInfoRight($info) {  }
	
    public function __construct() {
		parent::__construct();
		
		add_action('init', array($this,'myTextDomain'));
		add_action('admin_init', array($this,'ohmyprints_admin_headers'),false,false,true);
		
		// admin interface
		add_action( 'admin_menu', array($this,'create_ohmyprints_id_box' ));
		add_action( 'save_post', array($this,'save_ohmyprints_id'), 10, 2 );

		// register widget
		add_action('widgets_init', create_function('', 'return register_widget("ohmyprints_widget");'));
		add_action('wp_head', array($this,'ohmyprints_headercode'),false,false,true);    

		//ajax
		add_action("wp_ajax_clicked_for_sale", array($this,"clicked_for_sale"));
		add_action("wp_ajax_nopriv_clicked_for_sale", array($this,"clicked_for_sale"));
		
		// clicks in the posts/pages pages.
		add_filter('manage_posts_columns', array($this,'ohmyprints_columns'));
		add_filter('manage_pages_columns', array($this,'ohmyprints_columns'));
		add_action('manage_posts_custom_column',  array($this,'ohmyprints_show_columns'));
		add_action('manage_pages_custom_column',  array($this,'ohmyprints_show_columns'));

		add_shortcode( 'ohmyprints', array($this,'custom_oh_my_prints' ));
		
	}

	function myTextDomain() {
		load_plugin_textdomain(
			self::FS_TEXTDOMAIN,
			false,
			dirname(plugin_basename(__FILE__)).'/languages/'
		);
	}

	
	function ohmyprints_headercode () {
		wp_enqueue_style('ohmyprints_handler', plugins_url('/css/ohmyprints.css', __FILE__ ));
		wp_register_script( "ohmyprints_js", WP_PLUGIN_URL.'/oh-my-prints/js/ohmyprints.js', array('jquery') );
		wp_localize_script( 'ohmyprints_js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
		wp_enqueue_script( 'jquery' );	
		wp_enqueue_script( 'ohmyprints_js' );		
	}
	
	function ohmyprints_admin_headers () {
		wp_enqueue_style('ohmyprints_admin_css', plugins_url('/css/ohmyprints.css', __FILE__ ));
	}
	
	
 /*	shortcode  -------------------------------------------------------------------------------------
	*/ 
 
	function custom_oh_my_prints( $atts, $content ) {
		$res = '';
		$lang = substr(get_bloginfo ( 'language' ), 0, 2);
		$lang = (in_array($lang,array('en','nl','de','fr')))?$lang:'en';	
		$useDomain 		= __('www.ohmyprints.com', self::FS_TEXTDOMAIN);		
		
		$wadm_id = get_post_meta( $GLOBALS['post']->ID, 'wadm_id', true );
		if ($wadm_id) {
			$nonce = wp_create_nonce("forsale_nonce");

			$attribs['data-nonce']			= $nonce;
			$attribs['data-post_id']		= $GLOBALS['post']->ID;
			$attribs['data-value-wadm']		= $wadm_id;
			$attribs['data-value-lang']		= $lang;
			$attribs['data-value-domain']	= $useDomain;

			foreach($attribs as $tag=>$val):
				$linkAttribs .= $tag.'= "'.$val.'" ';
			endforeach;
		// Code
			$res .= sprintf($content,'class="oh_my_prints_link" '.$linkAttribs);		
		}
	return $res;
	}
 
 /*	Columns in the posts/pages overview -------------------------------------------------------------------------------------
	*/

	function array_insert_after($key, &$array, $new_key, $new_value) {
		if (array_key_exists($key, $array)) {
			$new = array();
			foreach ($array as $k => $value) {
			$new[$k] = $value;
			if ($k === $key) {
				$new[$new_key] = $new_value;
			}
			}
			return $new;
		}
		return FALSE;
	}
	
	function ohmyprints_columns($columns) {
		$columns = $this->array_insert_after('date',$columns,'forsaleclicks',__("Sale clicks",self::FS_TEXTDOMAIN));
		return $columns;
	}


	function ohmyprints_show_columns($name) {
		global $post;
		switch ($name) {
			case 'forsaleclicks':
					$wadm_id = get_post_meta( $post->ID, 'wadm_id', true );
					$idMarker=!$wadm_id?'<span title="not for sale!">*</span>':'';
					
					$wadm_clicks = get_post_meta($post->ID, 'ohmyprints_clicks',true);
					if (!$wadm_clicks) {
						echo '<div class="clicks"></div>';
					} else {
						echo '<div class="clicks">'.$wadm_clicks.$idMarker.'</div>';
					}
		}
	}
 
    /*	page/post artcode box -------------------------------------------------------------------------------------
	*/
    
	function create_ohmyprints_id_box() {
		add_meta_box( 'ohmyprints-box', __('Artcode',self::FS_TEXTDOMAIN), array($this,'ohmyprints_id_box'), 'page', 'side', 'default' );
		add_meta_box( 'ohmyprints-box', __('Artcode',self::FS_TEXTDOMAIN), array($this,'ohmyprints_id_box'), 'post', 'side', 'default' );
	}

	function ohmyprints_id_box( $object, $box ) { ?>
		<p>
			<label for="ID"><?php _e("The artcode as given on the page",self::FS_TEXTDOMAIN); ?></label><br>
			<input name="wadm_id" id="ID" style="width: 97%;" value="<?php echo wp_specialchars( get_post_meta( $object->ID, 'wadm_id', true ), 1 ); ?>">
			<input type="hidden" name="my_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</p>
	<?php }

	function save_ohmyprints_id( $post_id, $post ) {

		if ( !wp_verify_nonce( $_POST['my_meta_box_nonce'], plugin_basename( __FILE__ ) ) )
			return $post_id;

		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		$meta_value = get_post_meta( $post_id, 'wadm_id', true );
		$new_meta_value = stripslashes( $_POST['wadm_id'] );

		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, 'wadm_id', $new_meta_value, true );

		elseif ( $new_meta_value != $meta_value )
			update_post_meta( $post_id, 'wadm_id', $new_meta_value );

		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, 'wadm_id', $meta_value );
	}

	/*	Ajax handler function  -------------------------------------------------------------------------------------
	*/
	
	function clicked_for_sale() {
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "forsale_nonce")) {
			exit("No naughty business please");
		}
		$clicks = get_post_meta($_REQUEST["post_id"], 'ohmyprints_clicks',true);
		$updated = update_post_meta($_REQUEST["post_id"], 'ohmyprints_clicks', $clicks+1);

		$wadm_id		= $_REQUEST['wadm'];
		$useLanguage	= $_REQUEST['lang'];
		$defaultUrl		= $_REQUEST['defUrl'];
		$useDomain		= $_REQUEST['domain'];

		if ($wadm_id) {
				$url = sprintf("http://%s/art/%s/%s/",$useDomain,$useLanguage,$wadm_id);
			} else {
				if (substr($defaulturl,-1)==='/') { $defaulturl = substr($defaulturl,0,-1); }
				$url = $defaultUrl.'/'.$useLanguage.'/';
		}

		
		$result['url'] =  $url;
		$result['type'] = "success";		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$result = json_encode($result);
			echo $result;
		} else {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	die();
	}

	
	
}
 
$ohmyprints = new ohmyprints_class();
?>
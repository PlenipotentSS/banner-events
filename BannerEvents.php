<?php
/*
Plugin Name: Banner Events
Description: Listing events represented by banner image for mobile and event display. 
Author: Steven Stevenson 
Author URI: http://stevenandleah.com/
*/
/* Add your functions below this line */
add_action( 'init', 'ss_register_banner_events' );
add_action('admin_menu','ss_banner_events_settings');
add_action('add_meta_boxes', 'ss_be_mbe_create' );


function ss_banner_events_settings() {
	add_options_page('Banner Events Settings', 'Banner Events Settings', 'manage_options', 'banner_events', 'ss_banner_events_options_page');
	include('settings.php');
	
}

function ss_register_banner_events() {

/* Labels for the product post type. */
	$banner_events_labels = array(
		'name' => __( 'Banner Events'),
		'singular_name' => __( 'Event'),
		'add_new' => __( 'Add New'),
		'add_new_item' => __( 'Add New Event'),
		'edit' => __( 'Edit'),
		'edit_item' => __( 'Edit Event'),
		'new_item' => __( 'New Event'),
		'view' => __( 'View Event'),
		'view_item' => __( 'View Event'),
		'search_items' => __( 'Search Events'),
		'not_found' => __( 'No events found'),
		'not_found_in_trash' => __( 'No events found in Trash'),
	);

   
	/* Arguments for the product post type. */
	$banner_events_args = array(
		'labels' => $banner_events_labels,
		'capability_type' => 'post',
		'public' => true,
		'can_export' => true,
		'query_var' => true,
		'menu_icon' => plugins_url('banner_events/images/menu-image.png'),  // Icon Path
		'rewrite' => array( 'slug' => 'banner_events', 'with_front' => false ),
		'supports' => array( 'title', 'editor')
	);

	/* Register the product post type. */
	register_post_type( 'banner_events', $banner_events_args );
}

// Styling for the custom post type icon
function ls_homepage_sliders_icons() {
    ?>
    <style type="text/css" media="screen">
        #menu-posts-homepage_sliders.wp-menu-image {
            background: url('<?php echo plugins_url('banner_events/images/menu-image.png'); ?>') no-repeat 6px -16px !important;
        }
		#icon-edit.icon32-posts-homepage_sliders {background: url('<?php echo plugins_url('banner_events/images/menu-image.png'); ?>') no-repeat 6px 9px; }
    </style>
<?php }
add_action( 'admin_head', 'ls_homepage_sliders_icons' );

function ss_be_mbe_create() {
	add_meta_box('ss_banner_events_meta', 'Banner Events Information', 'ss_banner_events_mbe_function', 'banner_events', 'normal', 'high' );
}

function ss_banner_events_mbe_function( $post ) {
	$ss_be_mbe_title = get_post_meta($post->ID, '_ss_be_mbe_title', true);
	$ss_be_mbe_link = get_post_meta($post->ID, '_ss_be_mbe_link', true);
	$ss_be_mbe_fb = get_post_meta($post->ID, '_ss_be_mbe_fb', true);
	$ss_be_mbe_when = get_post_meta($post->ID, '_ss_be_mbe_when', true);
	$ss_be_mbe_isweekly = (get_post_meta($post->ID, '_ss_be_mbe_isweekly', true) == 'yes') ? 'checked="yes"' : '';
	$ss_be_mbe_weeklyDay = get_post_meta($post->ID, '_ss_be_mbe_weeklyDay', true);
	$ss_be_mbe_isfrequent = (get_post_meta($post->ID, '_ss_be_mbe_isfrequent', true) == 'yes') ? 'checked="yes"' : '';
	$ss_be_mbe_stick2home = (get_post_meta($post->ID, '_ss_be_mbe_stick2home', true) == 'yes') ? 'checked="yes"' : '';
	$ss_be_mbe_where = get_post_meta($post->ID, '_ss_be_mbe_where', true);
	$ss_be_mbe_image = get_post_meta($post->ID, '_ss_be_mbe_image', true);

	echo "Please fill out the information for this event below.";
	?>
	<p>Post ID: <? echo $post->ID; ?></p>
	<input type='checkbox' name='ss_be_mbe_stick2home' value='yes' <?echo $ss_be_mbe_stick2home ?>> Advertise for Home Page</p>
	<p>Title:<BR>
	<input type='text' name='ss_be_mbe_title' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_title); ?>'> </p>
	<p>When:<BR>
    <input type='text' id="ss_be_mbe_when_text" name='ss_be_mbe_when' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_when); ?>'>
    <div id="when_select" style="display:none;">
        <select id="ss_be_mbe_when_select" name="ss_be_mbe_weeklyDay">
            <option value="Monday">Mondays</option>
            <option value="Tuesday">Tuesdays</option>
            <option value="Wednesday">Wednesdays</option>
            <option value="Thursday">Thursdays</option>
            <option value="Friday">Fridays</option>
            <option value="Saturday">Saturdays</option>
            <option value="Sunday">Sundays</option>
        </select><br />
    </div>
    <input type='checkbox' id="ss_be_mbe_isweekly" name='ss_be_mbe_isweekly' onChange="weekly_select()" value='yes' <?echo $ss_be_mbe_isweekly ?>> Weekly Dance</p>
	<input type='checkbox' name='ss_be_mbe_isfrequent' value='yes' <?echo $ss_be_mbe_isfrequent ?>> Other Frequency</p>
	<p>Where:<BR>
	<input type='text' name='ss_be_mbe_where' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_where); ?>'> </p>
	<p>Link to Event:<BR>
	<input type='text' name='ss_be_mbe_link' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_link); ?>'> </p>
	<p>Link to Facebook Event: <span style='color:#A0A0A0'>(optional)</span><BR>
	<input type='text' name='ss_be_mbe_fb' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_fb); ?>'> </p>
	<p>Banner Image: <span style='color:#A0A0A0'>(optimized for size: 150h x 625w)</span><BR>
	<input id='ss_be_mbe_image' type='text' size='60' name='ss_be_mbe_image' value ='<? echo esc_attr($ss_be_mbe_image); ?>'> 
	<input id='put_img' type='button' onclick='put_img_into_place()' value='View Image' class='button-secondary' /><br><input id='upload_image_button' type='button' value='Media Library Image' class='button-secondary' />  
	Enter a URL to an image or use an image from the Media Library</p>
	<center><div style='background-color:#FFF; border: 1px solid #000; width: 625px;'><img id='ss_be_mbe_img_obj' src='<? echo esc_attr($ss_be_mbe_image); ?>' style='max-width:625px; max-height:150px;'></div></center>
	<script>
		function put_img_into_place() {
			var theIMG = document.getElementById('ss_be_mbe_image').value;
			document.getElementById('ss_be_mbe_img_obj').src = theIMG;
		}
        function weekly_select() {
            var theCheckbox = document.getElementById('ss_be_mbe_isweekly');
            if (theCheckbox.checked) {
                document.getElementById('ss_be_mbe_when_select').disabled = false;
                document.getElementById('when_select').style.display = 'inline';
                for (var i=0; i<document.getElementById('ss_be_mbe_when_select').length;i++) {
                    if ( "<? echo $ss_be_mbe_weeklyDay; ?>" == document.getElementById('ss_be_mbe_when_select')[i].value) {
                        document.getElementById('ss_be_mbe_when_select')[i].selected = "Selected";
                        break;
                    }
                }
            } else {
                document.getElementById('ss_be_mbe_when_select').disabled = true;
                document.getElementById('when_select').style.display = 'none';
            }
        }
        weekly_select();
	</script>
	<?
}

add_action('save_post', 'ss_be_mbe_save_meta');

function ss_be_mbe_save_meta( $post_id ) {
	add_post_meta($post_id, '_ss_be_mbe_title', strip_tags($_POST['ss_be_mbe_title']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_title', strip_tags($_POST['ss_be_mbe_title']) );
	add_post_meta($post_id, '_ss_be_mbe_link', strip_tags($_POST['ss_be_mbe_link']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_link', strip_tags($_POST['ss_be_mbe_link']) );
	add_post_meta($post_id, '_ss_be_mbe_fb', strip_tags($_POST['ss_be_mbe_fb']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_fb', strip_tags($_POST['ss_be_mbe_fb']) );
	add_post_meta($post_id, '_ss_be_mbe_when', strip_tags($_POST['ss_be_mbe_when']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_when', strip_tags($_POST['ss_be_mbe_when']) );
	add_post_meta($post_id, '_ss_be_mbe_isfrequent', strip_tags($_POST['ss_be_mbe_isfrequent']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_isfrequent', strip_tags($_POST['ss_be_mbe_isfrequent']) );
    add_post_meta($post_id, '_ss_be_mbe_isweekly', strip_tags($_POST['ss_be_mbe_isweekly']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_isweekly', strip_tags($_POST['ss_be_mbe_isweekly']) );
    add_post_meta($post_id, '_ss_be_mbe_weeklyDay', strip_tags($_POST['ss_be_mbe_weeklyDay']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_weeklyDay', strip_tags($_POST['ss_be_mbe_weeklyDay']) );
	add_post_meta($post_id, '_ss_be_mbe_stick2home', strip_tags($_POST['ss_be_mbe_stick2home']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_stick2home', strip_tags($_POST['ss_be_mbe_stick2home']) );
	add_post_meta($post_id, '_ss_be_mbe_where', strip_tags($_POST['ss_be_mbe_where']) , true ) or update_post_meta( $post_id, '_ss_be_mbe_where', $_POST['ss_be_mbe_where'] );
	if ( isset($_POST['ss_be_mbe_image']) ){
		update_post_meta( $post_id, '_ss_be_mbe_image', esc_url_raw( $_POST['ss_be_mbe_image']) );
	}	
}

add_action('admin_print_scripts-post.php', 'ss_be_mbe_admin_scripts');
add_action('admin_print_scripts-post-new.php', 'ss_be_mbe_admin_scripts');

function ss_be_mbe_admin_scripts() {
	wp_enqueue_script('ss-image-upload', 
			plugins_url('/banner_events/js/settings-image.js'), 
			array('jquery','media-upload','thickbox' ) 
	);
}

add_action('admin_print_styles-post.php', 'ss_be_mbe_image_image_styles');
add_action('admin_print_styles-post-new.php', 'ss_be_mbe_image_image_styles');

function ss_be_mbe_image_image_styles() {
	wp_enqueue_style( 'thickbox' );
}

add_shortcode('banner-events' , 'ss_be_display_banners');

function ss_be_display_banners($atts){
	$return_string = "<div><hr style='color: #D0D0D0; background-color: #D0D0D0;'>
<h1>Events</h1>
<hr style='color: #D0D0D0; background-color: #D0D0D0;'>";
	$show_img = true;
	if ( isset($atts['hide_img']) ) {
		$show_img = false;
	}
	$theFilter = '';
	if ( isset($atts['type']) ) {
		$theFilter = $atts['type'];
		if ( $theFilter == 'dances') {
			$return_string = "<div><hr style='color: #D0D0D0; background-color: #D0D0D0;'>
<h1>Dances</h1>
<hr style='color: #D0D0D0; background-color: #D0D0D0;'>";
		}
	}
	$unique_post = '';
	if ( isset($atts['post_id']) ) {
		$unique_post = $atts['post_id'];
		$return_string = "<div>";
	}
	$exclude_ids = array();
	if ( isset($atts['exclude_ids']) ) {
		$exclude_raw = $atts['exclude_ids'];
		$exclude_ids = preg_split('/,/',$exclude_raw); //get each entry by: $exclude_ids[$i][0]
	}
	if ( isset($atts['title']) ) {
		$title = $atts['title'];
		$return_string = "<div><hr style='color: #D0D0D0; background-color: #D0D0D0;'>
<h1>".$title."</h1>
<hr style='color: #D0D0D0; background-color: #D0D0D0;'>";
	}
	$designate = '';
	if ( isset($atts['designate']) ) {
		$designate = $atts['designate'];
		$return_string = "<div>";
	}
	if ( isset($atts['details_only']) ) {
		$show_img = false;
		$return_string = "<div>";
	}
	$return_string .= "
<style type='text/css'>
	.banner_single_wrapper {
		width:600px; 
		margin: 0 auto;
	}
	.banner-image:hover img{
		border: 2px #000000 solid;
	}
	.banner-image img{
		border: 2px #D0D0D0 solid;
	}
	.the_banner_img{
		max-width:600px; 
		max-height:150px;
	}
	@media screen and (max-width: 524px) {
		.banner_single_wrapper {
			width:280px; 
		}
  		.the_banner_img{
			max-width:260px; 
			max-height:90px;
		}
	}
</style> ";
	$args = array( 'post_type' => 'banner_events');
	$loop = new WP_Query( $args );
	$first = true;
	while ( $loop->have_posts() ) {
		$loop->the_post();
		$id = $loop->post->ID;
		$title =  $loop->post->post_title;
		$desc =  $loop->post->post_content;
		$meta = get_post_meta($id);
		$sub_title = $meta['_ss_be_mbe_title'][0];
		$link = $meta['_ss_be_mbe_link'][0];
		$fb = $meta['_ss_be_mbe_fb'][0];
		$when = $meta['_ss_be_mbe_when'][0];
		$isFrequent = $meta['_ss_be_mbe_isfrequent'][0];
		$isWeekly = $meta['_ss_be_mbe_isweekly'][0];
		$weekDay = $meta['_ss_be_mbe_WeeklyDay'][0];
		$stick2Home = $meta['_ss_be_mbe_stick2home'][0];
		$where = $meta['_ss_be_mbe_where'][0];
		$image_url = $meta['_ss_be_mbe_image'][0];
		$this_string = '';
		$this_string .= "<div class='banner_single_wrapper'>";
		if ( $image_url != '' && $show_img ) {
			$this_string .= "
				<a class='banner-image' href='".$link."' target=_new><img class='the_banner_img' src='".$image_url."' ></a>";
		}
		if ( isset($atts['details_only']) ) {
			$this_string .= "
			<h1>".$title."</h1>";
		} else {
			$this_string .= "
			<h1><a class='regular_link' href='".$link."' target=_new>".$title."</a></h1>";
		}
			$this_string .= "
			<i style='font-size:120%'>".$sub_title."</i><BR>
			<b><small>WHEN:</small></b> ".$when."<br>
			<b><small>WHERE:</small></b> ".$where."<br>";
		if ( isset($atts['extra_field']) && isset($atts['extra_value']) ) {
			$this_string .="
			<b><small>".strtoupper($atts['extra_field']).":</small></b> ".$atts['extra_value']."<br>";
		}
		if ( $fb != '' ) {
			$this_string .= "
			<b><small>FACEBOOK EVENT:</small></b> <a href='".$fb."' target=_blank>".$fb."</a><br>";
		}
		if ( !isset($atts['hide_desc']) ) {
			$this_string .= "
			<br>
			".$desc."
			<BR><BR>";
		}
		$this_string .= "
			</div>";
		$this_string .= "<BR><hr style='color: #D0D0D0; background-color: #D0D0D0;'>";
		if ( $theFilter == 'dances' && ($isFrequent == '' || $isWeekly == '' )) {
			$this_string = '';
		}
		if ( $theFilter == 'events' && ($isFrequent == 'yes' || $isWeekly == 'yes') ) {
			$this_string = '';
		}		
		if ( $unique_post != '') {
			if ( intval($unique_post) != $id ) {
				$this_string = '';
			}
		}
		if ( $designate != '') {
			if ( $designate == 'home' && $stick2Home == '' ) {
				$this_string = '';
			}
		}
		if ( count($exclude_ids) != 0) {
			for ($i=0;$i<count($exclude_ids);$i++) {
				if ( intval($exclude_ids[$i]) == $id ) {
					$this_string = '';
					array_pop($exclude_ids);
					break;
				}
			}
		}			
		$return_string .= $this_string;
	}
	$return_string .= "</div>";
	return $return_string;
}

?>
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
	$ss_be_mbe_isspecific = (get_post_meta($post->ID, '_ss_be_mbe_isspecific', true) == 'yes') ? 'checked="yes"' : '';
	$ss_be_mbe_isfrequent = (get_post_meta($post->ID, '_ss_be_mbe_isfrequent', true) == 'yes') ? 'checked="yes"' : '';
	$ss_be_mbe_stick2home = (get_post_meta($post->ID, '_ss_be_mbe_stick2home', true) == 'yes') ? 'checked="yes"' : '';
	$ss_be_mbe_where = get_post_meta($post->ID, '_ss_be_mbe_where', true);
	$ss_be_mbe_image = get_post_meta($post->ID, '_ss_be_mbe_image', true);

	$ss_be_mbe_weeklyDay = preg_split("/ \| /",$ss_be_mbe_when);

	echo "Please fill out the information for this event below.";
	?>
	<form>
	<p>Post ID: <? echo $post->ID; ?></p>
	<input type='checkbox' name='ss_be_mbe_stick2home' value='yes' <?echo $ss_be_mbe_stick2home ?>> Advertise for Home Page</p>
	<p>Title:<br />
	<input type='text' name='ss_be_mbe_title' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_title); ?>'> </p>
	<p>When: <span id="when_text" style="display:inline-block;"><input type='text' id="ss_be_mbe_when_text" name='ss_be_mbe_when' style="width:500px;" value ='<? echo esc_attr($ss_be_mbe_when); ?>' /></span>
	<table width="100%">
		<tr><td><center><input type='checkbox' id="ss_be_mbe_isspecific" name="ss_be_mbe_isspecific" onChange="show_specific_input();max_select(this);" value='yes' <?echo $ss_be_mbe_isspecific ?> /> Specific Date(s)</center></td>
		<td><center><input type='checkbox' id="ss_be_mbe_isweekly" name='ss_be_mbe_isweekly' onChange="weekly_select(); max_select(this);" value='yes' <?echo $ss_be_mbe_isweekly ?> /> Weekly Dance</center></td>
		<td><center><input type='checkbox' name='ss_be_mbe_isfrequent' id='ss_be_mbe_isfrequent' value='yes' onchange="frequency_select(); max_select(this);" <?echo $ss_be_mbe_isfrequent ?> /> Other Frequency</center></td></tr>
	</table>
	<div id="specific_date" style="display:none;"><blockquote>
			<span style=" width: 60px; display:inline-block">Begin: </span><input type='text' id="ss_be_mbe_when_begin" name='ss_be_mbe_when_begin' style="width: 100px;" />
		<br />
			<span style=" width: 60px; display:inline-block">End: </span><input type='text' id="ss_be_mbe_when_end" name='ss_be_mbe_when_end' style="width:100px;" value ='' />*required
	</blockquote></div>

    
     <div id="when_select" style="display:none;"><blockquote>
		<table cellpadding="0" cellspacing="0" style="margin:0px; padding:0px;"><tr><td>
        <select id="ss_be_mbe_when_select" onchange="set_weekly(this);set_frequency();" name="ss_be_mbe_weeklyDay">
            <option>-Select Day-</option>
            <option value="Monday">Mondays</option>
            <option value="Tuesday">Tuesdays</option>
            <option value="Wednesday">Wednesdays</option>
            <option value="Thursday">Thursdays</option>
            <option value="Friday">Fridays</option>
            <option value="Saturday">Saturdays</option>
            <option value="Sunday">Sundays</option>
        </select> 
		<input type="button" onclick="addWeekdaySelect();" value="Add Extra Day" />
		</td><td width="50px"></td><td>
		<span id="frequency_select" style="display:none width: 300px;">
			<table width="500px"><tr><td>1st</td><td>2nd</td><td>3rd</td><td>4th</td><td>Occassional 5th</td></tr>
				<tr><td><input id="ss_be_mbe_freq_1" type="checkbox" onchange="set_frequency();" value="1"></td>
					<td><input id="ss_be_mbe_freq_2" type="checkbox" onchange="set_frequency();" value="2"></td>
					<td><input id="ss_be_mbe_freq_3" type="checkbox" onchange="set_frequency();" value="3"></td>
					<td><input id="ss_be_mbe_freq_4" type="checkbox" onchange="set_frequency();" value="4"></td>
					<td><input id="ss_be_mbe_freq_5" type="checkbox" onchange="set_frequency();" value="5"></td>
				</tr>
			</table>
		</span>
		</td></tr></table>
		<?
		for ($i=2;$i<8;$i++){
			echo '
		<div id="weekday_select'.$i.'" style="display:none;">
	        <select id="ss_be_mbe_weekday_select'.$i.'" onchange="set_weekly_addition(this);" this_index="'.$i.'">
	            <option>-Select Day-</option>
	            <option value="Monday">Mondays</option>
	            <option value="Tuesday">Tuesdays</option>
	            <option value="Wednesday">Wednesdays</option>
	            <option value="Thursday">Thursdays</option>
	            <option value="Friday">Fridays</option>
	            <option value="Saturday">Saturdays</option>
	            <option value="Sunday">Sundays</option>
	        </select>
			<input type="button" onclick="removeWeekdaySelect('.$i.');" value="Remove Extra Day" />
		</div>
		';
		}
		?>
    </blockquote></div><br />
	</p>
	<p>Where:<BR>
	<input type='text' name='ss_be_mbe_where' id='ss_be_mbe_where' style="width:100%" value ='<? echo esc_attr($ss_be_mbe_where); ?>'> </p>
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
		var extra_weekday_indices = new Array();
		var extra_weekday_values = new Array("","","","","","","");
		function put_img_into_place() {
			var theIMG = document.getElementById('ss_be_mbe_image').value;
			document.getElementById('ss_be_mbe_img_obj').src = theIMG;
		}

		function checkExtraWeekDays() {
			for (i=0;i<window.extra_weekday_values.length;i++ ) {
				if ( window.extra_weekday_values[i] != ""){
					if (i != 0 ) {
						window.extra_weekday_indices.push(i+1);
						select_id = 'weekday_select'+(i+1);
						document.getElementById(select_id).style.display = 'block';
					}
				}
			}
		}
		function addWeekdaySelect() {
			for (i=2;i<8;i++ ){
				if (window.extra_weekday_indices.indexOf(i) == -1 ) {
					window.extra_weekday_indices.push(i);
					select_id = 'weekday_select'+i;
					document.getElementById(select_id).style.display = 'block';
					break;
				}
			}
		}
		function modifyWeekdaySelectors() {
			input_names = new Array('ss_be_mbe_when_select');
			for (i=2;i<8;i++ ) {
				input_names.push('ss_be_mbe_weekday_select'+i);
			}
			for (i=0;i<input_names.length;i++ ) {
				thisInput = document.getElementById(input_names[i]);
				for (j=0;j<thisInput.length;j++) {
					if (thisInput.selectedIndex != j && 
							window.extra_weekday_values.indexOf(thisInput[j].value) != -1) {
						document.getElementById(input_names[i])[j].disabled = true;
					} else {
						document.getElementById(input_names[i])[j].disabled = false;
					}
				}
			}
		}
		function removeWeekdaySelect(value) {
			index = window.extra_weekday_indices.indexOf(value);
			window.extra_weekday_indices.splice(index,1);
			select_id = 'weekday_select'+value;
			document.getElementById(select_id).style.display = 'none';
			document.getElementById('ss_be_mbe_weekday_select'+value).selectedIndex = 0;

			window.extra_weekday_values[(value-1)] = "";
			update_when_text_forWeekly();
		}
		function update_when_text_forWeekly() {
			modifyWeekdaySelectors()
			new_when_text = document.getElementById('ss_be_mbe_when_text').value;
			days = "";
			for (i=0;i<window.extra_weekday_values.length;i++) {
				if ( window.extra_weekday_values[i] != "" ) {
					if (i== 0 ){
						days = window.extra_weekday_values[i];
					} else {
						days = days+' | '+window.extra_weekday_values[i];
					}
				}
			}
			document.getElementById('ss_be_mbe_when_text').value = days;
		}
		function checkWhenSelected() {
			if (document.getElementById('ss_be_mbe_isspecific').checked) {
				var dates = document.getElementById('ss_be_mbe_when_text').value.split(' | ');
				for (index=0; index<dates.length;index++) {
					if (dates[index].indexOf("specific:") != -1 && index == 0) {
						the_date = dates[index].replace("specific:","");
						document.getElementById("ss_be_mbe_when_begin").value = the_date;
					} else if (dates[index].indexOf("specific:") != -1 && index == 1) {
						the_date = dates[index].replace("specific:","");
						document.getElementById("ss_be_mbe_when_end").value = the_date;
					}
				}
				show_specific_input();
			} else if (document.getElementById('ss_be_mbe_isweekly').checked) {
				weekly_select();
			} else if (document.getElementById('ss_be_mbe_isfrequent').checked) {
				frequency_select();
				info = document.getElementById('ss_be_mbe_when_text').value.split(' | ');
				day = info[0];
				freq = info[1].split(',');
				document.getElementById('ss_be_mbe_when_select').value = day;
				for (index=0;index<freq.length;index++) {
					document.getElementById("ss_be_mbe_freq_"+freq[index]).checked = true;
				}
			}
		}
		function max_select(this_checkbox) { 
			if (this_checkbox.checked) {
				document.getElementById('ss_be_mbe_when_text').value = "";
				for (i=1;i<6;i++) {
					document.getElementById("ss_be_mbe_freq_"+i).checked = false;
				}
				document.getElementById('ss_be_mbe_when_select').selectedIndex = 0;
				switch(this_checkbox.name){
					case "ss_be_mbe_isfrequent":
						document.getElementById("ss_be_mbe_isspecific").checked = false;
						document.getElementById("ss_be_mbe_when_begin").value = "";
						document.getElementById("ss_be_mbe_when_end").value = "";
						document.getElementById('specific_date').style.display = 'none';
						document.getElementById("ss_be_mbe_isweekly").checked = false;
						break;
					case "ss_be_mbe_isspecific":
						document.getElementById("ss_be_mbe_isfrequent").checked = false;
                		document.getElementById('when_select').style.display = 'none';
						document.getElementById('frequency_select').style.display = 'none';
						document.getElementById("ss_be_mbe_isweekly").checked = false;
						break;
					case "ss_be_mbe_isweekly":
						document.getElementById("ss_be_mbe_isspecific").checked = false;
						document.getElementById("ss_be_mbe_when_begin").value = "";
						document.getElementById("ss_be_mbe_when_end").value = "";
						document.getElementById('specific_date').style.display = 'none';
						document.getElementById("ss_be_mbe_isfrequent").checked = false;
						document.getElementById('frequency_select').style.display = 'none';
						break;
					default:
						break;
				}
			}
		}
		function set_weekly_addition(opt) {
			if ( window.extra_weekday_values.indexOf(opt.value) == -1 ) {
				index = opt.getAttribute("this_index");
				window.extra_weekday_values[(parseInt(index)-1)] = opt.value;
				update_when_text_forWeekly();
			}
		}
		function set_weekly(opt) {
			if (document.getElementById("ss_be_mbe_isweekly").checked){
				document.getElementById('ss_be_mbe_when_text').value = opt.value;
				window.extra_weekday_values[0] = opt.value;
			}
		}
		function frequency_select() {
            document.getElementById('ss_be_mbe_when_select').disabled = false;
            document.getElementById('when_select').style.display = 'inline';
			document.getElementById('frequency_select').style.display = 'inline-block';
		}
		function set_frequency() {
			if (document.getElementById("ss_be_mbe_isfrequent").checked){
				var date_freq = '';
				date_freq = document.getElementById('ss_be_mbe_when_select').value;
				if (date_freq != '-Select Day-') {
					date_freq = date_freq+' | ';
					for (i=1;i<6;i++) {
						if (document.getElementById("ss_be_mbe_freq_"+i).checked) {
							date_freq = date_freq+document.getElementById("ss_be_mbe_freq_"+i).value+",";
						}
					}
					document.getElementById('ss_be_mbe_when_text').value = date_freq;
				}
			}
		}
        function weekly_select() {
            var theCheckbox = document.getElementById('ss_be_mbe_isweekly');
            if (theCheckbox.checked) {
                document.getElementById('ss_be_mbe_when_select').disabled = false;
                document.getElementById('when_select').style.display = 'inline';
                document.getElementById('frequency_select').style.display = 'none';
				<?
				for ($i=0;$i< count($ss_be_mbe_weeklyDay);$i++) { 
					if ($i == 0 ) {
				?>
		                for (var i=0; i<document.getElementById('ss_be_mbe_when_select').length;i++) {
		                    if ( "<? echo $ss_be_mbe_weeklyDay[$i]; ?>" == document.getElementById('ss_be_mbe_when_select')[i].value) {
								window.extra_weekday_values[<? echo $i ?>] = document.getElementById('ss_be_mbe_when_select')[i].value;
		                        document.getElementById('ss_be_mbe_when_select')[i].selected = "Selected";
		                        break;
		                    }
						}
					<? 	} else { 
						$index = $i+1;
					?>
						for (var i=0; i<document.getElementById('ss_be_mbe_weekday_select<? echo $index ?>').length;i++) {
		                    if ( "<? echo $ss_be_mbe_weeklyDay[$i]; ?>" == document.getElementById('ss_be_mbe_weekday_select<? echo $index ?>')[i].value) {
								window.extra_weekday_values[<? echo $i ?>] = document.getElementById('ss_be_mbe_weekday_select<? echo $index ?>')[i].value;
		                        document.getElementById('ss_be_mbe_weekday_select<? echo $index ?>')[i].selected = "Selected";
		                        break;
		                    }
						}
					<? } 
				} ?>
				update_when_text_forWeekly();
            } else {
                document.getElementById('ss_be_mbe_when_select').disabled = true;
                document.getElementById('when_select').style.display = 'none';
            }
        }
		function show_specific_input() {
			var theCheckbox = document.getElementById('ss_be_mbe_isspecific');
            if (theCheckbox.checked) {
				document.getElementById('specific_date').style.display = 'inline';
			} else {
				document.getElementById('specific_date').style.display = 'none';
			}
		}
		$(function() {
			 $( "#ss_be_mbe_when_begin" ).datepicker( {
        			onSelect: function(date) {
            			document.getElementById('ss_be_mbe_when_text').value = "specific:"+date;
        			}
				});
			 $( "#ss_be_mbe_when_end" ).datepicker( {
        			onSelect: function(date) {
						begin = document.getElementById("ss_be_mbe_when_begin").value;
            			document.getElementById('ss_be_mbe_when_text').value = "specific:"+begin+' | '+"specific:"+date;
        			}
				});
  		});
        weekly_select();
        checkWhenSelected();
		checkExtraWeekDays();
	</script>
	</form>
	<?
}

add_action('save_post', 'ss_be_mbe_save_meta');

function ss_be_mbe_save_meta( $post_id ) {
	add_post_meta($post_id, '_ss_be_mbe_title', strip_tags($_POST['ss_be_mbe_title']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_title', strip_tags($_POST['ss_be_mbe_title']) );
	add_post_meta($post_id, '_ss_be_mbe_link', strip_tags($_POST['ss_be_mbe_link']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_link', strip_tags($_POST['ss_be_mbe_link']) );
	add_post_meta($post_id, '_ss_be_mbe_fb', strip_tags($_POST['ss_be_mbe_fb']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_fb', strip_tags($_POST['ss_be_mbe_fb']) );
	add_post_meta($post_id, '_ss_be_mbe_when', strip_tags($_POST['ss_be_mbe_when']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_when', strip_tags($_POST['ss_be_mbe_when']) );
	add_post_meta($post_id, '_ss_be_mbe_isfrequent', strip_tags($_POST['ss_be_mbe_isfrequent']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_isfrequent', strip_tags($_POST['ss_be_mbe_isfrequent']) );
	add_post_meta($post_id, '_ss_be_mbe_isspecific', strip_tags($_POST['ss_be_mbe_isspecific']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_isspecific', strip_tags($_POST['ss_be_mbe_isspecific']) );
    add_post_meta($post_id, '_ss_be_mbe_isweekly', strip_tags($_POST['ss_be_mbe_isweekly']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_isweekly', strip_tags($_POST['ss_be_mbe_isweekly']) );
    add_post_meta($post_id, '_ss_be_mbe_weeklyDay', strip_tags($_POST['ss_be_mbe_weeklyDay']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_weeklyDay', strip_tags($_POST['ss_be_mbe_weeklyDay']) );
	add_post_meta($post_id, '_ss_be_mbe_stick2home', strip_tags($_POST['ss_be_mbe_stick2home']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_stick2home', strip_tags($_POST['ss_be_mbe_stick2home']) );
	add_post_meta($post_id, '_ss_be_mbe_where', strip_tags($_POST['ss_be_mbe_where']) , true ) 
						or update_post_meta( $post_id, '_ss_be_mbe_where', $_POST['ss_be_mbe_where'] );
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
	wp_enqueue_script('jquery-1.9.1','http://code.jquery.com/jquery-1.9.1.js');
	wp_enqueue_script('datepicker','http://code.jquery.com/ui/1.10.3/jquery-ui.js');
}

add_action('admin_print_styles-post.php', 'ss_be_mbe_image_image_styles');
add_action('admin_print_styles-post-new.php', 'ss_be_mbe_image_image_styles');

function ss_be_mbe_image_image_styles() {
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style('smoothness','http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
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
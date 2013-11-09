<?php
/*
Title: Settings for Banner Events
Description: Settings for Banner Events plugin
*/
function ss_banner_events_options_page() {
	?>
<div class="wrap"><table style='float: left; width: 100%;'><TR><TD>
	<?php screen_icon(); ?>
	<h2>Banner Events</h2>
	<h3>Explanation of Shortcodes:</h3>

	Shortcode base: <b>[banner-events]</b><br /><br />

	Banner Events create display objects for specific events or ongoing events. These events have information attached to them that help categorize them into different areas. A few noteable categories are freqency and designation. For frequency the shortcode key is <b>type</b> and the possible values are <b>dances</b> (at least once a month) and <b>events</b>.
<br />
<br />
For area-specific (namely home screen), you can designate posts to that area; the name for this key is <b>designate</b> and currently the sole value possible is <b>home</b>. You can also simply put just one event on a page by using the key <b>post_id</b> where you then look at the event Post ID in that event's information and use that as a value. If the value is invalid, no post will be shown. You can also exclude events by using the key <b>exclude_ids</b> where you then list the ids that you would like to exclude separated by commas.
<br />
<br />
As for formatting, you can add a title, remove banner images, hide descriptions and add an extra information field on the fly. For title, the key is <b>title</b> and the value can be any title you wish. To remove the banner image the key is <b>details_only</b> and the sole value is an empty value <b>'' or ""</b>. To hide the description the key is <b>hide_desc</b> with the same empty value as <i>details_only</i>. Lastly, to add an extra information field for your event, you have two key/value pairs required. Firstly, you need to give the information title with key <b>extra_field</b>. Secondly, you need to give the description for this information in the key <b>extra_value</b>.
<br />
<br />
If you are having trouble, make sure that your single or double quotes are not breaking the key->value tags and that the post_ids are referencing actual events. Otherwise, enjoy!
	<br />
	<br />
	<h3>Examples:</h3>
	<blockquote>
		[banner-events designate='home']<br />
		[banner-events post_id='2271']<br />
		[banner-events type='dances' title='Our Partners' exclude_ids='2271,2289']<br />
		[banner-events type='events']<Br />
		[banner-events post_id='2364' title='' details_only='' hide_desc='' extra_field='How Much' extra_value="$20"]
	</blockquote>
</TD><TD style='width:200px; border: 1px solid #D0D0D0; border-width: 0px 0px 0px 1px;' valign='top'>
	<center>
	<h3 style='margin-bottom:0px;'>Products Plugin</h3>
	by <a href='http://www.stevenandleah.com'>Steven Stevenson</a><BR>
	email: <a href="mailto:stevensonhoyt@icloud.com">stevensonhoyt@icloud.com</a>
	</center>
	<BR>
</TD></TR></TABLE></div>
	<?
}
?>
<?php

/**
 * Fetch data
 */
require_once(SRFW_PLUGIN_DIR . 'include/srfw-functions.php');
function srfw_fetch_data($title, $limit, $rss_transient_val)
{
	$rss_feed_op = '';
	wp_enqueue_style('srfw-feed-styles');
	wp_enqueue_style('srfw-custom-icon');
	$all_data = srfw_get_data_api($title, $limit, $rss_transient_val);
	$prefix_name = 'r/';
	$display_name = $prefix_name . $title;
	$default_logo = SRFW_PLUGIN_URL . 'assets/images/reddit.png';
	$rss_feed_op .= '<div class="rss-feed-main-cls">
     <ul class="rss-feed-wrapper">
     <li class="rss-feed-header"> <div class="rss-feed-logo"><i class="rss-feed-reddit"></i></div>
     <div class="feed-name">' . $display_name . '</div></li>';
	$time_suff = 'ago';
	if (is_array($all_data) && !empty($all_data)) {
		$count = 1;
		foreach ($all_data as $value) {
			$get_feed_title =  $value['get_feed_title'];
			$permalink = $value['permalink'];
			$feed_link =  $value['feed_link'];
			$get_author_name = $value['get_author_name'];
			$get_feed_comment =  $value['get_feed_comment'];
			$user_name = $value['user_name'];
			$created =  $value['created'];
			$logo =  $value['logo'];
			$feed_ups = $value['feed_ups'];
			$time1 =  rss_get_timeago($created);
			$rss_feed_op .=  '<li class="rss-feed-section">
        	<div class="rss-feed-data">';
			if ($logo != 'self') {
				$rss_feed_op .= '<div class="rss-media">
        		<div class="rss-r-img"><img src=' . $logo . '></div>
			   </div>';
			} else {
				$rss_feed_op .=   '<div class="rss-media">
        		<div class="rss-r-img"><img src=' . $default_logo . '></div>
			   </div>';
			}
			$rss_feed_op .= '<div class="rss-feed-content">
          <div class="feed-title"><a href=' . $feed_link . ' target="_blank">' . $get_feed_title . '</a></div>
          <span class="author-name">' . $get_author_name . '</span>
          <div class="coinmc-r-stat"><i class="rss-feed-up"></i>' . $feed_ups . '
          <i class="rss-feed-comment"></i>' . $get_feed_comment . '
          <i class="rss-feed-clock"></i>' . $time1 . 'ago</div>
          </div></div>
          </li>';
			$total_count =  $count++;
			if ($total_count == $limit) break;
		}
		$rss_feed_op .=  '</ul>
       </div>';
	} else if ($all_data == 'Not Found') {
		$rss_feed_op .= '<div class="rss-no-user">' . __('No user found', 'rss-feed') . '</div>';
	} else {
		$rss_feed_op .=  '<div class="rss-no-feed">' . __('No Data Found', 'rss-feed') . '</div>';
	}
	echo $rss_feed_op;
}

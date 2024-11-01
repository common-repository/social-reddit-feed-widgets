<?php

/**
 *  All Function Here
 */
function srfw_get_data_api($title, $limit, $rss_transient_val)
{
    $cache_pre = "rss_feed_";
    $token_validity = $rss_transient_val;
    $user_ext = '.json';
    $user_name = $title . $user_ext;
    $user_title = $title . '_' . $limit . '_' . $token_validity;
    $cache_name = $cache_pre . $user_title;
    $get_transient = get_transient($cache_name);
    if ($get_transient) {
        return $get_transient;
    } else {
        if ($user_name == '.json') {
            return;
        }
        $rss_feed_url = "https://www.reddit.com/r/$user_name?limit=$limit;";
        $api_request = wp_remote_get($rss_feed_url);
        if (is_wp_error($api_request)) {
            return false;
        }
        $request_body = wp_remote_retrieve_body($api_request);
        $decode_data = json_decode($request_body);
        $api_data = rss_objectToArray($decode_data);
        if (isset($api_data['error'])) {
            return $api_data['message'];
        } else {
            $data =   isset($api_data['data']) ? $api_data['data'] : '';
            if ($data != '') {
                $all_data = array();
                $feed_data_val = array();
                foreach ($data['children'] as $rss_feed_key => $rss_feed_value) {
                    $feed_data = $rss_feed_value['data'];
                    $feed_data_val['get_feed_title'] = $feed_data['title'];
                    $feed_data_val['permalink'] = isset($feed_data['permalink']) ? $feed_data['permalink'] : '';
                    $feed_data_val['feed_link'] = 'https://www.reddit.com' . $feed_data_val['permalink'] . '';
                    $feed_data_val['get_author_name'] = isset($feed_data['author']) ? $feed_data['author'] : '';
                    $feed_data_val['get_feed_comment'] = isset($feed_data['num_comments']) ? $feed_data['num_comments'] : '';
                    $feed_data_val['user_name'] = isset($feed_data['subreddit_name_prefixed']) ? $feed_data['subreddit_name_prefixed'] : '';
                    $feed_data_val['created'] = isset($feed_data['created_utc']) ? $feed_data['created_utc'] : '';
                    $feed_data_val['logo'] = isset($feed_data['thumbnail']) ? $feed_data['thumbnail'] : '';
                    $feed_data_val['feed_ups'] = isset($feed_data['ups']) ? $feed_data['ups'] : '';
                    $all_data[] = $feed_data_val;
                }
            }
            set_transient($cache_name, $all_data, $token_validity);
            return $all_data;
        }
    }
}
/*
|--------------------------------------------------------------------------
|  Convert object into array
|--------------------------------------------------------------------------
 */
function rss_objectToArray($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}
// date formating
function rss_get_timeago($ptime)
{
    $time = new DateTime();
    $time->setTimestamp($ptime);

    $now = new DateTime();

    $dif = $time->diff($now);

    $html = '';


    if ($dif->y > 1) {
        $html .= $dif->y . ' year(s) ';
    }
    if ($dif->y == 1) {
        $html .= $dif->y . ' year ';
    }
    if ($dif->m > 1) {
        $html .= $dif->m . ' month(s) ';
    }
    if ($dif->m == 1) {
        $html .= $dif->m . ' month ';
    }
    if ($dif->d > 1 && $dif->y == 0) {
        $html .= $dif->d . ' day(s) ';
    }
    if ($dif->d == 1 && $dif->y == 0) {
        $html .= $dif->d . ' day ';
    }
    if ($dif->h > 1 && $dif->m == 0) {
        $html .= $dif->h . ' hour(s) ';
    }
    if ($dif->h == 1 && $dif->m == 0) {
        $html .= $dif->h . ' hour ';
    }
    if ($dif->i > 1 && $dif->d == 0) {
        $html .= $dif->i . ' minute(s) ';
    }
    if ($dif->i == 1 && $dif->d == 0) {
        $html .= $dif->i . ' minute ';
    }
    if ($dif->s > 0 && $dif->h == 0) {
        $html .= $dif->s . ' second(s) ';
    }

    return $html;
}

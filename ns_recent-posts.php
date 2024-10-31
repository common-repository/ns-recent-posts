<?php
/*
Plugin Name: NS Recent Posts
Plugin URI: http://seleckis.lv/projects/wp-plugins/ns_recent-posts
Description: Returns an array of the most recent posts.
Version: 1.0
Author: Nikita Seleckis
Author URI: http://seleckis.lv/
*/

function unixtime_to_getdate($unixtime){
	$date = str_replace("-", "", $unixtime);
	$date = str_replace(":", "", $date);
	$date = str_replace(" ", ", ", $date);
	$date = getdate(strtotime($date));
	return $date;
}

class ns_re_posts {
	public $post_title, $bookmark, $post_content, $permalink, $comment_count, $day, $month, $year, $post_tags;
}

function ns_recent_posts($no_posts = 10, $first = 0, $excerpt_len = 200, $db_unicode = false, $tags_divider = " | ") {
    global $wpdb;
	$now = gmdate("Y-m-d H:i:s",time());
    $request = "SELECT ID, post_title, post_date, post_content, comment_count FROM $wpdb->posts WHERE post_status = 'publish' ";
	$request .= "AND post_date_gmt < '$now' AND post_type = 'post' ORDER BY post_date DESC LIMIT $first, $no_posts";
    $posts = $wpdb->get_results($request);
    if($posts) {
		foreach ($posts as $post) {
			$return_posts = new ns_re_posts();
			$return_posts->post_title = stripslashes($post->post_title);
			$return_posts->bookmark = htmlspecialchars($post_title, ENT_COMPAT);
			$post_content = str_replace("&nbsp;", "", strip_tags(stripslashes($post->post_content)));
			if($db_unicode){
				$post_content = mb_substr($post_content, 0, $excerpt_len+1);
				if(mb_strlen($post_content)>$excerpt_len)
					$post_content = mb_substr($post_content, 0 ,mb_strrpos($post_content, " ")) . "...";
			}
			else{
				$post_content = substr($post_content, 0, $excerpt_len+1);
				if(strlen($post_content)>$excerpt_len)
					$post_content = substr($post_content, 0 ,strrpos($post_content, " ")) . "...";
			}
			$return_posts->post_content = $post_content;
			$return_posts->permalink = get_permalink($post->ID);
			$return_posts->comment_count = $post->comment_count;
			$date = unixtime_to_getdate($post->post_date);
			if (strlen($date["mday"])<2) $return_posts->day = "0".$date["mday"]; else $return_posts->day = $date["mday"];
			if (strlen($date["mon"])<2) $return_posts->month = "0".$date["mon"]; else $return_posts->month = $date["mon"];
			$return_posts->year = substr($date["year"],2,2);
			$recent_post_tags = '';
			$request_cat_id = "SELECT category_id FROM $wpdb->post2cat WHERE post_id = ".$post->ID.";";
			$k=0;
			$result_cat_id = mysql_query($request_cat_id) or die(mysql_error());
			while ($row = mysql_fetch_object($result_cat_id)) {
				$request_cat_name = "SELECT cat_name, category_nicename FROM $wpdb->categories WHERE cat_ID = ".$row->category_id.";";
				$result_cat_name = mysql_query($request_cat_name) or die(mysql_error());
				$row_cat = mysql_fetch_object($result_cat_name);
				$cat_name = $row_cat->cat_name;
				$cat_permalink = $row_cat->category_nicename;
				if($k>0) $recent_post_tags .= $tags_divider;
				else $k++;
				$recent_post_tags .= '<a href="'.$cat_permalink.'">'.$cat_name.'</a>';
			}
			$return_posts->post_tags = $recent_post_tags;
			$output[] = $return_posts;
		}
	} else {
		$output[] = "None found";
	}
    return $output;
}
?>
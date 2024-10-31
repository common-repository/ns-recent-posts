=== NS Recent Posts ===
Contributors: nikitaseleckis
Tags: post, recent, widget, list
Requires at least: 2.0
Tested up to: 2.2.2
Stable tag: trunk

Returns an array of the most recent posts. Simple and powerfull.


== Description ==

Returns an array of the most recent posts. Simple and powerfull.


== Installation ==

Standard WordPress quick and easy installation.

1. Download and unzip ns_recent-posts.zip
1. Upload to the plugins folder. 
1. Log in WordPress and activate the plugin. 


== Usage ==

Function `ns_recent_posts(10, 0, 200, false, ", ")` returns an array of objects of recent posts.

Params for function:

* *$no_posts* - number of posts to display. Default **10**.
* *$first* - Number of the first post. Used when is needed to display, for example, posts from 10 to 20, but not from the first post. Default **0**.
* *$excerpt_len* - Number of symbols in excerpt. Default **200**.
* *$db_unicode* - boolean value variable. Determines if your wordpress database is in unicode format (UTF-8). Default FALSE. **FALSE** - non-Unicode, uses standart string functions. **TRUE** - Unicode(UTF-8), uses mbstring functions.
* *$tags_divider* - string value of categories list divider. Default **", "**.

**Use function `ns_recent_posts()` in a loop.**

Objects returned by array has properties, that is used in construction: **$object->property**, where **"$object"** - your variable used in a loop, but **"property"** can be:

* *post_title* - Title of a post
* *permalink* - Link to post
* *bookmark* - Title of a post of "title" attribute
* *day* - day
* *month* - month
* *year* - year
* *post_content* - post preview (excerpt)
* *post_tags* - list of categories (tags)
* *comment_count* - number of categories

= Example =

`<?php
foreach (ns_recent_posts(10, 0, 200, false, ", ")  as $re_post){
echo <<<HTML
<div class="recent-post">
      <h3><a href="{$re_post->permalink}" rel="bookmark" title="{$re_post->bookmark}>
            {$re_post->post_title}
      </a></h3>
      <span class="post-date">{$re_post->day}.{$re_post->month}.{$re_post->year}</span>
      <div class="entry">
            <p>{$re_post->post_content}</p>
            <span class="tags"><strong>Tags: </strong>{$re_post->post_tags}</span>
            <span class="read-comments">
                  <a href="{$re_post->permalink}#respond">Comments ({$re_post->comment_count}) &raquo;</a>
            </span>
      </div>
</div>
HTML;
}
?>`

= Note =

* Place this code to any place in any file of your template.
* To show value of property of object in **"echo"**, add braces in construction: **{$object->property}**
* Plugin also erases all tags, and leaves simple text in excerpt.
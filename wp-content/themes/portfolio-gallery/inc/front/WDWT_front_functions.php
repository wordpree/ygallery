<?php


class WDWT_frontend_functions
{


  public static function the_title_max_charlength($charlength, $title = false)
  {

    if ($title) {
    } else {
      $title = the_title($before = '', $after = '', FALSE);
    }

    $title_length = mb_strlen($title);
    if ($title_length <= $charlength) {
      echo $title;
    } else {
      $limited_title = mb_substr($title, 0, $charlength);
      echo $limited_title . "...";
    }

  }

  public static function the_excerpt_max_charlength($charlength, $content = false)
  {

    /*if content is empty ,its ok, empty post*/

    if ($content || $content === '') {
      $excerpt = $content;
    } else {
      $excerpt = get_the_excerpt();
    }
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $charlength++;

    if (mb_strlen($excerpt) > $charlength) {
      $subex = mb_substr($excerpt, 0, $charlength - 5);
      $exwords = explode(' ', $subex);
      $excut = -(mb_strlen($exwords[count($exwords) - 1]));
      if ($excut < 0) {
        echo mb_substr($subex, 0, $excut) . '...';
      } else {
        echo $subex . '...';
      }
    } else {
      echo str_replace('[&hellip;]', '', $excerpt);
    }
  }

  public static function remove_last_comma($string = '')
  {

    if (substr($string, -1) == ',')
      return substr($string, 0, -1);
    else
      return $string;

  }

  public static function posted_on()
  {
    printf('<span class="sep date"></span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
      esc_url(get_permalink()),
      esc_attr(get_the_time()),
      esc_attr(get_the_date('c')),
      esc_html(get_the_date())
    );
  }

  public static function posted_on_single()
  {
    printf('<span class="sep date"></span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="by-author"> <span class="sep author"></span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>',
      esc_url(get_permalink()),
      esc_attr(get_the_time()),
      esc_attr(get_the_date('c')),
      esc_html(get_the_date()),
      esc_url(get_author_posts_url(get_the_author_meta('ID'))),
      esc_attr(sprintf(__('View all posts by %s', "portfolio-gallery"), get_the_author())),
      get_the_author()
    );
  }

  /**
   *
   *  Generate image for post thumbnail
   *
   */


  public static function display_thumbnail($width, $height)
  {

    if (has_post_thumbnail()) {
      the_post_thumbnail(array($width, $height));
    } elseif (self::is_empty_thumb()) {
      return self::first_image($width, $height); /*first image or no image placeholder*/
    } else {
      return '';
    }
  }

  public static function is_empty_thumb()
  {
    global $post;

    $thumb = get_post_custom_values("Image");
    return empty($thumb);
  }

  /**
   *  Get first image of post for thumbnail
   */

  public static function catch_that_image()
  {
    global $post, $posts;
    $first_img = array('src' => '', 'image_catched' => true);
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if (isset($matches [1] [0])) {
      $first_img['src'] = $matches [1] [0];
    }
    if (empty($first_img['src'])) {
      $first_img['src'] = WDWT_IMG . 'default.png';
      $first_img['image_catched'] = false;
    }
    return $first_img;
  }

  public static function first_image($width, $height, $url_or_img = 0)
  {
    $image_parametr = self::catch_that_image();
    $thumb = $image_parametr['src'];
    $class = '';
    if (!$image_parametr['image_catched'])
      $class = 'class="no_image"';
    if ($thumb) {
      $str = "<img src=\"";
      $str .= $thumb;
      $str .= '"';
      $str .= 'alt="' . get_the_title() . '" width="' . $width . '" height="' . $height . '" ' . $class . ' border="0" style="width:' . $width . '; height:' . $height . ';" data-s="" />';
      return $str;
    }
  }



  public static function thumbnail($width, $height)
  {
    if (has_post_thumbnail()) {
      the_post_thumbnail(array($width, $height));
    } elseif (self::is_empty_thumb()) {
      return '';
    }
  }

  /**
   * returns image tag with image
   * for containers of fixed size
   * image fitted, cropped, centered
   */

  public static function fixed_thumbnail($width, $height, $grab_image = true)
  {
    $tumb_id = get_post_thumbnail_id(get_the_ID());
    $thumb_url = wp_get_attachment_image_src($tumb_id, array($width, $height));
    $thumb_url = $thumb_url[0];
    if ($grab_image) {
      if (!$thumb_url) {
        $thumb_url = self::catch_that_image();
        $thumb_url = $thumb_url['src'];
      }
    }
    if ($thumb_url) {
      return '<img class="wp-post-image" src="' . $thumb_url . '" alt="' . esc_attr(get_the_title()) . '" />';
    } else {
      return '';
    }
  }

  /**
   * returns image tag with image
   * fits width of container
   * height auto
   */

  public static function auto_thumbnail($grab_image = true)
  {
    $scrset = '';
    $tumb_id = get_post_thumbnail_id(get_the_ID());
    $thumb_url = wp_get_attachment_image_src($tumb_id, 'full');
    $thumb_url = $thumb_url[0];
    if ($thumb_url) {
      if (function_exists('wp_get_attachment_image_srcset')) {
        $scrset = 'srcset="' . esc_attr(wp_get_attachment_image_srcset($tumb_id, 'full')) . '"';
      }
    } else if ($grab_image) {
      $thumb_url = self::catch_that_image();
      $thumb_url = $thumb_url['src'];
    }

    if ($thumb_url) {
      return '<img class="wp-post-image" src="' . $thumb_url . '" ' . $scrset . ' alt="' . esc_attr(get_the_title()) . '" style="width:100%;" />';
    } else {
      return '';
    }
  }

  /**
   * @return url of first image in the post content, or empty string if has no image
   */

  public static function post_image_url()
  {

    $thumb_url = self::catch_that_image();
    if (isset($thumb_url['image_catched'])) {
      if (!$thumb_url['image_catched']) {
        $thumb_url = '';
      } else {
        $thumb_url = $thumb_url['src'];
      }
    }

    return $thumb_url;

  }

  /**
   * get postthumb url @param size =full or medium
   * use inside loop
   */
  public static function get_post_thumb($size = 'large')
  {
    $tumb_id = get_post_thumbnail_id(get_the_ID());
    $thumb_url = wp_get_attachment_image_src($tumb_id, $size);

    if ($thumb_url) {
      $thumb_url = $thumb_url[0];
    } else {
      $thumb_url = self::catch_that_image();
    }
    if (is_array($thumb_url) && isset($thumb_url['image_catched'])) {
      if (!$thumb_url['image_catched']) {
        $thumb_url = '';
      } else {
        $thumb_url = $thumb_url['src'];
      }
    }
    return $thumb_url;
  }


  public static function post_nav()
  {
    global $post;
    $previous = (is_attachment()) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
    $next = get_adjacent_post(false, '', false);

    if (!$next && !$previous)
      return;
    ?>
    <nav class="page-navigation">
      <?php previous_post_link('%link', '<span class="meta-nav">&larr;</span> %title'); ?>
      <?php next_post_link('%link', '%title <span class="meta-nav">&rarr;</span>'); ?>
    </nav>
    <?php
  }

  public static function posts_nav($wp_query)
  {

    ?>
    <nav class="page-navigation">
      <div class="nav-back" style="float:left;">
        <?php next_posts_link(_x('Older entries', '', "portfolio-gallery"), $wp_query->max_num_pages); ?>

      </div>
      <div class="nav-forward" style="float:right;">

        <?php previous_posts_link(_x('Newer entries', '', "portfolio-gallery")); ?>
      </div>
      <div class="clear"></div>
    </nav>
    <?php
  }


  public static function excerpt_more($more)
  {
    return '...';
  }

  public static function remove_more_jump_link($link)
  {
    $offset = strpos($link, '#more-');
    if ($offset) {
      $end = strpos($link, '"', $offset);
    }
    if ($end) {
      $link = substr_replace($link, '', $offset, $end - $offset);
    }
    return $link;
  }


  public static function do_nothing($param = NULL)
  {
    return $param;
  }


}







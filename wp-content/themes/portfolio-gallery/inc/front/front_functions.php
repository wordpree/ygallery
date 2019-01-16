<?php

/* include  fornt end framework class */
require_once('WDWT_front_functions.php');

class Portfolio_gallery_frontend_functions extends WDWT_frontend_functions{


  public static function home_featured_post(){
    global $wdwt_front;
    $home_middle_description_post_enable = $wdwt_front->get_param('home_middle_description_post_enable');

    if(!$home_middle_description_post_enable){
      return;
    }

    $choose_post_page = $wdwt_front->get_param('home_middle_description_post_page_choose', array(), 'post');
    $home_middle_description_pos = $wdwt_front->get_param('home_middle_description_pos', array(), '2');

    if($choose_post_page == 'page'){
      $home_middle_description_post = $wdwt_front->get_param('home_middle_description_page');
    }
    else{
      $home_middle_description_post = $wdwt_front->get_param('home_middle_description_post');
    }
    $img_maxwidth = $wdwt_front->get_param('home_middle_image_maxwidth', array(), 600);

    $grab_image = $wdwt_front->get_param('grab_image');
    $featured_id=isset($home_middle_description_post[0]) ? $home_middle_description_post[0] : null;
    $args = array();
    $args['post_type'] = $choose_post_page == 'page' ? 'page' : array('post', 'product');
    $args['ignore_sticky_posts '] = true;


    /*WPML translation*/
    $featured_id = apply_filters( 'wpml_object_id', $featured_id, 'post' );

    if(isset($featured_id)){
      $args['p']= $featured_id;
    }
    else{
      $args['posts_per_page']=1;
    }

    $featured_query = new WP_Query( $args);


    if ($featured_query->have_posts()):
      while($featured_query->have_posts()) :

        $featured_query->the_post();
        $has_thumb = false;
        if(has_post_thumbnail() || (Portfolio_gallery_frontend_functions::post_image_url() && $grab_image)){
          $has_thumb = true;
        }
        ?>

        <style>
          #right_middle #featured_post_img img{
            max-width: <?php echo $img_maxwidth; ?>px;
          }
          #right_middle #middle {
            min-width: calc(100% - <?php echo $img_maxwidth; ?>px);
          <?php if(!$has_thumb){ ?>
            width:100%;
          <?php } ?>
          }
        </style>
        <div class="clear"></div>
        <div id="right_middle" data-pos="<?php echo $home_middle_description_pos; ?>" <?php post_class(); ?> >
          <?php
          if($has_thumb){ ?>
            <div id="featured_post_img">
              <?php echo Portfolio_gallery_frontend_functions::auto_thumbnail($grab_image); ?>
            </div>
          <?php } ?>
          <div id="middle">
            <a href="<?php echo get_permalink(get_the_ID()); ?>">
              <h2 class="entry-title">
                <?php echo esc_html(the_title()); ?>
              </h2>
            </a>
          <span class="entry-summary">
          <?php
          self::the_excerpt_max_charlength(200, get_the_excerpt());
          ?>
          </span>
            <div class="clear"></div>
          </div>
          <?php  ?>
        </div>
        <?php
      endwhile;
    endif;
    wp_reset_postdata();
  }


  public static function frontpage() {
    global $wdwt_front,
           $wp_query,
           $paged;

    $content_posts_enable = $wdwt_front->get_param('content_posts_enable');

    $choose_posts_pages = $wdwt_front->get_param('content_posts_pages_choose', array(), 'posts');

    $content_post_order = $wdwt_front->get_param('content_post_order', array(), array('DESC'));
    $content_post_orderby = $wdwt_front->get_param('content_post_orderby', array(), array('date'));
    $content_post_order = $content_post_order[0];
    $content_post_orderby = $content_post_orderby[0];

    $content_posts_noimage = esc_url($wdwt_front->get_param('content_posts_noimage'));

    $lbox_width = $wdwt_front->get_param('lbox_image_width');
    $lbox_height = $wdwt_front->get_param('lbox_image_height');

    $cat_checked=0;
    $post_count=0;
    $grid_thumbs_size = $wdwt_front->get_param('grid_thumbs_size', array(), array('large'));
    $grid_thumbs_size = $grid_thumbs_size[0];

    $printed_featured=false;
    $n_of_home_post=get_option( 'posts_per_page', 2);
    if($n_of_home_post == 0){
      $n_of_home_post = 1;
    }
    $content_posts_maxnumber = $wdwt_front->get_param('content_posts_maxnumber', array(), 200);
    $posts_loaded_before = ($paged == 0 ? 0 : ($paged-1)) * $n_of_home_post;
    $maxnumber_reached = ($posts_loaded_before >= $content_posts_maxnumber) ? true : false;
    $content_posts_hover_btn = $wdwt_front->get_param('content_posts_hover_btn', array(), array('lbox_and_link'));

    $content_posts_layout = $wdwt_front->get_param('content_posts_layout', array(), 'thumb');
    $content_posts_masonry_title_pos = $wdwt_front->get_param('content_posts_masonry_title_pos', array(), 'on_hover');
    $content_posts_masonry_hover_btn = $wdwt_front->get_param('content_posts_masonry_hover_btn', array(), 'lbox_and_link');
    $content_post_effect = $wdwt_front->get_param('content_post_effect', array(), 'slide');

    ?>
    <script>
      <?php if($content_posts_layout == "masonry") { ?>
      function wdwt_masonry() {
        var is_scrollbar = jQuery('body').get(0).scrollHeight > jQuery(window).height();

        var image_width = jQuery(".image_list_item.masonry_list_item").eq(0).outerWidth(true) ;
        
		    image_width = (!image_width) ? 1 : image_width;
		
        var masonry_thumbnails_div_width = jQuery("#image_list_top").outerWidth();
        var col_count = Math.round(masonry_thumbnails_div_width / image_width);
        if (!col_count) {
          col_count = 1;
        }
        var top = new Array();
        var left = new Array();
        for (var i = 0; i < col_count; i++) {
          top[i] = 0;
          left[i] = i * image_width;
        }

        var min_top, index_min_top;
        jQuery(".image_list_item.masonry_list_item").each(function() {
          min_top = Math.min.apply(Math, top);
          index_min_top = jQuery.inArray(min_top, top);
          jQuery(this).css({left: left[index_min_top], top: top[index_min_top]});
          top[index_min_top] += jQuery(this).outerHeight(true);
        });
        jQuery("#image_list_top").height(Math.max.apply(Math, top) + 150);
        /*if scrollbar appears, arrange again*/
        if(!is_scrollbar){

          var is_scrollbar_new = jQuery('body').get(0).scrollHeight > jQuery(window).height();
          if(is_scrollbar_new){
            wdwt_masonry();
          }
        }
      }
      jQuery(".masonry_list_item .masonry_item_img img").error(function() {
        jQuery(this).height(100);
        jQuery(this).width(100);
      });
      jQuery(window).load(function() {
        wdwt_masonry();
      });
      jQuery(window).resize(function() {
        wdwt_masonry();
      });
      <?php } elseif($content_posts_layout == "thumb" && $content_post_effect == "slide") { ?>
      // Hover
      jQuery(function() {
        jQuery('.da-thumbs > div:not(.da-empty)').hoverdir();
      });
      <?php } ?>
    </script>
    <?php
    self::home_featured_post();
    $printed_featured = true;

    if($maxnumber_reached) :
      ?>
      <div id="image_list_top" class="image_list_top portfolio_list da-thumbs">
      </div>
      <?php
      return;
    endif;

    ?>


    <div id="image_list_top" class="image_list_top portfolio_list da-thumbs <?php echo $content_posts_layout; ?> <?php echo $content_post_effect; ?>">

      <?php

      if($content_posts_enable){
        /*show specific posts/pages*/
        if($choose_posts_pages == 'pages'){
          $content_pages_list = $wdwt_front->get_param('content_pages_list', array(), array('')) ;
          $content_pages_list2 = array();
          /*WPML*/
          foreach ($content_pages_list as $page_id) {
            $page_trans_id = apply_filters( 'wpml_object_id', $page_id, 'page' );
            if(!is_null($page_trans_id)){
              array_push($content_pages_list2, $page_trans_id);
            }

          }
          $args = array(
            'posts_per_page' => $n_of_home_post,
            'post_type' => 'page',
            'post__in' => $content_pages_list2,
            'paged'=> $paged,
            'order'=>$content_post_order,
            'orderby'=>$content_post_orderby,
            //'post__in'  => get_option( 'sticky_posts' ),
            //'ignore_sticky_posts' => 1
          );
        }
        else{
          $content_post_categories = $wdwt_front->get_param('content_post_categories', array(), array());
          $content_post_categories = (isset($content_post_categories[0]) && empty($content_post_categories[0]) ) ? array() : $content_post_categories;

          $args = array(
            'posts_per_page' => $n_of_home_post,
            'paged'=> $paged,
            'order'=>$content_post_order,
            'orderby'=>$content_post_orderby,
            'tax_query' => array(
              'relation' => 'OR',
              array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $content_post_categories,
                'operator' => empty($content_post_categories) ? 'EXISTS': 'IN',
              ),
              array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $content_post_categories,
                'operator' => empty($content_post_categories) ? 'EXISTS': 'IN',
              ),
            ),
            //'post__in'  => get_option( 'sticky_posts' ),
            //'ignore_sticky_posts' => 1
          );
        }
      }
      else{
        /*show all posts/pages*/
        if($choose_posts_pages == 'pages'){
          $args = array(
            'posts_per_page' => $n_of_home_post,
            'post_type' => 'page',
            'paged'=> $paged,
            'order'=>$content_post_order,
            'orderby'=>$content_post_orderby,
            //'post__in'  => get_option( 'sticky_posts' ),
            //'ignore_sticky_posts' => 1
          );

        }
        else{
          $args = array(
            'posts_per_page' => $n_of_home_post,
            'paged'=> $paged,
            'order'=>$content_post_order,
            'orderby'=>$content_post_orderby,
          );
        }
      }
      $wp_query = new WP_Query($args);

      $id = 0;
      if(have_posts()):
        while ($wp_query->have_posts()):

          $wp_query->the_post();

          $article_class = "da-animate da-slideFromRight";
          $post_count++;

          $thumb_url = self::get_post_thumb($grid_thumbs_size);
          $full_img_url = self::get_post_thumb("full");

          if(!$thumb_url && !$content_posts_noimage){
            $article_class = "da-empty";
          }
          if($content_posts_layout == "thumb") :
            ?>

            <div class="image_list_item <?php echo ($article_class == "da-empty" ? "da-empty" : ""); echo implode(" ", get_post_class()); ?>" style="background-image:url(<?php echo $thumb_url ? esc_url($thumb_url) : ($content_posts_noimage ? esc_url($content_posts_noimage) : ""); ?>);">
              <?php //echo Portfolio_gallery_frontend_functions::fixed_thumbnail(370,310); ?>

              <article class="<?php echo $article_class; ?>" <?php echo ($content_post_effect == "slide")? 'style="display: block;"' : '';  ?>>
                <?php
                if($content_posts_hover_btn[0] == 'link_only'){
                  ?>
                  <h4 class="entry-title">
                    <a href="<?php echo get_permalink() ?>" rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-title">
                      <?php the_title(); ?>
                    </a>
                  </h4>
                  <?php
                }
                if($content_posts_hover_btn[0] == 'lbox_and_link' || $content_posts_hover_btn[0] == 'lbox_only'){
                ?>
                <h4 rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-title" class="entry-title">
                  <?php the_title(); ?>
                </h4>
                <?php
                }

                ?></h4>
                <center class="home_description_hover entry-summary" rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-desc"><?php self::the_excerpt_max_charlength(500); ?></center>
                <?php if($content_posts_hover_btn[0] == 'lbox_and_link'){  ?>
                  <span class="link_post">
                <a href="<?php echo get_permalink() ?>"><i class="fa fa-link"></i>
                </a>
              </span>
                <?php } ?>
                <?php
                if($full_img_url &&  ($content_posts_hover_btn[0] == 'lbox_and_link' || $content_posts_hover_btn[0] == 'lbox_only')){ ?>
                  <span class="zoom">
                <a href="<?php echo $full_img_url; ?>" class=" " onclick="wdwt_lbox.init(this, 'wdwt-lightbox', <?php echo intval($lbox_width);?> , <?php echo intval($lbox_height);?>); return false;" rel="wdwt-lightbox" id="content-post-<?php echo $id; ?>-<?php echo $paged; ?>"><i class="fa fa-search-plus"></i>
                </a>
              </span>
                  <?php
                }
                ?>
              </article>
            </div>
            <?php
          else :
            ?>
            <div class="image_list_item masonry_list_item <?php echo ($article_class == "da-empty" ? "da-empty" : ""); ?>  <?php echo $content_posts_masonry_title_pos[0]; ?>">
              <?php if($content_posts_masonry_title_pos[0] != "below"): ?>
                <div class="slide-in-left masonry_item_content">
                  <?php
                  if($content_posts_masonry_hover_btn[0] == 'link_only' || $content_posts_masonry_hover_btn[0] == 'lbox_and_link'){ ?>
                    <h4 class="entry-title"><a href="<?php echo get_permalink() ?>" rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-title"><?php the_title(); ?></a></h4>
                    <?php
                  }
                  if($content_posts_masonry_hover_btn[0] == 'lbox_only'){ ?>
                    <h4 rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-title" class="entry-title"> <?php the_title(); ?> </h4>
                  <?php } ?>

                  <center class="home_description_hover entry-summary" rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-desc"><?php  self::the_excerpt_max_charlength(500); ?></center>
                  <?php if($full_img_url &&  ($content_posts_masonry_hover_btn[0] == 'lbox_and_link' || $content_posts_masonry_hover_btn[0] == 'lbox_only')){ ?>
                    <span class="zoom">
                        <a href="<?php echo $full_img_url; ?>" class=" " onclick="wdwt_lbox.init(this, 'wdwt-lightbox', <?php echo intval($lbox_width);?> , <?php echo intval($lbox_height);?>); return false;" rel="wdwt-lightbox" id="content-post-<?php echo $id; ?>-<?php echo $paged; ?>"><i class="fa fa-search-plus"></i>
                        </a>
                      </span>
                  <?php  }  ?>
                </div>
              <?php endif; ?>
              <?php if($thumb_url || $content_posts_noimage): ?>
                <div class="masonry_item_img">
                  <img src="<?php echo $thumb_url ? esc_url($thumb_url) : ($content_posts_noimage ? esc_url($content_posts_noimage) : ""); ?>">

                </div>
              <?php endif; ?>
              <?php if($content_posts_masonry_title_pos[0] == "below"): ?>
                <div class="masonry_item_content">
                  <?php
                  if($content_posts_masonry_hover_btn[0] == 'link_only' || $content_posts_masonry_hover_btn[0] == 'lbox_and_link'){ ?>
                    <h4><a href="<?php echo get_permalink() ?>" rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-title" class="entry-title"><?php the_title(); ?></a></h4>
                    <?php
                  }
                  if($content_posts_masonry_hover_btn[0] == 'lbox_only'){ ?>
                    <h4 rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-title" class="entry-title"> <?php the_title(); ?> </h4>
                  <?php } ?>
                  <center class="home_description_hover entry-summary" rel="content-post-<?php echo $id; ?>-<?php echo $paged; ?>-desc"><?php  self::the_excerpt_max_charlength(500); ?></center>
                  <?php if($full_img_url &&  ($content_posts_masonry_hover_btn[0] == 'lbox_and_link' || $content_posts_masonry_hover_btn[0] == 'lbox_only')){ ?>
                    <span class="zoom">
                        <a href="<?php echo $full_img_url; ?>" class=" " onclick="wdwt_lbox.init(this, 'wdwt-lightbox', <?php echo intval($lbox_width);?> , <?php echo intval($lbox_height);?>); return false;" rel="wdwt-lightbox" id="content-post-<?php echo $id; ?>-<?php echo $paged; ?>"><i class="fa fa-search-plus"></i>
                        </a>
                      </span>
                  <?php  }  ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
          endif;
          $id++;
        endwhile;
      endif; /*the loop*/


      /*if($content_posts_enable){*/ ?>
      <div class="page-navigation">
        <?php posts_nav_link(); ?>
      </div>
      <?php /* } */?>

    </div>

    <?php

    wp_reset_query();

  }


  public static function homepage() {

    global $wdwt_front,
           $wp_query,
           $paged;
    $date_enable =  $wdwt_front->get_param('date_enable');
    $grab_image = $wdwt_front->get_param('grab_image');
    $blog_style = $wdwt_front->blog_style();


    ?>
    <?php

    if(have_posts()) :
      while (have_posts()) :
        the_post();

        ?>
        <div class="blog-post home-post <?php echo implode(' ',get_post_class()); ?>">
          <a class="title_href" href="<?php echo get_permalink() ?>">
            <h2 class="blog_post_title entry-title"><?php
              self::the_title_max_charlength(40);
              /*the_title();*/ ?></h2>
          </a>
          <?php


          if(has_post_thumbnail() || (Portfolio_gallery_frontend_functions::post_image_url() && $blog_style && $grab_image)){
            ?>
            <div class="blog_img_container img_container unfixed size250">
              <?php echo Portfolio_gallery_frontend_functions::auto_thumbnail($grab_image); ?>
            </div>

            <?php
          }

          if($blog_style) {
            ?>
            <div class="entry-summary"><?php the_excerpt(); ?></div>
            <?php
          }
          else {
            ?>
            <div class="entry-content"><?php the_content(__('More', "portfolio-gallery")); ?></div>
            <?php

          }
          if ($date_enable) { ?>
            <div class="entry-meta">
              <?php self::posted_on_single(); ?>
            </div>
            <?php self::entry_meta();
          }
          ?>

          <div class="clear"></div>

        </div>
        <?php
      endwhile;
      if( $wp_query->max_num_pages > 2 ){ ?>
        <div class="page-navigation">
          <?php posts_nav_link(); ?>
        </div>
        <?php
      }
    endif;

    ?>
    <div class="clear"></div>
    <?php
    $wdwt_front->bottom_advertisment();
    wp_reset_query();

  }

  public static function entry_meta() {
    $categories_list = get_the_category_list(', ' );
    echo '<div class="entry-meta-cat">';
    if ( $categories_list ) {
      echo '<span class="categories-links"><span class="sep category"></span> ' . $categories_list . '</span>';
    }
    $tag_list = get_the_tag_list( '', ' , ' );
    if ( $tag_list ) {
      echo '<span class="tags-links"><span class="sep tag"></span>' . $tag_list . '</span>';
    }
    echo '</div>';
  }


public static function wdwt_wrapper_start(){ ?>
  <div class="right_container">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
      <aside id="sidebar1" >
        <div class="sidebar-container">
          <?php  dynamic_sidebar( 'sidebar-1' );  ?>
          <div class="clear"></div>
        </div>
      </aside>
    <?php } ?>
    <div id="content">
      <?php
      }

      public static function wdwt_wrapper_end(){
      global $wdwt_front;
      ?>
      <div class="clear"></div>
      <?php $wdwt_front->bottom_advertisment();
      wp_reset_query();
      if(comments_open())
      {  ?>
        <div class="comments-template">
          <?php comments_template();  ?>
        </div>
      <?php }  ?>
    </div>
    <?php if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
      <aside id="sidebar2">
        <div class="sidebar-container">
          <?php  dynamic_sidebar( 'sidebar-2' );  ?>
          <div class="clear"></div>
        </div>
      </aside>
    <?php } ?>
    <div class="clear"></div>
    <?php $wdwt_front->footer_text(); ?>
  </div>




  <?php
}




}




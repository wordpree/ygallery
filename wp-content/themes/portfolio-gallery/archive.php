<?php
/*The template for displaying Archive pages*/
global $wdwt_front;
get_header();

$grab_image = $wdwt_front->get_param('grab_image');
$blog_style = $wdwt_front->blog_style();
?>
<div class="right_container">
  <?php
  if (is_active_sidebar('sidebar-1')) { ?>
    <aside id="sidebar1">
      <div class="sidebar-container">
        <?php
        dynamic_sidebar('sidebar-1');
        ?>
        <div class="clear"></div>
      </div>
    </aside>
  <?php } ?>

  <div id="content" class="blog archive-page">

    <?php if (have_posts()) : ?>
      <?php $post = $posts[0]; ?>

      <?php if (is_category()) { ?>
        <h1
          class="styledHeading"><?php _e('Archive For The ', "portfolio-gallery"); ?>&ldquo;<?php single_cat_title(); ?>&rdquo; <?php _e('Category', "portfolio-gallery"); ?></h1>
      <?php } elseif (is_tag()) { ?>
        <h1
          class="styledHeading"><?php _e('Posts Tagged ', "portfolio-gallery"); ?>&ldquo;<?php single_tag_title(); ?>&rdquo;</h1>
      <?php } elseif (is_day()) { ?>
        <h1
          class="styledHeading"><?php _e('Archive For ', "portfolio-gallery"); ?><?php the_time(get_option('date_format')); ?></h1>
      <?php } elseif (is_month()) { ?>
        <h1
          class="styledHeading"><?php _e('Archive For ', "portfolio-gallery"); ?><?php the_time(get_option('date_format')); ?></h1>
      <?php } elseif (is_year()) { ?>
        <h1
          class="styledHeading"><?php _e('Archive For ', "portfolio-gallery"); ?><?php the_time(get_option('date_format')); ?></h1>
      <?php } elseif (is_author()) { ?>
        <h1 class="styledHeading"><?php _e('Author Archive', "portfolio-gallery"); ?></h1>
      <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
        <h1 class="styledHeading"><?php _e('Blog Archives', "portfolio-gallery"); ?></h1>
      <?php } ?>

      <?php while (have_posts()) : the_post(); ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="post">
            <h2 class="archive-header blog_post_title"><a href="<?php the_permalink(); ?>" rel="bookmark"
                                                          title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
          </div>
          <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
            <?php
            if (has_post_thumbnail() || (Portfolio_gallery_frontend_functions::post_image_url() && $blog_style && $grab_image)) {
              ?>
              <div class="blog_img_container img_container unfixed size250">
                <?php echo Portfolio_gallery_frontend_functions::auto_thumbnail($grab_image); ?>
              </div>
              <?php
            } ?>
          </a>

          <?php
          if ($wdwt_front->blog_style()) {
            the_excerpt();
          } else {
            the_content();
          }
          if ($wdwt_front->get_param('date_enable')) { ?>
            <div class="entry-meta">
              <?php Portfolio_gallery_frontend_functions::posted_on_single(); ?>
            </div>
            <?php Portfolio_gallery_frontend_functions::entry_meta();
          } ?>
        </div>

      <?php endwhile; ?>
      <div class="page-navigation">
        <?php posts_nav_link(); ?>
      </div>
    <?php else : ?>

      <h2 class="archive-header"><?php _e('Not Found', "portfolio-gallery"); ?></h2>
      <p><?php _e('There are not posts belonging to this category or tag. Try searching below:', "portfolio-gallery"); ?></p>
      <div id="search-block-category"><?php get_search_form(); ?></div>

    <?php endif; ?>

    <?php
    $wdwt_front->bottom_advertisment();

    wp_reset_query(); ?>
  </div>
  <?php
  if (is_active_sidebar('sidebar-2')) { ?>
    <aside id="sidebar2">
      <div class="sidebar-container">
        <?php dynamic_sidebar('sidebar-2'); ?>
        <div class="clear"></div>
      </div>
    </aside>
    <?php
  } ?>
  <div class="clear"></div>

  <?php if (is_active_sidebar('footer-widget-area')) { ?>
    <aside id="footer-widget-area">
      <div class="sidebar-container">
        <?php dynamic_sidebar('footer-widget-area'); ?>
        <div class="clear"></div>
      </div>
    </aside>
  <?php } ?>
  <div class="clear"></div>
  <?php $wdwt_front->footer_text(); ?>
</div>
<?php get_footer(); ?>

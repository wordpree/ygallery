<?php
get_header();
global $wdwt_front;
$show_slider_wd = $wdwt_front->get_param('show_slider_wd', array(), false);
$slider_wd_id = $wdwt_front->get_param('slider_wd_id');

if (is_array($slider_wd_id) && isset($slider_wd_id[0])) {
  $slider_wd_id = $slider_wd_id[0];
}
else{
  $slider_wd_id = null;
}

?>
  <div class="right_container">
    <?php if (is_active_sidebar('sidebar-1')) { ?>
      <aside id="sidebar1">
        <div class="sidebar-container">
          <?php dynamic_sidebar('sidebar-1'); ?>
          <div class="clear"></div>
        </div>
      </aside>
    <?php } ?>
    <div id="content">
      <?php
      if ('posts' == get_option('show_on_front') && is_plugin_active('slider-wd/slider-wd.php') && $show_slider_wd && function_exists("wd_slider") && isset($slider_wd_id)) {
        wd_slider($slider_wd_id);
      }
      if ('posts' == get_option('show_on_front')) {
        Portfolio_gallery_frontend_functions::frontpage();
        ?>
        <div class="clear"></div>
        <?php
      } elseif ('page' == get_option('show_on_front')) {
        Portfolio_gallery_frontend_functions::homepage();


      }
      ?>
    </div>

    <?php if (is_active_sidebar('sidebar-2')) { ?>
      <aside id="sidebar2">
        <div class="sidebar-container">
          <?php dynamic_sidebar('sidebar-2'); ?>
          <div class="clear"></div>
        </div>
      </aside>
    <?php } ?>

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
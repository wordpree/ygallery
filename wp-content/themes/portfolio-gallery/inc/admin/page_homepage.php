<?php


class WDWT_homepage_page_class{
  

  public $options;
  
  function __construct(){

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    add_filter("wdwt_admin_setting_output_opt_content_post_categories", array("WDWT_homepage_page_class","add_woo_categories"));
    add_filter("wdwt_admin_setting_output_opt_home_middle_description_post", array("WDWT_homepage_page_class","add_woo_posts"));

    $this->options = array(

      /*featured post*/

      "home_middle_description_post_enable" => array(
        "name" => "home_middle_description_post_enable",
        "title" => __("Enable Featured Post", "portfolio-gallery"),
        'type' => 'checkbox',
        "description" => __( "Check box to display a featured post at the homepage", "portfolio-gallery" ),
        'section' => 'homepage_featured', 
        'tab' => 'homepage', 
        'default' => true,
        'customizer'=>array()
      ),
      "home_middle_description_post" => array(
        "name" => "home_middle_description_post",
        "title" => "", 
        'type' => 'select',
        "valid_options" => $this->get_posts(),
        "sanitize_type" => "sanitize_text_field",
        "description" => __("Select the featured post. Pages selection is available in PRO version", "portfolio-gallery" ),
        'section' => 'homepage_featured', 
        'tab' => 'homepage', 
        'default' => $this->last_post(),
        'customizer' => array()
      ),

      'home_middle_image_maxwidth' => array( 
        "name" => "home_middle_image_maxwidth", 
        "title" => "", 
        'type' => 'number', 
        "description" => __("Specify the maximum width of featured post image", "portfolio-gallery"),
        'input_size' => '4',
        "sanitize_type" => "sanitize_text_field", 
        'unit_symbol' => 'px',
        'min' => 100,
        'max' => 1200,
        'step' => 10,
        'section' => 'homepage_featured', 
        'tab' => 'homepage',
        'default' => 600,
        'customizer' => array()   
      ),
      'home_middle_description_pos' => array( 
        "name" => "home_middle_description_pos", 
        "title" => "", 
        'type' => 'number',
        "description" => __("Row where featured post should be shown. Enter integer from 1. This is applicable only for grid layout of content posts.", "portfolio-gallery"),
        'input_size' => '4',
        "sanitize_type" => "sanitize_text_field", 
        'section' => 'homepage_featured', 
        'tab' => 'homepage',
        'default' => 2,
        'customizer' => array()   
      ),  
      /*content posts*/ 
      "content_posts_enable" => array( 
        "name" => "content_posts_enable",
        "title" => __("Enable Content Posts Filter", "portfolio-gallery"),
        'type' => 'checkbox',  
        "description" => __("Check the box to display posts only from specific categories. If unchecked, all posts are shown.", "portfolio-gallery"),
        'section' => 'homepage_content', 
        'tab' => 'homepage', 
        'default' => false,
        'customizer'=>array()
      ),
      "content_posts_layout" => array(
        "name" => "content_posts_layout",
        "title" => "",
        'type' => 'radio_open',
        'valid_options' => array( 
          'thumb' => __('Grid', "portfolio-gallery"),
          'masonry' => __('Masonry', "portfolio-gallery"),
        ),
        'show' => array(
          'thumb' => array('content_posts_hover_btn'),
          'masonry' => array('content_posts_masonry_title_pos','content_posts_masonry_hover_btn'),
        ),
        'hide' => array(),
        "description" => __("Select view","portfolio-gallery"),
        'section' => 'homepage_content',
        'tab' => 'homepage',
        'default' => 'thumb',
        'customizer'=>array()
      ),
      "content_post_order" => array(
        "name" => "content_post_order",
        "title" => "",
        'type' => 'select',
        "sanitize_type" => "sanitize_text_field",
        "valid_options" => array('asc'=>__("Ascending", "portfolio-gallery"), 'desc'=>__("Descending", "portfolio-gallery")),
        "description" => __("Order of posts", "portfolio-gallery"),
        'section' => 'homepage_content',
        'tab' => 'homepage',
        'default' => array('desc'),
        'customizer'=>array()
      ),
      "content_post_orderby" => array(
        "name" => "content_post_orderby",
        "title" => "",
        'type' => 'select',
        "sanitize_type" => "sanitize_text_field",
        "valid_options" => array('date'=>__("Date", "portfolio-gallery"), 'name'=>__("Name", "portfolio-gallery")),
        "description" => __("Order by", "portfolio-gallery"),
        'section' => 'homepage_content',
        'tab' => 'homepage',
        'default' => array('date'),
        'customizer'=>array()
      ),
      "content_post_categories" => array(
        "name" => "content_post_categories",
        "title" => "",
        'type' => 'select',
        'multiple' => "true",
        "sanitize_type" => "sanitize_text_field",
        "valid_options" => $this->get_categories(),
        "description" => __("Select categories of posts. Pages are available in PRO version.","portfolio-gallery"),
        'section' => 'homepage_content',
        'tab' => 'homepage',
        'default' => $this->get_categories_ids(),
        'customizer'=>array()
      ),
      
      'content_posts_masonry_title_pos' => array( 
        "name" => "content_posts_masonry_title_pos", 
        "title" => "", 
        'type' => 'select', 
        "description" => __("Title and description position", "portfolio-gallery"),
        'valid_options' => array( 
          'below' => __('Below thumb', "portfolio-gallery"),
          'above' => __('Above thumb', "portfolio-gallery"),
          'on_hover' => __('On hover', "portfolio-gallery")
        ),
        'section' => 'homepage_content', 
        'tab' => 'homepage',
        'default' => 'on_hover',
        'customizer' => array()   
      ),
      "content_posts_hover_btn" => array( 
        "name" => "content_posts_hover_btn",
        "title" => '',
        'type' => 'select',  
        "description" => __("Buttons and links on hover", "portfolio-gallery"),
        'valid_options' => array( 
          'lbox_and_link' => __('Lightbox and link buttons', "portfolio-gallery"),
          'link_only' => __('Title as a link, no lightbox button', "portfolio-gallery"),
          'lbox_only' => __('Only lightbox button, no link', "portfolio-gallery"),
        ),
        'section' => 'homepage_content', 
        'tab' => 'homepage', 
        'default' => array('lbox_and_link'),
        'customizer'=>array()
      ),
      "content_posts_masonry_hover_btn" => array( 
        "name" => "content_posts_masonry_hover_btn",
        "title" => '',
        'type' => 'select',  
        "description" => __("Buttons and links on hover", "portfolio-gallery"),
        'valid_options' => array( 
          'lbox_and_link' => __('Title as a link and lightbox button', "portfolio-gallery"),
          'link_only' => __('Title as a link, no lightbox button', "portfolio-gallery"),
          'lbox_only' => __('Only lightbox button, no link', "portfolio-gallery"),
        ),
        'section' => 'homepage_content', 
        'tab' => 'homepage', 
        'default' => array('lbox_and_link'),
        'customizer'=>array()
      ),
      'content_posts_noimage' => array(
        'name' => 'content_posts_noimage', 
        'title' => '', 
        'type' => 'upload_single', 
        "sanitize_type" => "esc_url_raw", 
        'description' => __( 'Placeholder for Content Post if there is no thumb. Or leave blank and edit Post Thumb Background Color.', "portfolio-gallery" ), 
        'section' => 'homepage_content', 
        'tab' => 'homepage', 
        'default' => '',
        'customizer' => array()           
      ),
      'content_posts_maxnumber' => array( 
        "name" => "content_posts_maxnumber", 
        "title" => "", 
        'type' => 'number', 
        "description" => __("Maximal number of posts to load in content posts", "portfolio-gallery"),
        'input_size' => '3',
        "sanitize_type" => "sanitize_text_field", 
        'section' => 'homepage_content', 
        'tab' => 'homepage',
        'step' => '1',
        'min' => '12',
        'max' => '2500',
        'default' => 200,
        'customizer' => array()   
      ),
    );

    if ( is_plugin_active( 'slider-wd/slider-wd.php' )) {

      $this->options['show_slider_wd'] = array( 
        "name" => "show_slider_wd", 
        "title" =>  __("Show Slider WD in header","portfolio-gallery"),
        'type' => 'checkbox_open', 
        'show' => array('slider_wd_id'),
        'hide' => array(),
        "sanitize_type" => "", 
        "description" => __("Show Slider WD","portfolio-gallery"),
        'section' => 'homepage_slider', 
        'tab' => 'homepage', 
        'default' => false,
        'customizer'=>array()
      );
      if(function_exists('wds_get_sliders')){
        $this->options['slider_wd_id'] = array( 
          "name" => "slider_wd_id", 
          "title" =>  __("Choose Slider","portfolio-gallery"),
          'type' => 'select', 
          "sanitize_type" => "sanitize_text_field",
          'valid_options' => wds_get_sliders(),
          "description" => "",
          'section' => 'homepage_slider', 
          'tab' => 'homepage', 
          'default' => array(''),
          'customizer'=>array()
        );
      }
      else{
        $this->options['slider_wd_id'] = array( 
          "name" => "slider_wd_id", 
          "title" =>  __("Enter Slider WD id","portfolio-gallery"),
          'type' => 'number', 
          "sanitize_type" => "sanitize_text_field", 
          "description" => "",
          'section' => 'homepage_slider', 
          'tab' => 'homepage', 
          'default' => "1",
          'customizer'=>array()
        );
      }
      
    }
  
  }


  



  private function get_posts(){
    $args= array(
        'posts_per_page'   => 3000,
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'post_type'        => 'post',
        'post_status'      => 'publish',
         );

    $posts_array_custom=array();
    $posts_array = get_posts( $args );

    foreach($posts_array as $post){
      $key = $post->ID;
      $posts_array_custom[$key] = $post->post_title;
    }
    if(empty($posts_array_custom)){
      $posts_array_custom = array('');
    }
    return $posts_array_custom;
  }

  private function get_categories(){
    $args= array(
        'hide_empty' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
      );
    
    $categories_array_custom=array();
    $categories_array = get_categories( $args );
    
    foreach($categories_array as $category){
      $categories_array_custom[$category->term_id] = $category->name;
    }
    if(empty($categories_array_custom)){
      $categories_array_custom = array('');
    }
    return $categories_array_custom;
  }
  private function get_categories_ids(){
    $args= array(
        'hide_empty' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
      );
    
    $categories_array_custom=array();
    $categories_array = get_categories( $args );
    foreach($categories_array as $category){
      array_push($categories_array_custom,$category->term_id);
    }
    return $categories_array_custom;
  }

  private function get_pages(){
    $args= array(
        'posts_per_page'   => 3000,
        'hierarchical'     => 0,
         );

    $pages_array_custom=array();
    $pages_array = get_pages( $args );

    foreach($pages_array as $page){
      $key = $page->ID;
      $pages_array_custom[$key] = $page->post_title;
    }
    if(empty($pages_array_custom)){
      $pages_array_custom = array('');
    }
    return $pages_array_custom;
  }

  public static function add_woo_posts($posts_array){
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

      $args= array(
        'posts_per_page'   => 3000,
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'post_type'        => 'product',
        'post_status'      => 'publish',
      );

      $woo_posts_array = get_posts( $args );
      $woo_posts = array();
      foreach($woo_posts_array as $woo_post){
        $woo_posts[$woo_post->ID] = $woo_post->post_title;
      }
      $posts_array["valid_options"] = $posts_array["valid_options"] + $woo_posts;

    }
    return $posts_array;
  }

  public static function add_woo_categories($categories_array){
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

      $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'post_date',
        'order'        => 'DESC',
        'hide_empty'   => 0
      );
      $woo_cat_array = get_categories($args);
      $woo_categories = array();
      foreach($woo_cat_array as $woo_cat){
        $woo_categories[$woo_cat->term_id] = $woo_cat->name;
      }
      $categories_array["valid_options"] = $categories_array["valid_options"] + $woo_categories;
    }
    return $categories_array;
  }

  private function last_post(){
    $last_post=array();
    $post_in_array=get_posts( array('posts_per_page' => 1));
    if($post_in_array)
      $last_post=array($post_in_array[0]->ID);
    else
      $last_post=array();

    return $last_post;
  }

  private function last_page(){
    $last_post=array();
    $post_in_array=get_pages( array('posts_per_page' => 1));
    if($post_in_array)
      $last_post=array($post_in_array[0]->ID);
    else
      $last_post=array();

    return $last_post;
  }

  
}
 
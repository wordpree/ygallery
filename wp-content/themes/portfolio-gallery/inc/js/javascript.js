jQuery(document).ready(function ()
{

  var cat_tab_is_animated = 0;
  jQuery('#top-nav li:has(> ul)').addClass('haschild');

  jQuery("#top-nav > ul  li.haschild,#top-nav > div > ul  li.haschild").hover(function ()
  {
    if (matchMedia('only screen and (max-width : 767px)').matches || matchMedia('only screen and (max-width : 1024px)').matches) {
      return false;
    }
    jQuery(this).find('a').eq(0).addClass('clicked').attr('data-content', '');
    var submenu = jQuery(this).find("ul").eq(0);
    timer_menui_hover = setTimeout(function ()
    {
      submenu.slideDown(500, function ()
      {
        if (jQuery('.left_container').hasClass("fixed_menu")) {
          jQuery(window).scroll();   //fix for the case when left is short enough, but becomes larger after submenu open
        }

      });

    }, 300);
  }, function ()
  {
    if (matchMedia('only screen and (max-width : 767px)').matches || matchMedia('only screen and (max-width : 1024px)').matches) {
      return false;
    }
    clearTimeout(timer_menui_hover);
    jQuery(this).find('a').eq(0).removeClass('clicked').attr('data-content', '');
    var submenu = jQuery(this).find("ul").eq(0);
    setTimeout(function ()
    {
      submenu.slideUp(500, function ()
      {
        if (jQuery('.left_container').hasClass("fixed_menu")) {
          jQuery(window).scroll();  //fix for the case when left is short enough, but becomes larger after submenu open
        }
      });

    }, 300);
  });


  /*Add arrow to parent menu item*/
  jQuery("#top-nav > ul  li:has(> ul),#top-nav > div > ul  li:has(> ul)").each(function () {
    if ( !jQuery(this).find(".open_sub_arrow").length && jQuery(this).find("ul").length) {
      jQuery(this).find(">a").append('<span class="open_sub_arrow"></span>');
    }
  });

  jQuery("#top-nav > ul  li.haschild > a .open_sub_arrow,#top-nav > div > ul  li.haschild > a .open_sub_arrow").click(function ()
  {
    if(matchMedia('only screen and (max-width : 1024px)').matches) {
      if(jQuery(this).parent().parent().hasClass("open")) {
        jQuery(this).parent().parent().parent().find(".haschild ul").slideUp(100);
        jQuery(this).parent().parent().removeClass("open");
        return false;
      }
      jQuery(this).parent().parent().parent().find(".haschild ul").slideUp(100);
      jQuery(this).parent().parent().parent().find(".haschild").removeClass("open");
      jQuery(this).parent().next("ul").slideDown("fast");
      jQuery(this).parent().parent().addClass("open");
      return false;
    }
  });


  jQuery("#back").on("click", ".responsive_menu", function ()
  {
    if (jQuery(".phone-menu-block").hasClass("open")) {
      jQuery(".phone-menu-block").slideUp("fast", function ()
      {
        jQuery(this).removeAttr('style');
      });
      jQuery(".phone-menu-block").removeClass("open");
    }
    else {
      jQuery(".phone-menu-block").slideDown("slow");
      jQuery(".phone-menu-block").addClass("open");
    }
  });

  /* Disable right click.*/
  if (wdwt_custom_js.wdwt_images_right_click == "1") {
    jQuery("img").bind("contextmenu", function (e)
    {
      return false;
    });
    jQuery("img").css('webkitTouchCallout', 'none');
  }
  /*
   jQuery('.GalleryPost').on('click', function ()
   {

   if (wdwt_page_settings.width != 'desktop') {
   jQuery('.GalleryPost').find('.caption').hide();
   jQuery('.GalleryPost').find('.gallery-post-info').hide();
   if (!jQuery(this).find('.caption').is(":visible")) {
   jQuery(this).find('.caption').show();
   jQuery(this).find('.gallery-post-info').show();
   }
   }


   });
   */

  /*if thumbs grid or masonry set to fade in effect, prevent links of lbox and post from working on mobile when overlay becomes visible*/
  /*delegate events because posts in grid maj be AJAX-loaded*/
  jQuery('body').on('click',"#image_list_top.fadein .SearchPost:not(.da-empty),.da-thumbs.thumb.fadein .image_list_item:not(.da-empty), .da-thumbs.masonry .image_list_item.on_hover:not(.da-empty)",


    function (event)
    {
      if (matchMedia('only screen and (max-width : 1024px)').matches) {
        event.stopPropagation();
        wdwt_remove_fadein_mobile();
        jQuery(this).find('article').addClass('wdwt_visible');
        jQuery(this).find('.masonry_item_content').addClass('wdwt_visible');

      }


    });

  jQuery('body').on('click',".fadein .GalleryPost",
    function (event)
    {
      if (matchMedia('only screen and (max-width : 1024px)').matches) {
        event.stopPropagation();
        wdwt_remove_fadein_mobile();
        jQuery(this).find('.caption, .gallery-post-info').addClass('wdwt_visible');
      }
    });
  jQuery('html').on('click',function(){
    wdwt_remove_fadein_mobile();
  });

  window.wdwt_remove_fadein_mobile = function() {
    jQuery('body').find('#image_list_top.fadein .SearchPost:not(.da-empty) article').removeClass('wdwt_visible');
    jQuery('body').find('.da-thumbs.thumb.fadein .image_list_item:not(.da-empty) article').removeClass('wdwt_visible');
    jQuery('body').find('.da-thumbs.masonry .image_list_item.on_hover:not(.da-empty) .masonry_item_content').removeClass('wdwt_visible');
    jQuery('body').find('.fadein .GalleryPost .caption').removeClass('wdwt_visible');
    jQuery('body').find('.fadein .GalleryPost .gallery-post-info').removeClass('wdwt_visible');

  };




});




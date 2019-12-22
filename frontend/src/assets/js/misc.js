// Misc.js

(function($) {
    'use strict';
    $(function() {
      var body = $('body');
      var contentWrapper = $('.content-wrapper');
      var scroller = $('.container-scroller');
      var footer = $('.footer');
      var sidebar = $('.sidebar');

      //Close other submenu in sidebar on opening any

      sidebar.on('show.bs.collapse', '.collapse', function() {
        sidebar.find('.collapse.show').collapse('hide');
      });


      //Change sidebar and content-wrapper height
      applyStyles();

      function applyStyles() {
        //Applying perfect scrollbar
        if (!body.hasClass("rtl")) {
          if ($('.tab-content .tab-pane.scroll-wrapper').length) {
            const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
          }
          if ($('.chats').length) {
            const chatsScroll = new PerfectScrollbar('.chats');
          }
        }
      }

      //checkbox and radios
      $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    });
  })(jQuery);

"use strict";

(function ($) {

    $.fn.showPreloader = function (i) {
        $(this).prepend(`<div class="ajax-preloader"></div>`);
    }

    $.fn.removePreloader = function (i) {
        $(this).find('.ajax-preloader').remove();
    }

})(jQuery);
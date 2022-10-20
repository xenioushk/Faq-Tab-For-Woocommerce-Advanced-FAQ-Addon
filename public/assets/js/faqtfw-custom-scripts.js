(function($) {
    "use strict";

    $(function() {

        if ($(".faqtfw_tab_tab").length && faqftw_faq_counter == 1) {

            var  $faqtfw_tab = $("#tab-faqtfw_tab"),
            $faqftw_total_faq_items = $faqtfw_tab.find("div.bwl-faq-container").length,
            $faqftw_faq_title_text = $(".faqtfw_tab_tab").find('a').text();
            
            if ($faqftw_total_faq_items > 0) {
                $(".faqtfw_tab_tab").find('a').html($faqftw_faq_title_text + " (" + $faqftw_total_faq_items + ")");
            }

        }

    });

}(jQuery));
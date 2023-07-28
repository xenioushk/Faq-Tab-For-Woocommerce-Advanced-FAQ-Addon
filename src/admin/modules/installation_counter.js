;(function ($) {
  function baf_ftfwc_installation_counter() {
    return $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "baf_ftfwc_installation_counter", // this is the name of our WP AJAX function that we'll set up next
        product_id: BafFtfwcAdminData.product_id, // change the localization variable.
      },
      dataType: "JSON",
    })
  }

  if (typeof BafFtfwcAdminData.installation != "undefined" && BafFtfwcAdminData.installation != 1) {
    $.when(baf_ftfwc_installation_counter()).done(function (response_data) {
      // console.log(response_data)
    })
  }
})(jQuery)
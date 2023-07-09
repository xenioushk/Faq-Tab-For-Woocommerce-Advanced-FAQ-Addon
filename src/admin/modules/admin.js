;(function ($) {
  if (typeof inlineEditPost == "undefined") {
    return ""
  }

  // we create a copy of the WP inline edit post function
  var $wp_inline_edit = inlineEditPost.edit

  // and then we overwrite the function with our own code
  inlineEditPost.edit = function (id) {
    // "call" the original WP edit function
    // we don't want to leave WordPress hanging
    $wp_inline_edit.apply(this, arguments)

    // now we take care of our business

    // get the post ID

    var $post_id = 0

    if (typeof id == "object") $post_id = parseInt(this.getId(id))

    if ($post_id > 0) {
      // define the edit row
      var $edit_row = $("#edit-" + $post_id)

      // Display Status

      var baf_woo_tab_hide_status = $("#baf_woo_tab_hide_status-" + $post_id).data("status_code")

      $edit_row.find('select[name="baf_woo_tab_hide_status"]').val(baf_woo_tab_hide_status == "" ? 3 : baf_woo_tab_hide_status)
    }
  }

  /*------------------------------ Bulk Edit Settings ---------------------------------*/

  $("#bulk_edit").on("click", function () {
    // define the bulk edit row
    var $bulk_row = $("#bulk-edit")
    // get the selected post ids that are being edited
    var $post_ids = new Array()
    $bulk_row
      .find("#bulk-titles")
      .children()
      .each(function () {
        $post_ids.push(
          $(this)
            .attr("id")
            .replace(/^(ttle)/i, "")
        )
      })

    var $baf_woo_tab_hide_status = $bulk_row.find('select[name="baf_woo_tab_hide_status"]').val()
    // save the data
    $.ajax({
      url: ajaxurl, // this is a variable that WordPress has already defined for us
      type: "POST",
      async: false,
      cache: false,
      data: {
        action: "manage_wp_posts_using_bulk_edit_faqtfw", // this is the name of our WP AJAX function that we'll set up next
        post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
        baf_woo_tab_hide_status: $baf_woo_tab_hide_status,
      },
    })
  })
})(jQuery)

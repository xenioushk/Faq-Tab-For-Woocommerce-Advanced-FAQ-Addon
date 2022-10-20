(function ($) {

    $(function () {


        $('.bwl_cmb_remove_file').on('click', function () {

            $("#" + $(this).data('parent_field')).attr("value", "");

        });


        function bwl_cmb_generate_repeat_field($field_type, $field_name, $count_val, $label_text, $delete_text, $upload_text, $default_value) {
//            console.log($default_value.toSource());
            var $repeat_row = '';

            if ($field_type == 'repeatable_select') {
//                console.log("here");

                var $select_options = "";

                var $parse_default_value = $.parseJSON($default_value);

                $.each($parse_default_value, function (index, element) {

                    $select_options += '<option value="' + index + '">' + element + '</option>';

                });



                $repeat_row += '<li class="bwl_cmb_repeat_row" data-row_count="' + $count_val + '">' +
                        '<span class="label">' + $label_text + '</span> ' +
                        '<select id="' + $field_name + '_' + $count_val + '_url" name="' + $field_name + '[' + $count_val + ']">' +
                        '<option value="" selected="selected">- Select -</option>' +
                        $select_options +
                        '</select>' +
                        '<div class="clear"></div>' +
                        '<a class="delete_row" title="' + $delete_text + '">' + $delete_text + '</a>' +
                        '</li>';

            } else {

                $repeat_row += '<li class="bwl_cmb_repeat_row" data-row_count="' + $count_val + '">' +
                        '<span class="label">' + $label_text + '</span> ' +
                        '<input id="' + $field_name + '_' + $count_val + '_url" name="' + $field_name + '[' + $count_val + ']" type="text" class="img-path" value="" />' +
                        '<input id="upload_' + $field_name + '_' + $count_val + '_button" type="button" class="button bwl_cmb_upload_file" value="' + $upload_text + '" data-parent_field="' + $field_name + '" data-row_count="' + $count_val + '"/>' +
                        '<div class="clear"></div>' +
                        '<a class="delete_row" title="' + $delete_text + '">' + $delete_text + '</a>' +
                        '</li>';
            }



            return $repeat_row;


        }


        // Clone Rows.

        function bkb_get_new_row_id() {


            var new_row_id = 0;
            var $bwl_cmb_repeat_field_container = $('.bwl_cmb_repeat_field_container');
            $bwl_cmb_repeat_field_container.find("li").each(function () {

                if ($(this).data('row_count') > new_row_id) {
                    new_row_id = $(this).data('row_count');
                }

            });

            return new_row_id;


        }


        $("#add_new_row").click(function () {

            var $bwl_cmb_repeat_field_container = $(this).prev('.bwl_cmb_repeat_field_container');
            var $count_val = $bwl_cmb_repeat_field_container.find('li').length;
//            console.log($count_val);

            if ($count_val != 0) {
                $count_val = bkb_get_new_row_id() + parseInt(1);
            }

            var $field_type = $(this).data('field_type');
            var $field_name = $(this).data('field_name');

//            console.log($field_type);
            var $label_text = $(this).data('label_text');
            var $delete_text = $(this).data('delete_text');
            var $upload_text = $(this).data('upload_text');
            var $default_value = $('#bwl_cmb_data_set').val();


            var $bwl_cmb_new_row_html = bwl_cmb_generate_repeat_field($field_type, $field_name, $count_val, $label_text, $delete_text, $upload_text, $default_value);

//            console.log($bwl_cmb_new_row_html);

            if ($bwl_cmb_repeat_field_container.find('li').length == 0) {
                $bwl_cmb_repeat_field_container.html($bwl_cmb_new_row_html);
            } else {
                $bwl_cmb_repeat_field_container.find('li:last-child').after($bwl_cmb_new_row_html);
            }



        });


        // Remove Rows.

        $(document).on('click', '.delete_row', function () {
            $(this).parent().addClass('bwl_cmb_row_deleted').fadeOut(500, function () {
                $(this).remove();
            });
        });

        // Sortable lists.

        $(".bwl_cmb_repeat_field_container").sortable({placeholder: "bwl-cmb-sort-highlight"});

    });



})(jQuery);
<?php

add_shortcode('baf_woo_tab', 'baf_woo_tab');

function baf_woo_tab($atts)
{
    extract(shortcode_atts([
        'post_type'     => 'bwl_advanced_faq',
        'post_ids'         => '',
        'orderby'         => 'post__in',
        'order'            => 'ASC',
        'limit'              => -1,
        'suppress_filters' => 0,
        'sbox'            => 1,
        'paginate' => 1,
        'pag_limit' => 5,
        'meta_info' => 1,
        'voting' => 1
    ], $atts));

    return do_shortcode('[bwla_faq post_ids="' . $post_ids . '" orderby="' . $orderby . '" order="' . $order . '" sbox="' . $sbox . '" paginate="' . $paginate . '" pag_limit="' . $pag_limit . '"  meta_info="' . $meta_info . '" voting="' . $voting . '" /]');
}
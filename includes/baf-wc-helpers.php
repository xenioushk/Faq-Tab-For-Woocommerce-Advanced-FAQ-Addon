<?php

// @Description: if filter parameter is two dimentionsal array then it's return first content of array. other wise it's return the field value
// @Since: Version 1.1.2
// @Date: 15-04-2016

add_filter('filter_baftfwc_content_data', 'filter_baftfwc_content_data');

function filter_baftfwc_content_data( $field_value ) {
        
        if ( isset( $field_value[0] ) && is_array( $field_value[0] )  && !empty( $field_value[0] ) ) {

            return $field_value[0]; //  for new version
            
        } else if ( isset( $field_value ) && is_array( $field_value )  && !empty( $field_value ) ) {
            
            return $field_value; // for old version
        }
        else {
            
            return array(); // return nothing.
            
        }
    
}
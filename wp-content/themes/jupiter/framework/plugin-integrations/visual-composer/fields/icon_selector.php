<?php
if (!defined('THEME_FRAMEWORK')) exit('No direct script access allowed');

/**
 * Add Icon Selector Option to Visual Composer Params
 *
 * @author      Michael Taheri
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.5
 * @package     artbees
 */



if (function_exists('mk_add_shortcode_param')) {
    mk_add_shortcode_param('icon_selector', 'mk_icon_selector_param_field');
}



function mk_icon_selector_param_field($settings, $value) {
    $dependency = vc_generate_dependencies_attributes($settings);
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $options = isset($settings['value']) ? $settings['value'] : '';
    $output = '';
    $uniqeID = uniqid();
    
    $output.= '<div class="mk-vc-icon-selector ' . $class . '" id="icon-selector' . $uniqeID . '">';
        $output .= '<input name="' . $param_name  . '" ' . $dependency . 'class="wpb_vc_param_value ' . $param_name  . ' ' . $type  . '_field" type="hidden" value="' . $value. '" />';
        $output .= '<div class="mk-vc-icon-selector-view-wrap"><span class="mk-vc-icon-selector-view">' . Mk_SVG_Icons::get_svg_icon_by_class_name(false, $value) . '</span></div>';
        $output .= '<a href="#" class="mk-vc-icon-selector-btn">' . __( 'Select Icon', 'mk_framework' )  . '</a>';
    $output.= '</div>';
    
    return $output;
}
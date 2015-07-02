<?php

/**
 * Plugin Name: Custom Image CSS Classes
 * Plugin URI: Nivijah.com
 * Description: This simple plugin adds two additional fields to the media window, that lets you set additional classes to images on top of the default ones.
 * Version: 1.0
 * Author: nivijah
 * Author URI: NiviJah.com
 * License: GPL2
 */


function nivijah_attachment_field_credit($form_fields, $post)
{
    
    $form_fields['nivijah-custom-class-featured'] = array(
        'label' => 'Class(featured)',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'nivijah_custom_class_featured', true),
        'helps' => 'Custom class for featured images'
    );
    $form_fields['nivijah-custom-class-all']      = array(
        'label' => 'Class(all)',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'nivijah_custom_class_all', true),
        'helps' => 'Custom class for all other images'
    );
    return $form_fields;
}

add_filter('attachment_fields_to_edit', 'nivijah_attachment_field_credit', 10, 2);

function nivijah_attachment_field_credit_save($post, $attachment)
{
    
    if (isset($attachment['nivijah-custom-class-featured']))
        update_post_meta($post['ID'], 'nivijah_custom_class_featured', $attachment['nivijah-custom-class-featured']);
    if (isset($attachment['nivijah-custom-class-all']))
        update_post_meta($post['ID'], 'nivijah_custom_class_all', ($attachment['nivijah-custom-class-all']));
    
    return $post;
}

add_filter('attachment_fields_to_save', 'nivijah_attachment_field_credit_save', 10, 2);

add_filter('post_thumbnail_html', 'nivijah_add_class_to_thumbnail');
function nivijah_add_class_to_thumbnail($thumb)
{
    $string = get_post_meta(get_post_thumbnail_id(), 'nivijah_custom_class_featured', true);
    $thumb  = str_replace('attachment-', $string . ' attachment-', $thumb);
    return $thumb;
}

function nivijah_give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '')
{
    $string = get_post_meta($id, 'nivijah_custom_class_all', true);
    // Separate classes with spaces, e.g. 'img image-link' 
    $html   = preg_replace('/(<img.*? class=")(.*?)(" .*?>)/', '$1$2 ' . $string . '$3', $html);
    return $html;
}
add_filter('image_send_to_editor', 'nivijah_give_linked_images_class', 10, 8);
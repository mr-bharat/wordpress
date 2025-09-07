<?php

// Remove hentry class
add_filter('post_class', function($classes) {
    if (($key = array_search('hentry', $classes)) !== false) {
        unset($classes[$key]);
    }
    return $classes;
});
<?php 
add_action( 'wp_ajax_mona_ajax_default',  '_default' ); // login
add_action( 'wp_ajax_nopriv_mona_ajax_default',  '_default' ); // no login

function _default() {

}
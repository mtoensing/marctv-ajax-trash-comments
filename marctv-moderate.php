<?php

/*
  Plugin Name: MarcTV Moderate Comments
  Plugin URI: http://marctv.de/blog/marctv-wordpress-plugins/
  Description: Trash and moderate comments in the frontend with one click.
  Version: 1.0.3
  Author: MarcDK
  Author URI: http://www.marctv.de
  License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  your option) any later version.

 */



function add_marctv_ajax_comment_scripts() {
	/* load css and js in the frontend theme */
	wp_enqueue_style(
		"jquery.marctv-moderate", WP_PLUGIN_URL . "/" . dirname( plugin_basename( __FILE__ ) ) . "/marctv-moderate.css", false, "1.0" );

	wp_enqueue_script(
		"jquery.marctv-moderate", WP_PLUGIN_URL . "/" . dirname( plugin_basename( __FILE__ ) ) . "/jquery.marctv-moderate.js", array( "jquery" ), "1.0", 0 );

	/* localize the strings for frontend scripts */
	$params = array(
		'adminurl' => admin_url( 'admin-ajax.php' ),
		'trash_string' => __( 'trash', 'marctv-moderate' ),
		'untrash_string' => __( 'untrash', 'marctv-moderate' ),
		'trashing_string' => __( 'trashing', 'marctv-moderate' ),
		'untrashing_string' => __( 'untrashing', 'marctv-moderate' ),
		'error_string' => __( 'error', 'marctv-moderate' ),
		'replace_string' => __( 'replace comment text', 'marctv-moderate' ),
		'replacing_string' => __( 'replacing comment text', 'marctv-moderate' ),
		'replaced_string' => __( 'replaced comment text', 'marctv-moderate' ),
		'already_replaced_string' =>  __( 'comment text already replaced', 'marctv-moderate' ),
		'confirm_string' =>  __( 'This action can not be undone! Save and proceed?', 'marctv-moderate' ),
		'warned' => get_option('marctv-moderate-warned')
	);

	wp_localize_script( 'jquery.marctv-moderate', 'marctvmoderate', $params );
}

/**
 *
 * Generates the html links for the frontend
 *
 * @param $comment_text
 *
 * @return string
 */
function get_marctv_ajax_links( $comment_text ) {
	if ( current_user_can( 'moderate_comments' ) && is_single() ) {
		$comment_id = get_comment_ID();
		$nonce      = wp_create_nonce("delete-comment_$comment_id");
		$del_nonce  = esc_html( '_wpnonce=' . $nonce );
		$trash_url  = esc_url( "/wp-admin/comment.php?action=trashcomment&c=$comment_id&$del_nonce" );
		$link = "<small>";
		$link .= "<a data-nonce='$nonce' data-cid='$comment_id' class='marctv-trash-btn marctv-trash' href='$trash_url'
		title='" . esc_attr__( __( 'Move this comment to the trash', 'marctv-moderate' ) ) . "'>"
		         . __( 'trash', 'marctv-moderate' ) . '</a> | ';
		$link .= "<a data-nonce='$nonce' data-cid='$comment_id' class='marctv-replace-btn marctv-replace' href='#'
		title='" . esc_attr__( __( 'Replace comment text with customized moderation text.', 'marctv-moderate' ) )
		         . "'>" . __( 'replace comment text', 'marctv-moderate' ) . '</a> | ';

		$link .= "<a href='/wp-admin/options-general.php?page=marctv_moderate' title='" . esc_attr__( __( 'MarcTV moderate settings page', 'marctv-moderate' ) )
		         . "'>" . __( 'Moderation settings', 'marctv-moderate' ) . '</a>';

		$link .= "</small>";

		return $comment_text . $link;
	}
	return $comment_text;
}


function marctv_trash_comment() {

	if ( current_user_can( 'moderate_comments' ) ) {
		/* retrieve comment id from post ajax request and sanitize to number */
		$comment_id = filter_input( INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_FLOAT );
		$comment_status = wp_get_comment_status( $comment_id );

		/* check if the ajax referrer is the trash link */
		check_ajax_referer("delete-comment_$comment_id");

		switch ( $comment_status ) {
			case 'approved':
				if( wp_trash_comment( $comment_id ) ) {
					wp_die( 'trashed' );
				}
				break;
			case 'trash':
				if( wp_untrash_comment( $comment_id ) ) {
					wp_die( 'untrashed' );
				}
				break;
			default:
				wp_die( 'error' );
		}
	}
}

function marctv_replace_comment() {
	/* retrieve comment id from post ajax request and sanitize to number */
	$comment_id = filter_input( INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_FLOAT );
	$warned = filter_input( INPUT_POST, 'warned', FILTER_VALIDATE_BOOLEAN );

	if($warned == true ) {
		update_option('marctv-moderate-warned', 1);
	}

	/* check if the ajax referrer is the trash link */
	check_ajax_referer("delete-comment_$comment_id");

	$comment_arr = array();
	$comment_arr['comment_ID'] = $comment_id;
	$comment_arr['comment_content'] = get_option('marctv-moderation-text');
	if ( wp_update_comment( $comment_arr )) {
		wp_die(1);
	} else {
		wp_die(0);
	}
}


function marctv_moderate_plugin_menu(){
	add_options_page('MarcTV Moderate Comments', 'MarcTV Moderate Comments', 'manage_options', 'marctv_moderate', 'marctv_moderate_plugin_menu_options');
}

function register_mysettings() {
	//register our settings
	register_setting( 'marctv-moderate-settings-group', 'marctv-moderation-text' );
	register_setting( 'marctv-moderate-settings-group', 'marctv-moderate-warned' );
}

function marctv_moderate_load_textdomain() {
	load_plugin_textdomain( 'marctv-moderate', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function marctv_moderate_plugin_menu_options(){
	include('admin/marctv-moderate-admin.php');
}

function  marctv_moderate_activate() {
	if(empty(get_option('marctv-moderation-text'))) {

		/* Loading the textdomain. I could not figure out to prevent this here. */
		load_plugin_textdomain( 'marctv-moderate', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		/* If the moderation text is empty fill it with the default text */
		update_option('marctv-moderation-text',__( '[incorrect topic]', 'marctv-moderate' ));
	}
}



add_filter( 'comment_text', 'get_marctv_ajax_links', 99 );

add_action( 'admin_init', 'register_mysettings' );

add_action( 'wp_print_styles', 'add_marctv_ajax_comment_scripts' );

add_action( "wp_ajax_marctv_trash_comment", "marctv_trash_comment" );

add_action( "wp_ajax_marctv_replace_comment", "marctv_replace_comment" );

add_action( 'plugins_loaded', 'marctv_moderate_load_textdomain' );

add_action('admin_menu','marctv_moderate_plugin_menu');

register_activation_hook( __FILE__, 'marctv_moderate_activate' );
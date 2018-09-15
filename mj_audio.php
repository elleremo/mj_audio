<?php
/*
Plugin Name: Mojo Media Audio
Author: Elleremo
Version: 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class MjAudio {
	private $version = "2.0.";


	public function __construct() {
		add_action( 'init', array( $this, 'taxonomy' ), 10 );
		add_action( 'init', array( $this, 'post_type' ), 12 );
	}

	function post_type() {
		register_post_type( 'audio', array(
			'labels'             => array(
				'name'               => 'Подкасты',
				'singular_name'      => 'Подкаст',
				'add_new'            => 'Добавить подкаст',
				'add_new_item'       => 'Добавить подкаст',
				'edit_item'          => 'Редактировать подкаст',
				'new_item'           => 'Новый подкаст',
				'view_item'          => 'Посмотреть подкаст',
				'search_items'       => 'Найти подкаст',
				'not_found'          => 'Подкастов не найдено',
				'not_found_in_trash' => 'В корзине книг не подкастов',
				'parent_item_colon'  => '',
				'menu_name'          => 'Подкасты'
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-microphone',
			'taxonomies'         => array( 'audio-page' ),
			'supports'           => array( 'title' )
		) );
	}

	function taxonomy() {
		$labels = array(
			'name'              => 'Страница подкаста',
			'singular_name'     => 'Страница',
			'search_items'      => 'Поиск страниц',
			'all_items'         => 'Все страницы',
			'parent_item'       => 'Родительская страница',
			'parent_item_colon' => 'Родительская страница:',
			'edit_item'         => 'Редактировать страницу',
			'update_item'       => 'Обновить страницу',
			'add_new_item'      => 'Добавить новую страницу',
			'new_item_name'     => 'Добавить новую страницу',
			'menu_name'         => 'Ленты RSS'
		);
		register_taxonomy( 'audio-page', 'audio', array(
			'hierarchical' => true,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'podcast' ),
		) );
	}

}


function MjAudio_init() {
	new MjAudio ();
	require_once( "includes/functions.php" );

}

add_action( "plugins_loaded", "MjAudio_init", 20 );

function MjAudio_add_feed_init() {
	require_once( "includes/MjPodcastFeed.php" );
	new MjPodcastFeed( __FILE__, '1.0.0' );
}

add_action( "init", "MjAudio_add_feed_init", 0 );

function MjAudio_page_init() {
	require_once( "includes/MjPodcastPage.php" );
	new MjPodcastPage( __FILE__, '1.0.0' );
}

add_action( "plugins_loaded", "MjAudio_page_init" );

function MjPodcastAudioList_init() {
	require_once( "includes/MjPodcastAudioList.php" );
	new MjPodcastAudioList( __FILE__, '1.0.0' );
}

add_action( "plugins_loaded", "MjPodcastAudioList_init" );
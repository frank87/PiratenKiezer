<?php
/*
Plugin Name: PiratenKiesWijzer
Plugin URI: http://frank87.github.com/
Description: wordpress front voor PiratenKieswijzer
Version: 1.0
Author: Me
License: Piraat
*/

class KiesWijzerWidget extends WP_Widget{
	function __construct() {
		parent::__construct( false, 'Piraten Kieswijzer' );
	}

	function widget( $args, $instance ) {
		require __DIR__.'/tabel.php';
	}


	function update( $new_instance, $old_instance ) {
		// dont know
	}

	function form( $instance )
	{
		//no action;
	}
}

function ppnl_kieswijzer_register() {
	register_widget('KiesWijzerWidget');
}


add_action( 'widgets_init', 'ppnl_kieswijzer_register' );
register_activation_hook( __FILE__, 'ppnlkw_install' );

function KiesHulpTabel( $atts, $content, $tag )
{
require __DIR__."/tabel.php";
}

add_shortcode( 'KiesHulp', 'kiesHulpTabel' )
?>

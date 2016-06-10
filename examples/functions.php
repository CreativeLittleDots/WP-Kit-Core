<?php
    
// make sure composer has been installed
if( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	
	wp_die('Composer has not been installed, try running composer', 'Dependancy Error');
	
}

// Use composer to load the autoloader.
require __DIR__ . '/vendor/autoload.php';

// do all your normal wordpress stuff

set_post_thumbnail_size(600, 400, true);

add_image_size( 'slide', 585, 380, true );
			
add_image_size( 'product', 600, 400, true );

add_image_size( 'case_study', 600, 300, true );

add_image_size( 'partner', 320, 220, true );

add_image_size( 'post', 1000, 400, true );

register_nav_menus(
	array(
		'header-menu' => __( 'Header Menu' ),
		'side-menu' => __( 'Side Menu' ),
		'foot-menu' => __( 'Footer Menu' )
	)
);

register_sidebar(array(
	'name'          => sprintf( __( 'Simple Product' ) ),
	'id'            => 'simple-product',
	'description'   => '',
	'class'         => '',
	'before_widget' => '<div class="widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<div class="title"><h3>',
	'after_title'   => '</h3></div>'
));

register_sidebar(array(
	'name'          => sprintf( __( 'External Product' ) ),
	'id'            => 'external-product',
	'description'   => '',
	'class'         => '',
	'before_widget' => '<div class="widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<div class="title"><h3>',
	'after_title'   => '</h3></div>'
));

add_theme_support( 'woocommerce' );

add_theme_support( 'menus' );

add_post_type_support( 'product', 'revisions' );

?>
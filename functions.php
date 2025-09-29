<?php
/**
 * custom_portfolio functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package custom_portfolio
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function custom_portfolio_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on custom_portfolio, use a find and replace
		* to change 'custom_portfolio' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'custom_portfolio', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );
  
  // make custom cv posts CPTs
  function create_cv_entry_cpt() {
    $args = array(
      'labels'      => array(
          'name'          => __('CV Entries'),
          'singular_name' => __('CV Entry'),
      ),
      'public'      => true,
      'show_in_rest' => true, // Ensures it's available in REST (important for WPGraphQL)
      'show_in_graphql' => true,
      'graphql_single_name' => 'cvEntry', // This makes it accessible as 'cvEntry'
      'graphql_plural_name' => 'cvEntries', // This makes it accessible as 'cvEntries'
      'has_archive' => false,
      'supports'    => array('title', 'editor'),
      'menu_icon'   => 'dashicons-portfolio',
    );
    register_post_type('cv_entry', $args);
  }
  add_action('init', 'create_cv_entry_cpt');
  
  // cors settings
  add_action('init', function() {
    $allowed = [
        'https://jeremywy.com',
        'http://localhost:3000', // dev
    ];
    if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed)) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(200);
        exit;
    }
  });



	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );
  // set up cors
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization");
 
  // Redirect all users to wp-admin
  function redirect_to_admin() {
     if (!is_admin()) {
        wp_redirect(admin_url());
        exit;
      }
  }
  add_action('template_redirect', 'redirect_to_admin');

  // add the script for header animation
  function enqueue_custom_scripts() {
    // Enqueue Konva
    wp_enqueue_script(
      'konva-js',
      'https://cdn.jsdelivr.net/npm/konva@9/konva.min.js',
      array(),
      '9.0.0',
      true
    );

    // Then enqueue your custom script
    wp_enqueue_script(
      'header-animation',
      get_template_directory_uri() . '/js/header-animation.js',
      array('konva-js'), // This ensures Konva loads first
      '1.0.0',
      true
    );

    wp_enqueue_script(
      'subway-layout', // Name for the script
      get_template_directory_uri() . '/js/subway.js', // Path to the script
      '1.0.0', // Version number
      true // Load in footer
    );
  }
  add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
  
  // add youtube api
  function enqueue_youtube_iframe_api() {
    // Enqueue the YouTube IFrame API script
    wp_enqueue_script(
        'youtube-iframe-api', // Handle
        'https://www.youtube.com/iframe_api', // Source
        array(), // Dependencies (none)
        null, // Version (none)
        true // Load in footer
    );
  }
  add_action('wp_enqueue_scripts', 'enqueue_youtube_iframe_api');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'custom_portfolio' ),
      'reg_header' => __( 'Regular Header Menu', 'custom_portfolio' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'custom_portfolio_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'custom_portfolio_setup' );

// prevent introspection for security
add_filter('graphql_enable_introspection', '__return_false');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function custom_portfolio_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'custom_portfolio_content_width', 640 );
}
add_action( 'after_setup_theme', 'custom_portfolio_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function custom_portfolio_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'custom_portfolio' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'custom_portfolio' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'custom_portfolio_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function custom_portfolio_scripts() {
	wp_enqueue_style( 'custom_portfolio-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'custom_portfolio-style', 'rtl', 'replace' );

	wp_enqueue_script( 'custom_portfolio-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'custom_portfolio_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


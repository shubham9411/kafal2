<?php
/**
 * Kafal functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kafal
 */

if ( ! function_exists( 'kafal_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kafal_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'kafal' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'kafal' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'kafal_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			// No widget initially
			'sidebar-1' => array( '' ),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array(
			'home',
			'about' => array(),
			'contact' => array(),
			'blog' => array(),
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'menu-1' => array(
				'name' => __( 'Top Menu', 'kafal' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
		),
	);

	/**
	 * Filters Kafal array of starter content.
	 *
	 * @since Kafal 1.0
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'kafal_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
}
endif;
add_action( 'after_setup_theme', 'kafal_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kafal_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kafal_content_width', 640 );
}
add_action( 'after_setup_theme', 'kafal_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kafal_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'kafal' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'kafal' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'kafal_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kafal_scripts() {
	wp_enqueue_style( 'kafal-style', get_stylesheet_uri(), array( 'kafal-bootstrap', 'kafal-fa', 'kafal-clean-blog-css' ), '4342017' );

	wp_enqueue_style( 'kafal-bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.3.7' );
	
	wp_enqueue_style( 'kafal-fa', get_template_directory_uri() . '/css/font-awesome.css', array( 'kafal-bootstrap' ), '4.6.3' );

	wp_enqueue_style( 'kafal-clean-blog-css', get_template_directory_uri() . '/css/clean-blog.css', array( 'kafal-bootstrap' ), '1.0' );
	
	wp_enqueue_style( 'kafal-font-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' );

	wp_enqueue_script( 'kafal-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '3.3.7', true );

	wp_enqueue_script( 'kafal-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '1.0', true );
	
	wp_enqueue_script( 'kafal-main', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0', true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kafal_scripts' );

/**
 * Registers an editor stylesheet for the theme.
 */
function kafal_add_editor_styles() {
	add_editor_style( get_template_directory_uri() . '/css/bootstrap.css' );
	add_editor_style( get_template_directory_uri() . '/css/clean-blog.css' );
	add_editor_style( 'style.css' );
}
add_action( 'admin_init', 'kafal_add_editor_styles' );

/**
 * Function for continue reading excerpts.
 */
if ( ! function_exists( 'kafal_excerpt_more' ) ) {
	function kafal_excerpt_more( $more ) {
		if( is_admin() ){
			return $more;
		}
		global $post;
		return '&hellip; <a href="' . get_the_permalink() . '" title="' . __( 'Read More', 'kafal' ) . '" class="read-more">' . __( 'Continue reading', 'kafal' ) . '<span class="meta-nav">&rarr;</span></a>';
	}
	add_filter( 'excerpt_more', 'kafal_excerpt_more' );
}

/**
 * Function for rendering site heading.
 */
function kafal_site_heading() {
	global $post;
	$headline = '';
	if ( is_single() || is_page() ) {
		the_title( '<h1 class="headline-single">', '</h1>' );
	} else if ( is_archive() ) {
		the_archive_title( '<h1 class="headline-archive">', '</h1>' );
	} else {
		if ( is_home() || is_search() || is_404() ) {
			$headline = get_bloginfo( 'name' );
			echo '<h1 class="headline">' . esc_html( $headline ) . '</h1>';
		}
	}
}

/**
 * Function for rendering site sub-heading.
 */
function kafal_site_subheading() {
	$subheading = '';
	if ( is_single() || is_page() ) {
		?>
		<span class="meta posted-on"><?php kafal_posted_on();?></span>
		<?php
	} else if ( is_archive() ) {
		the_archive_description( '<hr class="small archive"><span class="subheading archive">', '</span>' );
	} else {
		if ( is_home() || is_search() || is_404() ) {
			$subheading = get_bloginfo( 'description' );
		}
		if( $subheading != '' )
			echo '<hr class="small"><span class="subheading">' . esc_html( $subheading ) . '</span>';
	}
}

/**
 * Function for parallex image.
 */
function kafal_parallex() {
	$header_image = '';
	if ( is_single() || is_page() ) {
		global $post;
		$thumb_url = get_the_post_thumbnail_url( $post->ID );
		if ( $thumb_url ) {
			$header_image = $thumb_url;
		} else {
			$header_image = get_header_image();
		}
	} else {
		$header_image = get_header_image();
	}
	$header_color = get_theme_mod( 'kafal_header_color', '#696969' );
	echo '<style type="text/css">
#masthead {
	background: transparent url(' . esc_url( $header_image ) . ') center center no-repeat fixed;
	background-color: ' . esc_attr( $header_color ) . ';
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	background-position: center 0px;
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' . esc_url( $header_image ) . '",sizingMethod="scale");
	-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' . esc_url( $header_image ) . '",sizingMethod="scale")";
	}
</style>';
}
add_action( 'wp_head', 'kafal_parallex' );
/**
 * Function for rendering titles of the page.
 */
function kafal_header_title() {
	if ( is_single() || is_page() ) {
		$classes = 'post-heading';
	} else {
		$classes = 'site-heading';
	}
	?>
	<div <?php post_class( "$classes" );?>>
	<?php
		kafal_site_heading();
		kafal_site_subheading();
	?>
	</div>
	<?php
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/walker-nav.php';

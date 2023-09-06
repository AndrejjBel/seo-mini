<?php
/**
 * Plugin Name: Seo mini
 * Plugin URI: https://creatsites.ru
 * Description: Meta tags minimum.
 * Author: Andrey Belopolskiy
 * Version: 1.01
 * Author URI: https://creatsites.ru
 * Text Domain: seo-mini
 * Domain Path: /languages
 */

define( 'SEO_MINI_PATH', dirname( __FILE__ ) );

require_once SEO_MINI_PATH . '/inc/settings.php';
require_once SEO_MINI_PATH . '/inc/kama-post-meta-box.php';
require_once SEO_MINI_PATH . '/inc/meta-box.php';
require_once SEO_MINI_PATH . '/inc/kama_excerpt.php';

// Adds a main styles and scripts for admin panel.
add_action( 'admin_enqueue_scripts', function(){
    wp_enqueue_style('seo-mini-admin', plugin_dir_url( __FILE__ ) . '/css/admin.css',	array(),
        filemtime( SEO_MINI_PATH . '/css/admin.css' )
    );
}, 99 );

// add_filter( 'wp_robots', 'seo_mini_robots_filter' );
function seo_mini_robots_filter( $robots ){
    $robots['max-snippet'] = '-1';
    $robots['max-video-preview'] = '-1';
    $robots['follow'] = true;
    $robots['index'] = true;
	return $robots;
}

add_filter( 'document_title', 'seo_mini_modify_document_title' );
function seo_mini_modify_document_title( $title ) {
    global $post;
    if ( is_front_page() ) {
        return $post->seo_mini_title ? $post->seo_mini_title : $title;
    } elseif ( is_singular('post') ) {
        $title_blog_end = seo_mini_get_options('title_blog_end');
        $title_blog_ends = $title_blog_end  ? $title_blog_end : get_bloginfo('name');
        $delimiter = seo_mini_get_options('delimiter');
        $delimiters = $delimiter  ? ' ' . $delimiter . ' ' : ' &mdash; ';
        return $post->seo_mini_title ? $post->seo_mini_title.$delimiters.$title_blog_ends : $post->post_title.$delimiters.$title_blog_ends;
    } else {
        $title_page_end = seo_mini_get_options('title_page_end');
        $title_page_ends = $title_page_end  ? $title_page_end : get_bloginfo('name');
        $delimiter = seo_mini_get_options('delimiter');
        $delimiters = $delimiter  ? ' ' . $delimiter . ' ' : ' &mdash; ';
        return $post->seo_mini_title ? $post->seo_mini_title.$delimiters.$title_page_ends : $post->post_title.$delimiters.$title_page_ends;
    }
}

// Выведем SEO мета-теги страниц
add_action( 'wp_head', 'seo_mini_head_seo_meta_tags', 1 );
function seo_mini_head_seo_meta_tags(){
    global $post;
    $maxchar = seo_mini_get_options('count_descr');
    $maxchars = $maxchar ? $maxchar : 160;
    $type_author = seo_mini_get_options('type_author');
    $type_authors  = $type_author  ? $type_author  : 'Person';
    $author_name = seo_mini_get_options('author_name');
    $author = get_user_by('ID', $post->post_author);
    $author_names  = $author_name  ? $author_name  : $author->display_name;
    if ( is_front_page() ) {
        $seo_title = $post->seo_mini_title ? $post->seo_mini_title : get_bloginfo('name');
        $seo_description = $post->seo_mini_description ? $post->seo_mini_description : kama_excerpt_seo_mini( [ 'maxchar'=>$maxchar, 'autop'=>false, 'sanitize_callback' =>'sanitize_text_field', $post->post_content ] );
    } elseif ( is_home() ) {
        $title_blog_end = seo_mini_get_options('title_blog_end');
        $title_blog_ends = $title_blog_end  ? $title_blog_end : get_bloginfo('name');
        $delimiter = seo_mini_get_options('delimiter');
        $delimiters = $delimiter  ? ' ' . $delimiter . ' ' : ' - ';
        $seo_title = $post->seo_mini_title ? $post->seo_mini_title.$delimiters.$title_blog_ends : 'Блог' .$delimiters.$title_blog_ends;
        $seo_description = 'Качественные программные решения, разработка программного обеспечения, тестирование программного обеспечения';
    } elseif ( is_singular('post') ) {
        $title_blog_end = seo_mini_get_options('title_blog_end');
        $title_blog_ends = $title_blog_end  ? $title_blog_end : get_bloginfo('name');
        $delimiter = seo_mini_get_options('delimiter');
        $delimiters = $delimiter  ? ' ' . $delimiter . ' ' : ' - ';
        $seo_title = $post->seo_mini_title ? $post->seo_mini_title.$delimiters.$title_blog_ends : $post->post_title.$delimiters.$title_blog_ends;
        $seo_description = $post->seo_mini_description ? $post->seo_mini_description : kama_excerpt_seo_mini( [ 'maxchar'=>$maxchar, 'autop'=>false, 'sanitize_callback' =>'sanitize_text_field', $post->post_content ] );
    } else {
        $title_page_end = seo_mini_get_options('title_page_end');
        $title_page_ends = $title_page_end  ? $title_page_end : get_bloginfo('name');
        $delimiter = seo_mini_get_options('delimiter');
        $delimiters = $delimiter  ? ' ' . $delimiter . ' ' : ' - ';
        $seo_title = $post->seo_mini_title ? $post->seo_mini_title.$delimiters.$title_page_ends : $post->post_title.$delimiters.$title_page_ends;
        $seo_description = $post->seo_mini_description ? $post->seo_mini_description : kama_excerpt_seo_mini( [ 'maxchar'=>$maxchar, 'autop'=>false, 'sanitize_callback' =>'sanitize_text_field', $post->post_content ] );
    }

    echo "\n" . '<!-- Seo mini -->' . "\n";
    echo '<meta name="description" content="' . $seo_description . '" />' . "\n";
    if ( $post->seo_mini_keywords ) {
        echo '<meta name="keywords" content="' . $post->seo_mini_keywords . '" />' . "\n";
    }
    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:title" content="' . $seo_title . '" />' . "\n";
    echo '<meta property="og:description" content="' . $seo_description . '" />' . "\n";
    echo '<meta property="og:url" content="' . get_bloginfo('url') . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />' . "\n";
    echo '<meta property="og:updated_time" content="' . get_the_modified_date('c') . '" />' . "\n";
    echo '<meta property="article:published_time" content="' . get_the_date('c') . '" />' . "\n";
    echo '<meta property="article:modified_time" content="' . get_the_modified_date('c') . '" />' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . $seo_title . '" />' . "\n";
//     echo '<script type="application/ld+json">
// {
//   "@context": "https://schema.org",
//   "@type": "NewsArticle",
//   "mainEntityOfPage": {
//     "@type": "WebPage",
//     "@id": "' . get_permalink($post->ID) . '"
//   },
//   "headline": "' . $seo_title . '",
//   "description": "' . $seo_description . '",
//   "image": "www",
//   "author": {
//     "@type": "Organization",
//     "name": "' . get_bloginfo('name') . '",
//     "url": "' . get_bloginfo('url') . '"
//   },
//   "publisher": {
//     "@type": "' . $type_authors . '",
//     "name": "' . $author_names . '",
//     "logo": {
//       "@type": "ImageObject",
//       "url": ""
//     }
//   },
//   "datePublished": "' . get_the_date('c') . '",
//   "dateModified": "' . get_the_modified_date('c') . '"
// }
// </script>' . "\n";
    echo '<!-- Seo mini -->' . "\n" . "\n";
}

add_filter( 'the_content', 'seo_mini_wp_posts_nofollow' );
function seo_mini_wp_posts_nofollow( $content ){
    // $content = wp_unslash( wp_rel_nofollow( $content ) );
	// $content = str_replace( 'rel="noopener nofollow"', 'target="_blank" rel="nofollow noindex noopener"', $content );
	// return $content;
    return stripslashes( wp_rel_nofollow( $content ) );
}

add_action( 'do_robotstxt', 'seo_mini_robots_txt' );
function seo_mini_robots_txt(){
    $robots = seo_mini_get_options('robots');
    if ( $robots ) {
        echo $robots;
    	die;
    }
}

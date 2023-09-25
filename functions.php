<?php 
  // Details what dependencies to load 
  function university_files() {
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    //leave out "http/s:" info from link 
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style( 'university_main_styles', get_theme_file_uri( '/build/style-index.css' ) );
    wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( '/build/index.css' ) );
  }
  // Tell WP to load dependencies on load 
  add_action('wp_enqueue_scripts', 'university_files'); 

  function university_features() {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support( 'title-tag'); 
    //* add support for post featured images
    add_theme_support( 'post-thumbnails' );
    //* add additional image crop size
    //will only apply by default- use "Regenerate Thumbnails" Plugin 
    add_image_size( 'professorLandscape', 400, 260, true );
    add_image_size( 'professorPortrait', 480, 650, true );
    add_image_size( 'pageBanner', 1500, 350, true );
  }
  // Tell WP to add Title tag to header - can change default in Settings > General
  add_action('after_setup_theme', 'university_features');

  function university_adjust_queries($query) {
    //* 'is_main_query' will make sure only default queries are changed
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', -1);
    }
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
      $today = date('Ymd');
      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        array( //Only Show Future events
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        )
      ));
    }
  }
  add_action( 'pre_get_posts', 'university_adjust_queries' );
?>
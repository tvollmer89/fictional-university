<?php

/**
 * Title: Single Program
 * Slug:  fictional-university-theme/single-program
 * Categories: Programs
 * Description: Template for single program page.
 */
?>
<?php
get_header();
while (have_posts()) {
  the_post();
  pageBanner();
?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <?php
        // * get_post_type_archive_link( [post-type] );
        ?>
        <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span>
      </p>
    </div>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
    <?php
    $relatedProfessors = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'professor',
      'orderby' => 'title',
      'order' => 'ASC',
      'meta_query' => array(
        // find only events with similar related programs 
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($relatedProfessors->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Professors</h2>';
      echo '<ul class="professor-cards">';
      while ($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post(); ?>
        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink(); ?>">
            <img src="<?php the_post_thumbnail_url('professorLandscape') ?>" alt="" class="professor-card__image">
            <span class="professor-card__name"><?php the_title(); ?></span>
          </a>
        </li>
    <?php }
      echo '</ul>';
    }

    //* We need to reset the global post object after using WP_Query so we can call the events 
    wp_reset_postdata();

    $today = date('Ymd');
    $homepageEvents = new WP_Query(array(
      'posts_per_page' => 2,
      'post_type' => 'event',
      'meta_key' => 'event_date', //Pull Custom Field Data
      'orderby' => 'meta_vale_num',
      'order' => 'ASC',
      'meta_query' => array(
        array( //Only Show Future events
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),
        // find only events with similar related programs 
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($homepageEvents->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
      while ($homepageEvents->have_posts()) {
        $homepageEvents->the_post();
        get_template_part('template-parts/content-event');
      }
    } ?>
  </div>
<?php }
get_footer();
?>
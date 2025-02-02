<?php
/* Template Name: CV Page */
get_header(); ?>

<main class="cv-page-wrapper">

    <?php
    // Fetch all CV posts
    $cv_query = new WP_Query(array(
        'post_type'      => 'cv_entry',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value',
        'meta_key'       => 'date', // Assuming 'date' is the ACF field for sorting
        'order'          => 'DESC',
    ));
    
    if ($cv_query->have_posts()) :
     

      // Create an array to group entries by 'type'
      $cv_sections = [];
      
      while ($cv_query->have_posts()) : $cv_query->the_post();
            $type = get_field('type'); // Get ACF 'type' field
            echo '<pre>';
            print_r(get_fields(get_the_ID())); // Dumps all ACF fields for debugging
            echo '</pre>';
            echo "<script>console.log(" . json_encode($type) . ");</script>";
            if ($type) {
                $cv_sections[$type][] = [
                    'date'     => get_field('date'),
                    'title'    => get_the_title(),
                    'location' => get_field('location'),
                    'description' => get_field('description'),
                    'link' => get_field('link'),
                ];
            }
        endwhile;
        wp_reset_postdata();
        
        // Loop through the grouped CV sections
        foreach ($cv_sections as $section_name => $entries) :
    ?>

    <h2 class="wp-block-heading"><?php echo esc_html($section_name); ?></h2>
    <div class="list-line"></div>
    
    <ul class="cv-list-wrapper">
        <?php foreach ($entries as $entry) : ?>
            <li>
                <span class="cv-date"><?php echo esc_html($entry['date']); ?>:</span>
                <span class="cv-list-name"><?php echo esc_html($entry['title']); ?></span>.
                <span class="cv-list-location"><?php echo esc_html($entry['location']); ?></span>.
                <span class="cv-list-description"><?php echo esc_html($entry['description']); ?></span>.
                <span class="cv-list-link"><?php echo esc_html($entry['link']); ?></span>.
            </li>
        <?php endforeach; ?>
    </ul>

    <?php endforeach; ?>

    <?php else : ?>
        <p>No CV entries found.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>

<section id="pageid-post<?php the_ID(); ?>" class="entry-content inner">

  <?php get_template_part( 'entry', ( is_front_page() || is_home() || is_front_page() && is_home() || is_archive() || is_search() ? 'summary' : 'content' ) ); ?>

</section>
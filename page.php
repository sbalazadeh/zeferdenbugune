<?php get_header(); ?>

<!-- Card -->
<div class="container content-space-t-lg-4 content-space-t-md-3 content-space-t-2 content-space-b-1 px-sm-6 px-2 header-shape" style="background-color:#F1F1F2;">
  <div class="mx-lg-auto">
  <?php
    while ( have_posts() ) :
        the_post();
    ?>
    <!-- Card -->
    <div class="card card-ghost px-2 px-sm-7">

          <h3 class="card-text pb-6 divider-start"><?php the_title(); ?></h3>
          <p class="card-text text-dark"><?php the_content(); ?></p>

    </div>
    <!-- End Card -->
  </div>
  <?php endwhile; // end of the loop. ?>
</div>
<!-- End Card -->

<?php get_footer(); ?>
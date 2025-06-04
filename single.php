<?php get_header(); ?>

<!-- Card -->
<div class="container content-space-t-lg-5 content-space-t-md-3 content-space-t-2 content-space-b-1 px-sm-6 px-2 header-shape" style="background-color:#F1F1F2;">
  <div class="mx-lg-auto">
  <?php
    while ( have_posts() ) :
        the_post();
    ?>
    <!-- Card -->
    <div class="card card-ghost px-2 px-sm-7">
      <h2 class="card-title mb-4" style="font-weight: 400;"><?php $catID = get_the_category(); echo category_description( $catID[0] );?></h2>

      <!-- Media -->
      <div class="d-flex mb-5">
        <div class="flex-shrink-0 text-center">
            <div class="p-3 btn-arxa">
            <p class="mb-0" style="font-size: 3rem;line-height: 3rem;"><?php echo get_the_date('j') ?></p>
            <p class="text-uppercase mb-0" style="font-size: 0.8rem;"><?php echo get_the_date('F') ?></p>
            </div>
        </div>

        <div class="flex-grow-1 ms-3">
          <h3 class="card-text text-dark mb-1"><?php the_title(); ?></h3>
          <p class="card-text text-dark"><?php the_content(); ?></p>
        </div>
      </div>
      <!-- End Media -->
    </div>
    <!-- End Card -->
  </div>
  <?php endwhile; // end of the loop. ?>
</div>
<!-- End Card -->

<?php get_footer(); ?>
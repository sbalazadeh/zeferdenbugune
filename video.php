<?php /* Template Name: Video */ ?>
<?php get_header(); ?>

<!-- Card -->
<div class="container content-space-t-lg-4 content-space-t-md-3 content-space-t-2 content-space-b-1 px-sm-6 px-2 header-shape" style="background-color:#F1F1F2;">
  <div class="mx-lg-auto">
    <?php
        while ( have_posts() ) :
            the_post();
    ?>
    <h3 class="card-text text-aciq pb-6 divider-start px-2 px-sm-7"><?php the_title(); ?></h3>
    <?php endwhile; // end of the loop. ?>

    <!-- Gallery -->
    <div class="row pt-4 px-2 px-sm-7">
        <?php $the_query = new WP_Query( 'cat=229&posts_per_page=50&order=desc' ); ?>
        <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
        <div class="col-sm-6 col-lg-4 mb-4">

                <!-- Fancybox -->
                <a data-fslightbox="youtube-video" href="<?php the_excerpt(); ?>" class="card bg-img-start rounded-2 py-10 px-5" style="background-image: url(<?php the_post_thumbnail_url(); ?>);">
                    <div class="video-player-btn" role="button">
                        <span class="d-flex justify-content-start align-items-start">
                            <span class="video-player-icon shadow-sm">
                            <i class="bi-play-fill"></i>
                            </span>
                        </span>
                    </div>
                </a>
                <!-- End Fancybox -->

            <p class="mt-2 mb-0"><?php the_title(); ?></p>
        </div>
        <?php endwhile; wp_reset_postdata();?>
    </div>

  </div>
</div>
<!-- End Card -->

<?php get_footer(); ?>
<?php get_header(); ?>

<div class="container content-space-t-lg-4 content-space-t-md-3 content-space-t-2 content-space-b-1 px-sm-6 px-3 header-shape" style="background-color:#F1F1F2;">
  <div class="mx-lg-auto">
    <!-- Card -->
    <div class="card card-ghost px-0 px-sm-7">
      <div class="row mb-6 justify-content-md-start align-items-md-center">
        <div class="col-sm-auto">
          <span class="text-dark" style="font-size: 1.3rem;"><?php pll_e('BÜLLETEN SEÇ'); ?></span>
        </div>
        <div class="col-sm-auto col-6">
          <?php wp_dropdown_categories('include=array( 3,4,5,295,373,383,659 )&class=form-select form-select-sm'); ?>
          <script>
            document.getElementById('cat').onchange = function(){
            // if value is category id
              if( this.value !== '-1' ){
                window.location='/?cat='+this.value
              }
            }
          </script>
        </div>
        <div class="col-sm-auto col-6 px-0">
          
            <a class="btn btn-primary btn-sm" href="<?php echo get_field('bulleten_pdf','category_'.get_queried_object()->term_id); ?>" rel="noopener" target="_blank"> <i class="bi bi-file-earmark-pdf"></i> <?php pll_e('PDF YÜKLƏ'); ?></a>

        </div>
        <div class="col-sm py-2 d-none d-lg-inline-block btn-arxa">
        &nbsp;
        </div>
      </div>

    <?php  if ( have_posts() ) : ?>

      <h2 class="card-title my-6 text-uppercase" style="font-weight: 400;"><?php single_cat_title( '', true ); ?> / <?php the_archive_description( ); ?></h2>
    <?php endif; ?>
        <div class="row">
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="col-lg-6 mb-3">
                <!-- Media -->
                <a href="<?php the_permalink() ?>" class="d-flex mb-5 card-transition">
                    <div class="flex-shrink-0 text-center">
                        <div class="py-3 px-1 btn-arxa" style="width:6rem;">
                        <p class="mb-0" style="font-size: 3rem;line-height: 2.5rem;"><?php echo get_the_date('j') ?></p>
                        <p class="text-uppercase mb-0" style="font-size: 0.78rem;"><?php echo get_the_date('F') ?></p>
                        </div>
                    </div>

                    <div class="flex-grow-1 ms-3">
                    <h5 class="card-text text-dark mb-1"><?php the_title(); ?></h5>
                    <p class="card-text text-dark small"><?php the_excerpt(); ?></p>
                    </div>
                </a>
                <!-- End Media -->
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- End Card -->
  </div>
</div>

<?php get_footer(); ?>
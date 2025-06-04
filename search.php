<?php get_header(); ?>


<div class="container pt-6">
  <div class="row mx-n2 mx-lg-n3">
   

    <div class="col-12">

    <?php while ( have_posts() ) : the_post(); 
    $xeber=$post;
      $cat=get_the_category($xeber->ID)[0];
      ?>
       <a class="card pb-4" href="<?php echo get_permalink($xeber->ID); ?>" style="background-color:transparent!important; box-shadow: none!important;">
        <div class="card-post">
        <small class="text-muted ps-6"><?php echo $cat->name; ?></small> 
        <span class="h4 card-header-title ps-6"><?php echo $xeber->post_title;?></span>
        </div>
      </a>
    <?php endwhile;?>
    </div>
    
  </div>
</div>


<?php get_footer(); ?>

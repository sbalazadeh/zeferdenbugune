<?php get_header(); ?>
<?php
add_action('wp_footer', function () {
  if (!is_page('xəritə') && !is_front_page()) return; // lazım olduqda səhifə ID və ya slug ilə dəyiş

  $terms = get_terms([
    'taxonomy' => 'location',
    'hide_empty' => false,
  ]);

  $locations = [];

  foreach ($terms as $term) {
    $lang = function_exists('pll_get_term_language') ? pll_get_term_language($term->term_id, 'slug') : 'az';
    $slug = $term->slug;
    $locations[$slug][$lang] = [
      'name' => $term->name,
      'desc' => term_description($term, 'location'),
      'url'  => get_term_link($term),
    ];
  }

  // Current language-i JavaScript-ə göndər
  $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'az';
  
  echo '<script>';
  echo 'const locationMap = ' . json_encode($locations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ';';
  echo 'const currentLanguage = "' . $current_lang . '";';
  echo '</script>';
}); 
?>

<main class="relative z-20 md:py-[30px] pb-4 sm:pt-0 flex flex-col overflow-hidden">
  <div class="map-container relative">
    <?php
      $svg_path = get_template_directory() . '/src/images/xarita-ck.svg';
      $svg_content = file_get_contents($svg_path);
      $svg_content = preg_replace('/<svg([^>]+)>/', '<svg$1 class="w-full h-full md:w-auto md:h-auto object-cover scale-[1.8] md:scale-[1.1]">', $svg_content);
      echo $svg_content;
    ?>
  </div>
 
  <div class="relative md:absolute right-0 top-[30%] md:max-w-[35%] lg:max-w-[30%] w-full md:text-right text-left text-[#2C2E35]">
    <h1 class="text-2xl font-bold" id="region-title">Regionun adı</h1>
    <p class="text-sm lg:text-base" id="region-description">Region təsviri...</p>
    <button class="bg-[#667B88] text-white w-full md:w-fit font-semibold mt-6 py-2 px-5">DAXİL OL</button>
  </div>
</main>

<?php get_footer(); ?>
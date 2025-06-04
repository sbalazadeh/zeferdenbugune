<?php get_header(); ?>

<?php
// Current location term məlumatlarını alırıq
$current_term = get_queried_object();
$location_name = $current_term->name;

// Debug: Current term məlumatını göstər
echo '<script>console.log("Current term:", ' . json_encode([
    'term_id' => $current_term->term_id,
    'name' => $current_term->name,
    'slug' => $current_term->slug,
    'taxonomy' => $current_term->taxonomy,
    'language' => function_exists('pll_get_term_language') ? pll_get_term_language($current_term->term_id) : 'unknown'
]) . ');</script>';

// Current language
$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'az';

// Filter parametrlərini alırıq
$selected_city = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : '';
$selected_day = isset($_GET['day']) ? sanitize_text_field($_GET['day']) : '';
$selected_month = isset($_GET['month']) ? sanitize_text_field($_GET['month']) : '';
$selected_year = isset($_GET['year']) ? sanitize_text_field($_GET['year']) : '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Debug: Filter values göstər
echo '<script>console.log("Filter values:", ' . json_encode([
    'selected_city' => $selected_city,
    'selected_day' => $selected_day,
    'selected_month' => $selected_month,
    'selected_year' => $selected_year,
    'paged' => $paged
]) . ');</script>';

// Multilingual ay adları
$month_names = array();
switch ($current_lang) {
    case 'en':
        $month_names = array(
            'january' => 'January', 'february' => 'February', 'march' => 'March', 'april' => 'April',
            'may' => 'May', 'june' => 'June', 'july' => 'July', 'august' => 'August',
            'september' => 'September', 'october' => 'October', 'november' => 'November', 'december' => 'December'
        );
        break;
    case 'ru':
        $month_names = array(
            'january' => 'Январь', 'february' => 'Февраль', 'march' => 'Март', 'april' => 'Апрель',
            'may' => 'Май', 'june' => 'Июнь', 'july' => 'Июль', 'august' => 'Август',
            'september' => 'Сентябрь', 'october' => 'Октябрь', 'november' => 'Ноябрь', 'december' => 'Декабрь'
        );
        break;
    case 'es':
        $month_names = array(
            'january' => 'Enero', 'february' => 'Febrero', 'march' => 'Marzo', 'april' => 'Abril',
            'may' => 'Mayo', 'june' => 'Junio', 'july' => 'Julio', 'august' => 'Agosto',
            'september' => 'Septiembre', 'october' => 'Octubre', 'november' => 'Noviembre', 'december' => 'Diciembre'
        );
        break;
    default: // az
        $month_names = array(
            'january' => 'Yanvar', 'february' => 'Fevral', 'march' => 'Mart', 'april' => 'Aprel',
            'may' => 'May', 'june' => 'İyun', 'july' => 'İyul', 'august' => 'Avqust',
            'september' => 'Sentyabr', 'october' => 'Oktyabr', 'november' => 'Noyabr', 'december' => 'Dekabr'
        );
}

// Month number to key mapping
$month_key_mapping = array(
    1 => 'january', 2 => 'february', 3 => 'march', 4 => 'april',
    5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august',
    9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december'
);

// Current location üçün mövcud tarixləri hesablayırıq
$available_dates_query = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'tax_query' => array(
        array(
            'taxonomy' => 'location',
            'field'    => 'term_id',
            'terms'    => $current_term->term_id,
        ),
    ),
);

$all_posts_for_location = new WP_Query($available_dates_query);
$available_years = array();
$available_months_by_year = array();
$available_days_by_month = array();
$sample_dates = array(); // Debug dates üçün array

// Debug: Check if posts found
if ($all_posts_for_location->have_posts()) {
    $counter = 0;
    foreach ($all_posts_for_location->posts as $post_id) {
        $year = intval(get_post_time('Y', false, $post_id));
        $month = intval(get_post_time('n', false, $post_id)); // 1-12
        $day = intval(get_post_time('j', false, $post_id)); // 1-31
        
        // Debug üçün ilk 5 post tarixini saxla
        if ($counter < 5) {
            $sample_dates[] = array(
                'post_id' => $post_id,
                'date' => get_post_time('Y-m-d', false, $post_id),
                'year' => $year,
                'month' => $month,
                'day' => $day
            );
            $counter++;
        }
        
        // Years collect et
        if (!in_array($year, $available_years)) {
            $available_years[] = $year;
        }
        
        // Months by year collect et
        if (!isset($available_months_by_year[$year])) {
            $available_months_by_year[$year] = array();
        }
        if (!in_array($month, $available_months_by_year[$year])) {
            $available_months_by_year[$year][] = $month;
        }
        
        // Days by month collect et
        $month_year_key = $year . '-' . $month;
        if (!isset($available_days_by_month[$month_year_key])) {
            $available_days_by_month[$month_year_key] = array();
        }
        if (!in_array($day, $available_days_by_month[$month_year_key])) {
            $available_days_by_month[$month_year_key][] = $day;
        }
    }
    echo '<script>console.log("Sample post dates:", ' . json_encode($sample_dates) . ');</script>';
} else {
    // Default fallback data if no posts found
    $current_year = date('Y');
    $available_years = array($current_year);
    $available_months_by_year = array($current_year => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12));
    $available_days_by_month = array();
    
    // Generate days for each month
    for ($m = 1; $m <= 12; $m++) {
        $key = $current_year . '-' . $m;
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $m, $current_year);
        $available_days_by_month[$key] = range(1, $days_in_month);
    }
}

// Sort arrays
sort($available_years);
foreach ($available_months_by_year as $year => $months) {
    sort($available_months_by_year[$year]);
}
foreach ($available_days_by_month as $key => $days) {
    sort($available_days_by_month[$key]);
}

wp_reset_postdata();

// WP_Query args
$query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 10,
    'paged' => $paged,
    'tax_query' => array(
        array(
            'taxonomy' => 'location',
            'field'    => 'term_id',
            'terms'    => $current_term->term_id,
        ),
    ),
);

// Tarix filterləri əlavə edirik - WordPress date_query formatında
$date_query_items = array();
$has_date_filter = false;

if (!empty($selected_year)) {
    $date_query_items['year'] = intval($selected_year);
    $has_date_filter = true;
}

if (!empty($selected_month)) {
    $month_number = array(
        'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
        'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
        'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
    );
    if (isset($month_number[$selected_month])) {
        $date_query_items['month'] = $month_number[$selected_month];
        $has_date_filter = true;
    }
}

if (!empty($selected_day)) {
    $date_query_items['day'] = intval($selected_day);
    $has_date_filter = true;
}

// Date query varsa əlavə et
if ($has_date_filter && !empty($date_query_items)) {
    $query_args['date_query'] = array(
        'relation' => 'AND',
        $date_query_items
    );
    
    // Debug: Date query structure
    echo '<script>console.log("Date query:", ' . json_encode($query_args['date_query']) . ');</script>';
}

// Debug: Query args-ı göstər
echo '<script>console.log("Query args:", ' . json_encode($query_args) . ');</script>';

$location_posts = new WP_Query($query_args);

// Debug: Tapılan post sayını göstər
echo '<script>console.log("Found posts:", ' . $location_posts->found_posts . ');</script>';
if ($location_posts->found_posts > 0) {
    echo '<script>console.log("SQL query:", "' . str_replace('"', '\"', $location_posts->request) . '");</script>';
}

// Digər location-ları dropdown üçün alırıq
$all_locations = get_terms(['taxonomy' => 'location', 'hide_empty' => false]);
$filtered_locations = array();
if (function_exists('pll_get_term_language')) {
    foreach ($all_locations as $location) {
        $term_lang = pll_get_term_language($location->term_id, 'slug');
        if ($term_lang === $current_lang) {
            $filtered_locations[] = $location;
        }
    }
} else {
    $filtered_locations = $all_locations;
}
?>

<!-- Main Content Start -->
<main class="my-10 pt-[80px] md:pt-[150px]">
  <!-- Filters Start -->
  <form method="GET" action="<?php echo get_term_link($current_term); ?>" class="flex flex-col lg:flex-row lg:items-center gap-2 text-xl">
    <p class="flex-[0.3] text-[#2C2E35]"><?php echo pll__('XƏBƏR SEÇİMİ'); ?></p>

    <div class="flex flex-col md:flex-row items-center gap-2 flex-1">
      <div class="custom-select flex-1 w-full">
        <select
          name="city"
          id="city"
          class="w-full bg-white py-2.5 px-4 outline-none"
        >
          <option value=""><?php echo pll__('Şəhər Seç'); ?></option>
          <?php foreach ($filtered_locations as $location): ?>
            <option value="<?php echo esc_attr($location->slug); ?>" 
                    <?php selected($current_term->slug, $location->slug); ?>>
              <?php echo esc_html($location->name); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="custom-select flex-1 w-full">
        <select
          name="day"
          id="day"
          class="w-full bg-white py-2.5 px-4 outline-none"
        >
          <option value=""><?php echo pll__('Gün'); ?></option>
          <!-- Dynamic days will be populated by JavaScript -->
        </select>
      </div>

      <div class="custom-select flex-1 w-full">
        <select
          name="month"
          id="month"
          class="w-full bg-white py-2.5 px-4 outline-none"
        >
          <option value=""><?php echo pll__('Ay'); ?></option>
          <!-- Dynamic months will be populated by JavaScript -->
        </select>
      </div>

      <div class="custom-select flex-1 w-full">
        <select
          name="year"
          id="year"
          class="w-full bg-white py-2.5 px-4 outline-none"
        >
          <option value=""><?php echo pll__('İl'); ?></option>
          <?php foreach ($available_years as $year): ?>
            <option value="<?php echo $year; ?>" <?php selected($selected_year, $year); ?>><?php echo $year; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <button type="submit" class="py-2 bg-[#667B88] flex-[0.5] text-white"><?php echo pll__('KEÇİD'); ?></button>
  </form>
  <!-- Filters End -->

  <div class="mt-15">
    <h1 class="text-[#2C2E35] text-2xl"><?php echo strtoupper(esc_html($location_name)); ?></h1>

    <div class="space-y-4">
      <?php if ($location_posts->have_posts()): ?>
        <?php while ($location_posts->have_posts()): $location_posts->the_post(); ?>
          <div class="bg-white flex flex-col md:flex-row md:gap-10 gap-2 mt-4 shadow-xs">
            <div class="flex h-fit">
              <div class="bg-[#D2E6DB] flex flex-col gap-0 items-center py-3 px-5">
                <p class="font-medium text-3xl"><?php echo get_the_date('d'); ?></p>
                <span class="text-[10px] font-light"><?php echo strtoupper(get_the_date('M')); ?></span>
              </div>
              <div class="bg-[#486D6F] flex items-center p-3">
                <p class="text-2xl font-light text-white"><?php echo get_the_date('Y'); ?></p>
              </div>
            </div>

            <div class="pt-6 flex flex-col items-end gap-4">
              <p class="text-[#2C2E35] font-light tracking-wide pr-5 text-sm md:text-base">
                <?php 
                if (has_excerpt()) {
                    the_excerpt();
                } else {
                    echo wp_trim_words(get_the_content(), 50, '...');
                }
                ?>
              </p>

              <a href="<?php the_permalink(); ?>" class="bg-[#667B88] w-fit py-2 px-4 text-white"><?php echo pll__('ƏTRAFLI OXU'); ?></a>
            </div>
          </div>
        <?php endwhile; ?>
        
        <!-- Pagination -->
        <?php if ($location_posts->max_num_pages > 1): ?>
          <div class="flex justify-center mt-8">
            <?php
            echo paginate_links(array(
                'total' => $location_posts->max_num_pages,
                'current' => $paged,
                'format' => '?paged=%#%',
                'add_args' => array(
                    'year' => $selected_year,
                    'month' => $selected_month,
                    'day' => $selected_day
                ),
                'prev_text' => '‹',
                'next_text' => '›',
                'type' => 'list',
                'class' => 'pagination'
            ));
            ?>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="bg-white flex flex-col md:flex-row md:gap-10 gap-2 mt-4 shadow-xs">
          <div class="pt-6 pb-6 flex flex-col items-center gap-4 w-full">
            <p class="text-[#2C2E35] font-light tracking-wide text-center text-sm md:text-base">
              <?php echo pll__('Bu region və ya tarix üçün heç bir xəbər tapılmadı. Filtri dəyişərək yenidən cəhd edin.'); ?>
            </p>
            <a href="<?php echo get_term_link($current_term); ?>" class="bg-[#667B88] w-fit py-2 px-4 text-white">
              <?php echo pll__('BÜTÜN XƏBƏRLƏRI GÖSTƏR'); ?>
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>
<!-- Main Content End -->

<?php wp_reset_postdata(); ?>

<!-- JavaScript data-nı footer-də yerləşdiririk ki main.js-dən sonra yüklənsin -->
<?php 
add_action('wp_footer', function() use ($current_term, $available_years, $available_months_by_year, $available_days_by_month, $month_key_mapping, $month_names, $selected_year, $selected_month, $selected_day) {
?>
<script>
// Current location slug-unu və home URL-unu JavaScript üçün təmin edirik
document.body.dataset.currentLocation = '<?php echo esc_js($current_term->slug); ?>';
window.homeUrl = '<?php echo esc_url(home_url()); ?>';

// Available dates for dynamic dropdowns
window.availableDates = {
    years: <?php echo wp_json_encode($available_years); ?>,
    monthsByYear: <?php echo wp_json_encode($available_months_by_year); ?>,
    daysByMonth: <?php echo wp_json_encode($available_days_by_month); ?>,
    monthMapping: <?php echo wp_json_encode($month_key_mapping); ?>,
    monthNames: <?php echo wp_json_encode($month_names); ?>,
    selectedValues: {
        year: '<?php echo esc_js($selected_year); ?>',
        month: '<?php echo esc_js($selected_month); ?>',
        day: '<?php echo esc_js($selected_day); ?>'
    }
};

console.log('availableDates data loaded:', window.availableDates);

// Dynamic dropdowns-ı initialize et
if (typeof window.initializeDynamicDropdowns === 'function') {
    window.initializeDynamicDropdowns();
    console.log('Dynamic dropdowns initialized from footer');
}
</script>
<?php
}, 100); // Priority 100 - main.js-dən sonra yüklənsin
?>

<?php get_footer(); ?>
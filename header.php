<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php wp_title('|', true, 'right');?></title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/src/output.css?v=<?php echo filemtime(get_template_directory() . '/src/output.css'); ?>">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class('bg-[#FBFBFB] container'); ?>>
    <?php wp_body_open(); ?>

    <!-- Mobile Menu Start-->
    <div id="mobile-menu" class="h-screen w-screen fixed inset-0 bg-white z-[1000] flex flex-col p-5 gap-2 md:hidden">
      <!-- Close Button -->
      <button id="close-btn" class="absolute top-4 right-4 p-2">
        <!-- SVG same as original -->
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
          <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
        </svg>
      </button>
      <!-- Close Button -->

      <!-- SearchField -->
      <div class="flex items-center w-full mt-[50px]">
        <input type="text" name="search" id="search" class="bg-[#EBECEC] h-14 flex-1 outline-none pl-2" />
        <button class="bg-[#2C2E35] text-white size-14 grid place-items-center shrink-0">
          <!-- SVG search icon -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
          </svg>
        </button>
      </div>

            <!-- Navigation Links -->
      <div class="grid grid-cols-1 h-1/2 w-full mt-auto">
        <a href="#" class="bg-[#D2E6DB] text-[#2C2E35] font-bold px-4 py-2 flex items-center justify-center text-sm lg:text-base">XƏBƏRLƏR</a>
        <a href="#" class="bg-[#486D6F] text-white font-bold px-4 py-2 flex items-center justify-center text-sm lg:text-base">HAQQIMIZDA</a>
        <a href="#" class="bg-[#667B88] text-white font-bold px-4 py-2 flex items-center justify-center text-sm lg:text-base">VİDEOLAR</a>
      </div>

      <!-- Language Selector -->
      <div class="flex gap-2 mt-auto self-end">
        <?php
        $languages = pll_the_languages(['raw' => 1]);
        $current_lang = pll_current_language();
        foreach ($languages as $lang) {
          if ($lang['slug'] === $current_lang) continue;
        ?>
          <div>
            <a href="<?php echo esc_url($lang['url']); ?>"
              class="p-2 block bg-[#D8D9DA] text-sm lg:text-base hover:bg-[#486D6F] hover:text-white transition-all">
              <?php echo esc_html(strtoupper($lang['slug'])); ?>
            </a>
          </div>
        <?php } ?>
      </div>
    </div>
    <!-- Mobile Menu END-->

    <!-- Header Start -->
    <header class="relative">
      <div class="flex items-end justify-between bg-white z-[999] md:h-[140px] h-[100px]">
       <div class="flex items-end flex-1 h-full md:pl-10 pl-4">
         <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="lg:text-4xl md:text-2xl text-xl font-bold tracking-wide relative">
          <span>ZƏFƏRDƏN <br />BU GÜNƏ</span>
          <img src="<?php echo get_template_directory_uri(); ?>/src/images/xari-icon.svg" alt="" class="absolute md:top-9 top-7 md:left-1 lg:left-5 left-4 w-[40%] md:w-[70%] lg:w-[50%] z-[998]" />
        </a>
        <!-- Logo -->

        <!-- SearchField -->
        <div class="md:flex items-center flex-[0.5] ml-4 hidden">
          <input type="text" name="search" id="search" class="bg-[#EBECEC] h-10 flex-1 outline-none pl-2" />
          <button class="bg-[#2C2E35] text-white size-10 grid place-items-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
          </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 justify-center self-stretch hidden md:flex">
          <div class="grid grid-cols-3 gap-2 h-full w-full lg:w-4/5">
            <a href="#" class="bg-[#D2E6DB] text-[#2C2E35] font-bold px-4 py-4 flex items-end justify-center text-sm lg:text-base hover:opacity-75 transition-all duration-300">XƏBƏRLƏR</a>
            <a href="#" class="bg-[#486D6F] text-white font-bold px-4 py-4 flex items-end justify-center text-sm lg:text-base hover:opacity-75 transition-all duration-300">HAQQIMIZDA</a>
            <a href="#" class="bg-[#667B88] text-white font-bold px-4 py-4 flex items-end justify-center text-sm lg:text-base hover:opacity-75 transition-all duration-300">VİDEOLAR</a>
          </div>
        </div>
       </div>

        <!-- Language Selector -->
        <div class="md:flex flex-col  gap-0.5 h-full hidden">
          <?php foreach ($languages as $lang) {
            if ($lang['slug'] === $current_lang) continue;
          ?>
            <div class="w-full h-full">
              <a href="<?php echo esc_url($lang['url']); ?>"
                class="w-full h-full px-2 grid place-items-center block bg-[#D8D9DA] text-sm lg:text-base hover:bg-[#486D6F] hover:text-white transition-all">
                <?php echo esc_html(strtoupper($lang['slug'])); ?>
              </a>
            </div>
          <?php } ?>
        </div>

        <!-- Menu Button -->
        <button id="open-btn" class="md:hidden pr-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
          </svg>
        </button>
        <!-- Menu Buton -->
      </div>

      <div
          class="absolute top-full left-0 h-[80px] md:h-[165px] w-full bg-no-repeat bg-bottom z-50 overflow-hidden"
          style="background-image: url('<?php echo get_template_directory_uri(); ?>/src/images/elchin-kolge.svg'); background-size: 103%;"
        >
      </div>
    </header>
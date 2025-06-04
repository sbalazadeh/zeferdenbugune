<footer
  class="h-[120px] md:h-[200px] bg-no-repeat bg-top md:bg-bottom relative z-40"
  style="background-image: url('<?php echo get_template_directory_uri(); ?>/src/images/footerbg.svg'); background-size: 100%;"
>
  <p class="text-end tracking-wide text-white md:text-2xl text-xs md:pr-10 md:pt-6 pt-2 pr-4">
    ALL RIGHTS RESERVED 2025
  </p>
</footer>
<?php wp_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/src/main.js?v=<?php echo filemtime(get_template_directory() . '/src/main.js'); ?>"></script>
<style>
  /* SVG Map Styles */
  svg path[id^="qarabag-region"] {
    cursor: pointer;
    transition: fill 0.3s ease;
  }
  
  /* Pagination Styles */
  .pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .pagination .page-numbers {
    display: block;
    padding: 0.5rem 1rem;
    background: #667B88;
    color: white;
    text-decoration: none;
    transition: opacity 0.3s;
  }
  
  .pagination .page-numbers:hover {
    opacity: 0.8;
  }
  
  .pagination .current {
    background: #2C2E35;
  }
  
  .pagination .dots {
    background: transparent;
    color: #667B88;
  }
</style>

</body>
</html>
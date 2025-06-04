// Mobil menyu funksionallığı
document.addEventListener('DOMContentLoaded', () => {
  const mobileMenu = document.querySelector("#mobile-menu");
  const openBtn = document.querySelector("#open-btn");
  const closeBtn = document.querySelector("#close-btn");

  if (openBtn && closeBtn && mobileMenu) {
    openBtn.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      document.body.classList.add("no-scroll");
    });

    closeBtn.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      document.body.classList.remove("no-scroll");
    });
  }

  // ===========================
  // Location Filter Funksionallığı (taxonomy-location.php üçün)
  // ===========================
  
  // Dynamic dropdowns functionality
  function initializeDynamicDropdowns() {
    console.log('Initializing dynamic dropdowns...');
    
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const daySelect = document.getElementById('day');
    
    if (!yearSelect || !monthSelect || !daySelect) {
      console.log('Dropdowns not found - not on location page');
      return;
    }
    
    // Check if availableDates exists
    if (typeof window.availableDates === 'undefined') {
      console.log('availableDates not loaded yet');
      return;
    }
    
    const availableDates = window.availableDates;
    console.log('Available dates loaded:', availableDates);
    
    // Check if data is properly loaded
    if (!availableDates || !availableDates.monthsByYear || !availableDates.daysByMonth || !availableDates.monthMapping) {
      console.error('Available dates structure is incomplete');
      return;
    }
    
    // Initialize months and days based on current selections
    updateMonthOptions();
    updateDayOptions();
    
    // Year change handler
    yearSelect.addEventListener('change', function() {
      console.log('Year changed to:', this.value);
      updateMonthOptions();
      updateDayOptions();
    });
    
    // Month change handler  
    monthSelect.addEventListener('change', function() {
      console.log('Month changed to:', this.value);
      updateDayOptions();
    });
    
    function updateMonthOptions() {
      console.log('Updating month options...');
      const selectedYear = yearSelect.value;
      const targetSelectedMonth = (availableDates.selectedValues && availableDates.selectedValues.month) || monthSelect.value;
      
      console.log('Selected year:', selectedYear);
      console.log('Target month:', targetSelectedMonth);
      
      // Get first option text before clearing
      const firstOptionText = monthSelect.options[0] ? monthSelect.options[0].textContent : 'Ay';
      
      // Clear existing options except first
      monthSelect.innerHTML = '<option value="">' + firstOptionText + '</option>';
      
      if (selectedYear && availableDates.monthsByYear && availableDates.monthsByYear[selectedYear]) {
        const availableMonths = availableDates.monthsByYear[selectedYear];
        console.log('Available months for year ' + selectedYear + ':', availableMonths);
        
        if (Array.isArray(availableMonths)) {
          availableMonths.forEach(monthNumber => {
            const monthKey = availableDates.monthMapping[monthNumber];
            const monthName = availableDates.monthNames[monthKey];
            
            console.log('Adding month:', monthNumber, monthKey, monthName);
            
            if (monthKey && monthName) {
              const option = document.createElement('option');
              option.value = monthKey;
              option.textContent = monthName;
              
              if (monthKey === targetSelectedMonth) {
                option.selected = true;
                console.log('Selected month:', monthKey);
              }
              
              monthSelect.appendChild(option);
            }
          });
        }
      } else if (availableDates.monthsByYear) {
        console.log('No year selected, showing all months');
        // Show all available months from all years
        const allMonths = new Set();
        
        if (typeof availableDates.monthsByYear === 'object') {
          Object.values(availableDates.monthsByYear).forEach(months => {
            if (Array.isArray(months)) {
              months.forEach(month => allMonths.add(month));
            }
          });
        }
        
        console.log('All available months:', Array.from(allMonths));
        
        Array.from(allMonths).sort((a, b) => a - b).forEach(monthNumber => {
          const monthKey = availableDates.monthMapping[monthNumber];
          const monthName = availableDates.monthNames[monthKey];
          
          if (monthKey && monthName) {
            const option = document.createElement('option');
            option.value = monthKey;
            option.textContent = monthName;
            
            if (monthKey === targetSelectedMonth) {
              option.selected = true;
            }
            
            monthSelect.appendChild(option);
          }
        });
      }
      console.log('Month options updated, total options:', monthSelect.options.length);
    }
    
    function updateDayOptions() {
      console.log('Updating day options...');
      const selectedYear = yearSelect.value;
      const selectedMonth = monthSelect.value;
      const targetSelectedDay = (availableDates.selectedValues && availableDates.selectedValues.day) || daySelect.value;
      
      console.log('Selected year:', selectedYear, 'Selected month:', selectedMonth, 'Target day:', targetSelectedDay);
      
      // Get first option text before clearing
      const firstOptionText = daySelect.options[0] ? daySelect.options[0].textContent : 'Gün';
      
      // Clear existing options except first
      daySelect.innerHTML = '<option value="">' + firstOptionText + '</option>';
      
      if (selectedYear && selectedMonth && availableDates.daysByMonth) {
        // Convert month key to number
        const monthNumber = Object.keys(availableDates.monthMapping).find(
          key => availableDates.monthMapping[key] === selectedMonth
        );
        
        console.log('Month number for', selectedMonth, ':', monthNumber);
        
        const monthYearKey = selectedYear + '-' + monthNumber;
        console.log('Looking for days in:', monthYearKey);
        
        if (availableDates.daysByMonth[monthYearKey] && Array.isArray(availableDates.daysByMonth[monthYearKey])) {
          const availableDays = availableDates.daysByMonth[monthYearKey];
          console.log('Available days:', availableDays);
          
          availableDays.forEach(day => {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            
            if (day == targetSelectedDay) {
              option.selected = true;
            }
            
            daySelect.appendChild(option);
          });
        }
      } else if (selectedMonth && availableDates.daysByMonth) {
        console.log('Only month selected, showing all days for this month');
        // Show all days for selected month across all years
        const monthNumber = Object.keys(availableDates.monthMapping).find(
          key => availableDates.monthMapping[key] === selectedMonth
        );
        
        const allDays = new Set();
        Object.keys(availableDates.daysByMonth).forEach(key => {
          if (key.endsWith('-' + monthNumber) && Array.isArray(availableDates.daysByMonth[key])) {
            availableDates.daysByMonth[key].forEach(day => allDays.add(day));
          }
        });
        
        console.log('All days for month:', Array.from(allDays));
        
        Array.from(allDays).sort((a, b) => a - b).forEach(day => {
          const option = document.createElement('option');
          option.value = day;
          option.textContent = day;
          
          if (day == targetSelectedDay) {
            option.selected = true;
          }
          
          daySelect.appendChild(option);
        });
      }
      console.log('Day options updated, total options:', daySelect.options.length);
    }
  }
  
  // Make function globally available for footer script
  window.initializeDynamicDropdowns = initializeDynamicDropdowns;
  
  // Try to initialize immediately if data is already available
  if (typeof window.availableDates !== 'undefined') {
    console.log('availableDates already loaded, initializing immediately');
    initializeDynamicDropdowns();
  }
  
  // City dropdown functionality və form submission
  const citySelect = document.getElementById('city');
  const filterForm = document.querySelector('form[method="GET"]');
  
  if (citySelect) {
    citySelect.addEventListener('change', function() {
      // Current location slug-unu PHP-dən almaq üçün data attribute istifadə edərik
      const currentLocationSlug = document.body.dataset.currentLocation;
      
      if (this.value && this.value !== currentLocationSlug) {
        const form = this.closest('form');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        // Bütün form dəyərlərini toplayırıq
        for (let [key, value] of formData.entries()) {
          if (value && key !== 'city') {
            params.append(key, value);
          }
        }
        
        const queryString = params.toString();
        // WordPress home URL-unu window object-dən alırıq
        const homeUrl = window.homeUrl || window.location.origin;
        
        // Polylang URL strukturunu nəzərə al
        const currentPath = window.location.pathname;
        const langPattern = /^\/([a-z]{2})\//;
        const langMatch = currentPath.match(langPattern);
        let newUrl;
        
        if (langMatch) {
          // Dil prefixi varsa (məs: /en/, /ru/, /es/)
          newUrl = homeUrl + '/' + langMatch[1] + '/location/' + this.value + '/' + (queryString ? '?' + queryString : '');
        } else {
          // Default dil (az) üçün
          newUrl = homeUrl + '/location/' + this.value + '/' + (queryString ? '?' + queryString : '');
        }
        
        window.location.href = newUrl;
      }
    });
  }
  
  // Form submit handler - boş parametrləri URL-dən silmək üçün
  if (filterForm) {
    filterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const params = new URLSearchParams();
      
      // Yalnız dolu olan parametrləri əlavə et
      for (let [key, value] of formData.entries()) {
        if (value && key !== 'city') { // city-ni URL-ə əlavə etmirik çünki o taxonomy slug-da var
          params.append(key, value);
        }
      }
      
      const queryString = params.toString();
      const currentUrl = window.location.pathname;
      const newUrl = currentUrl + (queryString ? '?' + queryString : '');
      
      window.location.href = newUrl;
    });
  }

  // ===========================
  // Xəritə interaktivliyi
  // ===========================

  // Language detection - home.php-dən inline script ilə gəlir
  const lang = (typeof currentLanguage !== 'undefined') ? currentLanguage : 'az';
  let lastSelectedRegion = null;

  console.log("Current lang:", lang);

  if (typeof locationMap === 'undefined') {
    console.log("locationMap yüklənməyib - xəritə səhifəsində deyilik");
    return;
  }

  console.log("locationMap:", locationMap);

  // SVG slug-larını location slug-larına map etmək (bütün dillər üçün)
  const slugMapping = {
    // English mappings
    'agdam': 'aghdam',
    'agdere': 'aghdara', 
    'kelbecer': 'kalbajar',
    'lacin': 'lachin',
    'zengilan': 'zangilan',
    'cebrayil': 'jabrayil',
    'qubadli': 'gubadli',
    'xocavend': 'khojavend',
    'xocali': 'khojaly',
    'xankendi': 'khankendi',
    
    // Russian specific mappings
    'ru': {
      'agdam': 'aghdam',
      'agdere': 'aghdara',
      'kelbecer': 'kalbadjar',  // fərqli!
      'lacin': 'lachin',
      'zengilan': 'zagilan',    // fərqli!
      'cebrayil': 'jabrayil',
      'qubadli': 'gubadli',
      'xocavend': 'xodjavend', // fərqli!
      'xocali': 'xodjali',     // fərqli!
      'xankendi': 'khankendi'  // fərqli!
    },
    
    // Spanish specific mappings
    'es': {
      'agdam': 'aghdam',
      'agdere': 'aghdara',
      'kelbecer': 'kalbayar',   // fərqli!
      'lacin': 'lachin',
      'zengilan': 'zangilan',
      'cebrayil': 'yabrayil',   // fərqli!
      'qubadli': 'gubadli',
      'xocavend': 'joyavand',   // fərqli!
      'xocali': 'joyali',       // fərqli!
      'xankendi': 'jankendi'    // fərqli!
    }
  };

  console.log("locationMap:", locationMap);

  document.querySelectorAll('[id^="qarabag-region"]').forEach(el => {
    const slug = el.dataset.slug;

    // Slug variants-ını yoxla
    let regionData = null;
    
    // 1. Birbaşa slug ilə axtarış
    if (locationMap[slug]?.[lang]) {
      regionData = locationMap[slug][lang];
    }
    // 2. Base slug tapmağa çalış (suffix sil)
    else {
      const baseSlug = slug.replace(/-[a-z]{2}$/, ''); // fuzuli-az -> fuzuli
      
      // Language-specific və default mapping-ləri yoxla
      let mappedSlug = baseSlug;
      
      // Əvvəl language-specific mapping yoxla
      if (slugMapping[lang] && slugMapping[lang][baseSlug]) {
        mappedSlug = slugMapping[lang][baseSlug];
      }
      // Sonra default mapping yoxla
      else if (slugMapping[baseSlug]) {
        mappedSlug = slugMapping[baseSlug];
      }
      
      console.log(`Language: ${lang}, Base: ${baseSlug}, Mapped: ${mappedSlug}`);
      
      // Bütün location-larda mapped slug-a uyğun olanı tap
      for (const [locationSlug, languages] of Object.entries(locationMap)) {
        const locationBaseSlug = locationSlug.replace(/-[a-z]{2}$/, '');
        if (locationBaseSlug === mappedSlug && languages[lang]) {
          regionData = languages[lang];
          console.log(`Found mapping: ${baseSlug} -> ${mappedSlug} -> ${locationSlug}`);
          break;
        }
      }
      
      // 3. Hələ tapılmadısa, azərbaycan dilində axtarış
      if (!regionData) {
        for (const [locationSlug, languages] of Object.entries(locationMap)) {
          const locationBaseSlug = locationSlug.replace(/-[a-z]{2}$/, '');
          if (locationBaseSlug === mappedSlug && languages.az) {
            regionData = languages.az;
            console.log(`Fallback to AZ: ${baseSlug} -> ${mappedSlug} -> ${locationSlug}`);
            break;
          }
        }
      }
    }

    if (!regionData) {
      console.warn(`Region data tapılmadı: ${slug} for lang: ${lang}`);
      console.log("Available locations:", Object.keys(locationMap));
      return;
    }

    console.log(`Region ${slug} data:`, regionData);

    el.addEventListener('mouseover', () => {
      if (lastSelectedRegion && lastSelectedRegion !== el) {
        lastSelectedRegion.style.fill = '';
      }

      document.getElementById('region-title').textContent = regionData.name;
      document.getElementById('region-description').textContent = regionData.desc || " ";
      el.style.fill = '#91aa99';
      lastSelectedRegion = el;
    });

    el.addEventListener('mouseout', () => {
      // Stabil rəng qalır — heç nə etmə
    });

    el.addEventListener('click', () => {
      console.log(`Navigating to: ${regionData.url}`);
      window.location.href = regionData.url;
    });

    // Title elementini SVG-ə əlavə et (tooltip üçün)
    const existingTitle = el.querySelector('title');
    if (existingTitle) existingTitle.remove();

    const title = document.createElementNS("http://www.w3.org/2000/svg", "title");
    title.textContent = regionData.name;
    el.appendChild(title);
  });
});
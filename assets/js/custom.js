$(document).ready(function(){
    // Performance optimization for mobile
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    // Optimize animations for mobile
    if (isMobile) {
        $('body').addClass('mobile-device');
    }
    
    // Add touch class for touch devices
    if (isTouch) {
        $('body').addClass('touch-device');
    }
    // banner slider - optimized for mobile
    $('.as_banner_slider').slick({
        dots: true,
        speed: isMobile ? 400 : 800,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: !isMobile, // Disable autoplay on mobile to save battery
        autoplaySpeed: 5000,
        pauseOnHover: true,
        pauseOnFocus: true,
        adaptiveHeight: true,
        prevArrow:'<button type="button" class="slick-prev as_btn" aria-label="Previous slide"><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="12" height="16" viewBox="0 0 12 16"> <path d="M12.003,15.996 L-0.000,7.997 L12.002,-0.001 L10.062,7.997 L12.003,15.996 ZM10.102,2.762 L2.246,7.997 L10.102,13.233 L8.832,7.997 L10.102,2.762 ZM3.824,7.997 L8.256,5.043 L7.539,7.997 L8.256,10.951 L3.824,7.997 Z" class="cls-1"/> </svg></span> <span class="sr-only">Prev</span></button>',
        nextArrow:'<button type="button" class="slick-next as_btn" aria-label="Next slide">next <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="8" height="12" viewBox="0 0 8 12"><path d="M8.000,5.998 L-0.002,11.997 L1.292,5.998 L-0.002,-0.001 L8.000,5.998 ZM1.265,9.924 L6.502,5.998 L1.265,2.071 L2.112,5.998 L1.265,9.924 ZM5.451,5.998 L2.496,8.213 L2.974,5.998 L2.496,3.783 L5.451,5.998 Z" class="cls-1"/> </svg></span></button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    dots: true,
                    speed: 400
                }
            }
        ]
    });


    // customer slider
    $('.as_customer_slider').slick({
        infinite: false,
        dots: true,
        speed: 800,
        slidesToShow: 2,
        slidesToScroll: 2,
        prevArrow:'<button type="button" class="slick-prev as_btn"><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="12" height="16" viewBox="0 0 12 16"> <path d="M12.003,15.996 L-0.000,7.997 L12.002,-0.001 L10.062,7.997 L12.003,15.996 ZM10.102,2.762 L2.246,7.997 L10.102,13.233 L8.832,7.997 L10.102,2.762 ZM3.824,7.997 L8.256,5.043 L7.539,7.997 L8.256,10.951 L3.824,7.997 Z" class="cls-1"/> </svg></span> Prev</button>',
        nextArrow:'<button type="button" class="slick-next as_btn">next <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="8" height="12" viewBox="0 0 8 12"><path d="M8.000,5.998 L-0.002,11.997 L1.292,5.998 L-0.002,-0.001 L8.000,5.998 ZM1.265,9.924 L6.502,5.998 L1.265,2.071 L2.112,5.998 L1.265,9.924 ZM5.451,5.998 L2.496,8.213 L2.974,5.998 L2.496,3.783 L5.451,5.998 Z" class="cls-1"/> </svg></span></button>',
        responsive: [
            {
              breakpoint: 991,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows:false
              }
            },
          ]
    });

    // product slider
    $('.as_product_slider').slick({
        arrows: false,
        infinite:true,
        speed:800,
        dots:true,
        slidesToShow: 4,
        slidesToScroll: 4,
        autoplay: true,
        autoplaySpeed: 4000,
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                dots: true,
                arrows:false
              }
            },
            {
              breakpoint: 991,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                dots: true,
                arrows:false
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                dots: true,
                arrows:false
              }
            },
            {
              breakpoint: 568,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows:false
              }
            },
          ]
       
    });
    // overview slider
    $('.as_about_slider').slick({
        infinite:true,
        speed:800,
        dots:true,
        arrows:false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 4000
    });
    // overview slider
    $('.as_overview_slider').slick({
        infinite:true,
        speed:800,
        dots:true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        prevArrow:'<button type="button" class="slick-prev as_btn"><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="12" height="16" viewBox="0 0 12 16"> <path d="M12.003,15.996 L-0.000,7.997 L12.002,-0.001 L10.062,7.997 L12.003,15.996 ZM10.102,2.762 L2.246,7.997 L10.102,13.233 L8.832,7.997 L10.102,2.762 ZM3.824,7.997 L8.256,5.043 L7.539,7.997 L8.256,10.951 L3.824,7.997 Z" class="cls-1"/> </svg></span> Prev</button>',
        nextArrow:'<button type="button" class="slick-next as_btn">next <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="8" height="12" viewBox="0 0 8 12"><path d="M8.000,5.998 L-0.002,11.997 L1.292,5.998 L-0.002,-0.001 L8.000,5.998 ZM1.265,9.924 L6.502,5.998 L1.265,2.071 L2.112,5.998 L1.265,9.924 ZM5.451,5.998 L2.496,8.213 L2.974,5.998 L2.496,3.783 L5.451,5.998 Z" class="cls-1"/> </svg></span></button>' ,
        responsive: [
            {
              breakpoint: 991,
              settings: {
                arrows:false
              }
            },
          ]
    });
    //  shop single slider
    $('.as_shopsingle_for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite:true,
        speed:800,
        arrows: false,
        fade: true,
        asNavFor: '.as_shopsingle_nav'
    });
    $('.as_shopsingle_nav').slick({
      slidesToShow: 4,
      arrows: false,
      slidesToScroll: 1,
      asNavFor: '.as_shopsingle_for',
      dots: false,
      centerMode: true,
      focusOnSelect: true
    });
    // card slider
    $('.as_card_slider').slick({
        infinite:true,
        speed:800,
        dots:false,
        arrows:false,
        slidesToShow: 4,
        slidesToScroll: 4,
        autoplay: true,
        autoplaySpeed: 4000,
        responsive: [
          {
            breakpoint: 1199,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
            }
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            }
          }
        ]
    });
    
    // datepicker
    $('.as_datepicker').datepicker({
        language: 'en'
    });

    // timepicker
    $('.as_timepicker').datepicker({
        dateFormat: ' ',
        timepicker: true,
        classes: 'only-timepicker',
        language: 'en' 
    });

    // countTo
    $('.as_number>span>span').countTo();
    
    // search popup
    $('.as_search').on('click', function(){
        $(this).parent().find('.as_search_boxpopup').addClass('popup_open');
    })
    $('.as_cancel').on('click', function(){
        $(this).parent().removeClass('popup_open');
    })

    // Enhanced Mobile menu toggle - Complete Fix
    $(document).off('click', '.as_toggle');
    $(document).on('click', '.as_toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $toggle = $(this);
        const $header = $toggle.closest('.as_header_wrapper');
        const $menu = $header.find('.as_menu');
        const $body = $('body');
        
        // Toggle menu state
        if ($header.hasClass('menu_open')) {
            // Close menu
            $header.removeClass('menu_open');
            $menu.slideUp(250);
            $body.removeClass('mobile-menu-open');
            $toggle.attr('aria-expanded', 'false');
        } else {
            // Open menu
            $header.addClass('menu_open');
            $menu.slideDown(250);
            $body.addClass('mobile-menu-open');
            $toggle.attr('aria-expanded', 'true');
        }
    });
    
    // Setup menu visibility on page load and resize
    function setupMenuVisibility() {
        const isMobileView = window.innerWidth <= 767;
        const $menu = $('.as_menu');
        const $header = $('.as_header_wrapper');
        const $body = $('body');
        
        if (isMobileView) {
            // On mobile, menu starts hidden unless menu_open class is present
            if (!$header.hasClass('menu_open')) {
                $menu.hide();
            }
        } else {
            // On desktop, menu is always visible and clean up mobile states
            $menu.show();
            $header.removeClass('menu_open');
            $body.removeClass('mobile-menu-open');
            $('.as_toggle').attr('aria-expanded', 'false');
        }
    }
    
    // Run setup on page load
    setupMenuVisibility();
    
    // Run setup on window resize with debouncing
    let menuResizeTimer;
    $(window).on('resize', function() {
        clearTimeout(menuResizeTimer);
        menuResizeTimer = setTimeout(setupMenuVisibility, 250);
    });
    
    // Close menu when clicking outside (mobile only)
    $(document).on('click', function(e) {
        const $header = $('.as_header_wrapper');
        const $menu = $('.as_menu');
        const $body = $('body');
        
        if (
            window.innerWidth <= 767 &&
            $header.hasClass('menu_open') && 
            !$(e.target).closest('.as_menu').length && 
            !$(e.target).closest('.as_toggle').length
        ) {
            $header.removeClass('menu_open');
            $menu.slideUp(250);
            $body.removeClass('mobile-menu-open');
            $('.as_toggle').attr('aria-expanded', 'false');
        }
    });
    
    // Prevent menu close when clicking inside menu
    $('.as_menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Close mobile menu when clicking on menu links
    $('.as_menu a').on('click', function() {
        if (window.innerWidth <= 767) {
            const $header = $('.as_header_wrapper');
            const $menu = $('.as_menu');
            const $body = $('body');
            
            $header.removeClass('menu_open');
            $menu.slideUp(250);
            $body.removeClass('mobile-menu-open');
            $('.as_toggle').attr('aria-expanded', 'false');
        }
    });
    
    // Enhanced nav link click handling
    $(document).off('click', '.as_menu_wrapper ul li a');
    $(document).on('click', '.as_menu_wrapper ul li a', function(e) {
        const $this = $(this);
        const href = $this.attr('href');
        
        console.log('Nav link clicked:', href);
        
        // Add visual feedback
        $this.addClass('touch-active');
        setTimeout(() => $this.removeClass('touch-active'), 200);
        
        // Close mobile menu on mobile devices
        if ($(window).width() < 992) {
            setTimeout(() => {
                $('.as_header_wrapper').removeClass('menu_open');
                $('.as_menu_wrapper').addClass('menu-hidden').removeClass('menu-visible');
                $('body').removeClass('mobile-menu-open');
            }, 100);
        }
        
        // Handle hash links
        if (href && href.startsWith('#')) {
            e.preventDefault();
            const target = $(href);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 500);
            }
        }
    });
    
    // Close menu when clicking outside
    $(document).on('click', function(e) {
        if ($(window).width() < 992) {
            const $headerWrapper = $('.as_header_wrapper');
            if (!$(e.target).closest($headerWrapper).length) {
                $headerWrapper.removeClass('menu_open');
                $('.as_menu_wrapper').addClass('menu-hidden').removeClass('menu-visible');
                $('body').removeClass('mobile-menu-open');
            }
        }
    });
    
    // Enhanced touch event handling
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
        $(document).on('touchstart', '.as_menu_wrapper ul li a', function(e) {
            $(this).addClass('touch-active');
        });
        
        $(document).on('touchend', '.as_menu_wrapper ul li a', function(e) {
            const $this = $(this);
            setTimeout(() => $this.removeClass('touch-active'), 150);
        });
        
        $(document).on('touchcancel', '.as_menu_wrapper ul li a', function(e) {
            $(this).removeClass('touch-active');
        });
    }
    
    // Close menu when window is resized to desktop
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if ($(window).width() >= 992) {
                $('.as_header_wrapper').removeClass('menu_open');
                $('.as_menu_wrapper').removeClass('menu-hidden menu-visible');
                $('body').removeClass('mobile-menu-open');
            }
        }, 250);
    });
    
    // Initialize mobile menu state
    function initMobileMenu() {
        if ($(window).width() < 992) {
            $('.as_menu_wrapper').addClass('menu-hidden').removeClass('menu-visible');
            $('.as_header_wrapper').removeClass('menu_open');
            $('body').removeClass('mobile-menu-open');
        }
    }
    
    // Call initialization
    initMobileMenu();
    
    console.log('Enhanced mobile navigation initialized');
    
    // responsive menu
    $(document).on('click','.as_menu > ul > li >a',function(){
      // console.log($(this).closest('li').find('ul.submenu'))
      $('.as_menu >ul > li>.as_submenu').removeClass('active');
      $(this).closest('li').find('>ul.as_submenu').addClass('active')
    })
    $(document).on('click','.as_menu > ul > li > ul > li >a',function(){
      // console.log($(this).closest('li').find('ul.submenu'))
      $(this).closest('li').find('>ul.as_submenu').toggleClass('active')
    })

    // cart box
    $('.as_cart_wrapper').on('click',function(){
        $(this).parent().toggleClass('cart_open');
    })

    // login popup
    $('.as_signup').on('click',function(){
      $(this).closest('.modal-body').find('.as_login_box').removeClass('active');
      $(this).closest('.modal-body').find('.as_signup_box').addClass('active');
      $(this).closest('.modal-content').find('.modal-title').text('Sign Up');
    })
    $('.as_login').on('click',function(){
      $(this).closest('.modal-body').find('.as_login_box').addClass('active');
      $(this).closest('.modal-body').find('.as_signup_box').removeClass('active');
      $(this).closest('.modal-content').find('.modal-title').text('Login');
    })

    if($('.as_select_box').length){
      $(".as_select_box select").select2({
          placeholder: 'data-placeholder',
          allowClear: true
      });
  }
    // circle
    if($('.as_progressbar').length){
      $(".as_progressbar.p1").circularProgress({
          color:'#ff7010',
          starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
          percent: 0, // percent starts from
          percentage: true,
      }).circularProgress('animate', 45, 5000);
      $(".as_progressbar.p2").circularProgress({
          color:'#ff7010',
          starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
          percent: 0, // percent starts from
          percentage: true,
      }).circularProgress('animate', 94, 5000);
      $(".as_progressbar.p3").circularProgress({
          color:'#ff7010',
          starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
          percent: 0, // percent starts from
          percentage: true,
      }).circularProgress('animate', 84, 5000);
      $(".as_progressbar.p4").circularProgress({
          color:'#ff7010',
          starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
          percent: 0, // percent starts from
          percentage: true,
      }).circularProgress('animate', 64, 5000);
      $(".as_progressbar.p5").circularProgress({
          color:'#ff7010',
          starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
          percent: 0, // percent starts from
          percentage: true,
      }).circularProgress('animate', 76, 5000);
   }

  // step
  $(document).on('click','.checkout_wrapper_box .next',function(){
    var targetNum = $(this).attr('data-step');
    $(this).closest('.checkout_wrapper_box').find('.step').css('display','none');
    $(this).closest('.checkout_wrapper_box').find('[data-target="'+targetNum+'"]').css('display','block');
    $(this).closest('.checkout_wrapper_box').find('[data-active="'+targetNum+'"]').addClass('active');
    // $(this).cloasest('.checkout_wrapper_box').find('data-target="'+targetNum+'"').css('display','block');
  })

  // number increase
  $('.quantity .plus').on('click', function(){
    var num = parseInt($('.quantity').find('input').val())+1; 
    $(this).closest('.quantity').find('input').val(num);
  }) 
  $('.quantity .minus').on('click', function(){
    var num = parseInt($('.quantity').find('input').val())-1; 
    $(this).closest('.quantity').find('input').val(num);
  }) 

  // Mobile optimization and touch improvements
  if (isMobile || isTouch) {
    // Improve touch scrolling
    $('body').css({
        '-webkit-overflow-scrolling': 'touch',
        'overscroll-behavior': 'contain'
    });
    
    // Add haptic feedback for buttons (where supported)
    $('.as_btn, .btn').on('touchstart', function() {
        if (navigator.vibrate) {
            navigator.vibrate(10); // Very short vibration
        }
    });
    
    // Optimize form inputs for mobile
    $('input[type="email"]').attr('inputmode', 'email');
    $('input[type="tel"]').attr('inputmode', 'tel');
    $('input[type="number"]').attr('inputmode', 'numeric');
    $('input[type="search"]').attr('inputmode', 'search');
    
    // Prevent zoom on double tap for iOS
    let lastTouchEnd = 0;
    $(document).on('touchend', function(event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    });
  }
  
  // Optimize images loading for better performance
  if ('loading' in HTMLImageElement.prototype) {
    $('img').attr('loading', 'lazy');
  }
  
  // Add smooth scroll for anchor links
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      let target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top - 80
        }, 500);
        return false;
      }
    }
  });
  
  // Viewport height fix for mobile browsers
  function setViewportHeight() {
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');
  }
  
  setViewportHeight();
  $(window).on('resize orientationchange', setViewportHeight);
  
})
$(window).on('load',function(){
    $('.as_loader').addClass('hide');
    
    // Fade in animations for better UX
    $('.as_service_box, .as_customer_box, .as_whychoose_box').each(function(index) {
        $(this).delay(100 * index).animate({
            opacity: 1
        }, 600);
    });
})


      
// Enhanced Mobile Menu JavaScript - Complete Fix
$(document).ready(function(){
    console.log('Mobile menu script loaded');
    
    // Mobile menu initialization
    function initializeMobileMenu() {
        const $toggle = $('.as_toggle');
        const $header = $('.as_header_wrapper');
        const $menu = $('.as_menu');
        const $body = $('body');
        
        // Ensure menu has proper initial state
        if (window.innerWidth <= 767) {
            $menu.hide(); // Start hidden on mobile
            $header.removeClass('menu_open');
            $body.removeClass('mobile-menu-open');
        } else {
            $menu.show(); // Always visible on desktop
            $header.removeClass('menu_open');
            $body.removeClass('mobile-menu-open');
        }
    }
    
    // Call initialization
    initializeMobileMenu();
    
    // Enhanced toggle functionality
    $(document).off('click.mobilemenu', '.as_toggle');
    $(document).on('click.mobilemenu', '.as_toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Hamburger menu clicked');
        
        const $toggle = $(this);
        const $header = $toggle.closest('.as_header_wrapper');
        const $menu = $header.find('.as_menu');
        const $body = $('body');
        
        // Only work on mobile screens
        if (window.innerWidth <= 767) {
            if ($header.hasClass('menu_open')) {
                // Close menu
                console.log('Closing mobile menu');
                $header.removeClass('menu_open');
                $menu.slideUp(250, function() {
                    $body.removeClass('mobile-menu-open');
                });
                $toggle.attr('aria-expanded', 'false');
            } else {
                // Open menu
                console.log('Opening mobile menu');
                $header.addClass('menu_open');
                $body.addClass('mobile-menu-open');
                $menu.slideDown(250);
                $toggle.attr('aria-expanded', 'true');
            }
        }
    });
    
    // Close menu when clicking outside
    $(document).off('click.mobilemenu-outside');
    $(document).on('click.mobilemenu-outside', function(e) {
        const $header = $('.as_header_wrapper');
        const $menu = $('.as_menu');
        const $body = $('body');
        
        if (
            window.innerWidth <= 767 &&
            $header.hasClass('menu_open') && 
            !$(e.target).closest('.as_menu').length && 
            !$(e.target).closest('.as_toggle').length
        ) {
            console.log('Closing menu - clicked outside');
            $header.removeClass('menu_open');
            $menu.slideUp(250);
            $body.removeClass('mobile-menu-open');
            $('.as_toggle').attr('aria-expanded', 'false');
        }
    });
    
    // Close menu when clicking on links (mobile only)
    $(document).off('click.mobilemenu-links', '.as_menu a');
    $(document).on('click.mobilemenu-links', '.as_menu a', function() {
        if (window.innerWidth <= 767) {
            console.log('Menu link clicked - closing menu');
            const $header = $('.as_header_wrapper');
            const $menu = $('.as_menu');
            const $body = $('body');
            
            setTimeout(function() {
                $header.removeClass('menu_open');
                $menu.slideUp(250);
                $body.removeClass('mobile-menu-open');
                $('.as_toggle').attr('aria-expanded', 'false');
            }, 100);
        }
    });
    
    // Handle window resize
    let resizeTimeout;
    $(window).off('resize.mobilemenu');
    $(window).on('resize.mobilemenu', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            console.log('Window resized - reinitializing menu');
            initializeMobileMenu();
        }, 250);
    });
    
    // Prevent menu from staying open after orientation change
    $(window).off('orientationchange.mobilemenu');
    $(window).on('orientationchange.mobilemenu', function() {
        setTimeout(function() {
            console.log('Orientation changed - reinitializing menu');
            initializeMobileMenu();
        }, 500);
    });
    
    console.log('Mobile menu enhanced initialization complete');
});
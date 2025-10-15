// Mobile Menu Initialization
$(document).ready(function() {
    // Initial setup for mobile/desktop states
    function setupMenuVisibility() {
        const isMobileView = window.innerWidth <= 767;
        const $menu = $('.as_menu');
        const $header = $('.as_header_wrapper');
        
        if (isMobileView) {
            // On mobile, menu starts hidden
            if (!$header.hasClass('menu_open')) {
                $menu.hide();
            }
        } else {
            // On desktop, menu is always visible
            $menu.show();
            $header.removeClass('menu_open');
            $('body').removeClass('mobile-menu-open');
        }
    }
    
    // Run on page load
    setupMenuVisibility();
    
    // Run on window resize
    $(window).on('resize', function() {
        setupMenuVisibility();
    });
    
    // Close menu when clicking outside
    $(document).on('click', function(e) {
        const $header = $('.as_header_wrapper');
        const $toggle = $('.as_toggle');
        const $menu = $('.as_menu');
        
        if (
            $header.hasClass('menu_open') && 
            !$(e.target).closest('.as_menu').length && 
            !$(e.target).closest('.as_toggle').length
        ) {
            $header.removeClass('menu_open');
            $menu.slideUp(300);
            $('body').removeClass('mobile-menu-open');
        }
    });
    
    // Prevent menu close when clicking inside menu
    $('.as_menu').on('click', function(e) {
        e.stopPropagation();
    });
});
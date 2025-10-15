<section class="as_header_wrapper as_menu_wrapper">
  <style>
    @media (max-width: 767px) {
      .as_menu ul {
        padding: 0 0 0 0;
        margin: 0;
      }
      .as_menu ul li {
        display: block;
        margin: 0 0 18px 0;
        padding: 0;
      }
      .as_menu ul li a {
        display: block;
        background: linear-gradient(90deg, rgba(255,255,255,0.06), rgba(255,255,255,0.12));
        color: #ffffff !important;
        font-weight: 700;
        font-size: 17px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.18);
        padding: 14px 20px;
        margin: 10px 14px;
        transition: background 0.18s, color 0.18s, transform 0.12s;
        text-align: left;
        letter-spacing: 0.3px;
      }
      .as_menu ul li a.active, .as_menu ul li a:focus, .as_menu ul li a:hover {
        background: linear-gradient(90deg,#ff9a2e 0%,#ff7b00 100%);
        color: #ffffffff !important;
        box-shadow: 0 8px 22px rgba(255,110,20,0.18);
        transform: translateY(-2px);
        text-shadow: none;
      }
      .nav_login_btn a, .nav_register_btn a {
        background: linear-gradient(135deg, #ff8f00 0%, #ff6a00 60%, #ff4b00 100%) !important;
        color: #fff !important;
        font-weight: 800;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(255,110,20,0.22);
        margin: 8px 14px;
        padding: 14px 22px;
        display: block;
        text-align: center;
      }
      /* Force user/dashboard links to be white inside the mobile menu regardless of inline styles */
      .as_menu .as_user_menu a,
      .as_menu a { color: #ffffff !important; }
    }
    @media (max-width: 767px) {
      .as_menu {
        position: fixed !important;
        top: 0;
        left: -100vw;
          width: 80vw;
          max-width: 340px;
          height: 100vh;
    background: linear-gradient(135deg,#2b0b00 0%,#6b1f04 30%,#ff7b00 65%,#ffb86b 100%); /* cosmic orange gradient */
    color: #fff;
    box-shadow: 2px 0 24px rgba(0,0,0,0.45);
          z-index: 99999 !important;
          transition: left 0.28s cubic-bezier(.77,0,.18,1);
          overflow-y: auto;
          padding-top: 72px;
          display: block !important;
      }
      .menu_open .as_menu {
        left: 0;
      }
      .as_body_overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.35);
        z-index: 99998 !important;
        transition: opacity 0.3s;
      }
      .menu_open .as_body_overlay {
        display: block;
        opacity: 1;
      }
      body.menu_open {
        overflow: hidden;
      }
    }
  </style>
  <div class="as_logo">
    <a href="<?php echo base_url(); ?>">
      <img src="<?php echo base_url();?>assets/images/logo.svg" alt="Kundali Magic Logo" />
    </a>
  </div>
  <div class="as_header_detail">
      <button class="as_toggle" type="button" aria-label="Toggle navigation" aria-controls="primary-navigation" aria-expanded="false">
  <span class="line"></span>
  <span class="line"></span>
  <span class="line"></span>
      </button>
      <nav class="as_menu" id="primary-navigation">
        <ul>
          <?php
            $segment1 = $this->uri->segment(1);
            $isHome = ($segment1 === null || $segment1 === '' || $segment1 === '/' );
            $currentPageAttr = 'aria-current="page"';
          ?>
          <li>
            <a href="<?php echo base_url()?>" class="<?php echo $isHome ? 'active' : '';?>" <?php echo $isHome ? $currentPageAttr : '';?>>
              home
            </a>
          <li>
            <a href="<?php echo base_url()?>about-us" class="<?php echo ($segment1 === 'about-us') ? 'active' : '';?>" <?php echo ($segment1 === 'about-us') ? $currentPageAttr : '';?>>
              about
            </a>
          </li>
          </li>
          <li>
            <a href="<?php echo base_url()?>services" class="<?php echo ($segment1 === 'services') ? 'active' : '';?>" <?php echo ($segment1 === 'services') ? $currentPageAttr : '';?>>
              services
            </a>
          </li>
          <li>
            <a href="<?php echo base_url()?>generate-kundli" class="<?php echo ($segment1 === 'generate-kundli') ? 'active' : '';?>" <?php echo ($segment1 === 'generate-kundli') ? $currentPageAttr : '';?>>
              generate kundli
            </a>
          </li>
          <li>
            
            <a href="<?php echo base_url()?>contact-us" class="<?php echo ($segment1 === 'contact-us') ? 'active' : '';?>" <?php echo ($segment1 === 'contact-us') ? $currentPageAttr : '';?>>
              contact
            </a>
          </li>

                    <?php if($this->session->userdata('logged_in')): ?>
            <!-- Logged in user menu -->
            <li class="as_user_menu">
              <a href="<?php echo base_url('dashboard'); ?>" style="color: #ff7010 !important; font-weight: 600;">
                <i class="fas fa-user-circle" style="margin-right: 5px;"></i>
                <?php echo $this->session->userdata('user_name'); ?>
              </a>
            </li>
            <li><a href="<?php echo base_url('dashboard'); ?>" style="color: #fff4e6 !important;">
              <i class="fas fa-tachometer-alt" style="margin-right: 5px;"></i> Dashboard
            </a></li>
            <li><a href="<?php echo base_url('auth/logout'); ?>" style="color: #ff6b6b !important;">
              <i class="fas fa-sign-out-alt" style="margin-right: 5px;"></i> Logout
            </a></li>
          <?php else: ?>
            <!-- Guest user menu -->
            <li class="nav_login_btn">
              <a href="<?php echo base_url('login'); ?>" style="color: #fff4e6 !important; padding: 8px 15px; border: 2px solid #ff7010; border-radius: 8px; margin-right: 10px; transition: all 0.3s ease; text-decoration: none;">
                <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i> Login
              </a>
            </li>
            <li class="nav_register_btn">
              <a href="<?php echo base_url('register'); ?>" style="background: linear-gradient(135deg, #ff7010 0%, #ff8f00 50%, #e65100 100%) !important; color: #ffffff !important; padding: 8px 15px; border-radius: 8px; border: 2px solid #ffa726; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 112, 16, 0.3);">
                <i class="fas fa-user-plus" style="margin-right: 5px;"></i> Register
              </a>
            </li>
          <?php endif; ?>
        </ul>
  </nav>
    </div>
    <span class="as_body_overlay" aria-hidden="true"></span>
  <script>
    // Sliding mobile menu logic (dropdown overlay)
    document.addEventListener('DOMContentLoaded', function () {
      var toggle = document.querySelector('.as_toggle');
      var header = document.querySelector('.as_header_wrapper');
      var overlay = document.querySelector('.as_body_overlay');
      var menu = document.querySelector('.as_menu');
      function closeMenu() {
        header.classList.remove('menu_open');
        document.body.classList.remove('menu_open');
        toggle.setAttribute('aria-expanded', 'false');
      }
      function openMenu() {
        header.classList.add('menu_open');
        document.body.classList.add('menu_open');
        toggle.setAttribute('aria-expanded', 'true');
      }
      if (toggle && menu && overlay) {
        toggle.addEventListener('click', function(e) {
          e.stopPropagation();
          if (header.classList.contains('menu_open')) {
            closeMenu();
          } else {
            openMenu();
          }
        });
        overlay.addEventListener('click', function() {
          closeMenu();
        });
        // Close menu on link click (mobile only)
        menu.addEventListener('click', function(e) {
          if (window.innerWidth <= 767 && e.target.tagName === 'A') {
            setTimeout(closeMenu, 100);
          }
        });
        // Responsive: close menu on resize/orientation change
        window.addEventListener('resize', function() {
          if (window.innerWidth > 767) closeMenu();
        });
        window.addEventListener('orientationchange', function() {
          setTimeout(function() { if (window.innerWidth > 767) closeMenu(); }, 500);
        });
      }
    });
  </script>
</section>

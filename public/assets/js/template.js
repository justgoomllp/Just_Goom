(function($) {
  'use strict';
  $(function() {
    var body = $('body');
    var contentWrapper = $('.content-wrapper');
    var scroller = $('.container-scroller');
    var footer = $('.footer');
    var sidebar = $('.sidebar');
    var navbar = $('.navbar').not('.top-navbar');


    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required

    function addActiveClass(element) {
      if (current === "") {
        //for root url
        if (element.attr('href').indexOf("index.html") !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
        }
      } else {
        //for other url
        if (element.attr('href').indexOf(current) !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
          if (element.parents('.submenu-item').length) {
            element.addClass('active');
          }
        }
      }
    }

    var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
    $('.nav li a', sidebar).each(function() {
      var $this = $(this);
      addActiveClass($this);
    })

    $('.horizontal-menu .nav li a').each(function() {
      var $this = $(this);
      addActiveClass($this);
    })

    //Close other submenu in sidebar on opening any

    sidebar.on('show.bs.collapse', '.collapse', function() {
      sidebar.find('.collapse.show').collapse('hide');
    });


    //Change sidebar and content-wrapper height
    applyStyles();

    function applyStyles() {
      //Applying perfect scrollbar
      if (!body.hasClass("rtl")) {
        if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
          const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
        }
        if ($('.chats').length) {
          const chatsScroll = new PerfectScrollbar('.chats');
        }
        if (body.hasClass("sidebar-fixed")) {
          if($('#sidebar').length) {
            var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
          }
        }
      }
    }

    $('[data-toggle="minimize"]').on("click", function() {
      if (body.hasClass('sidebar-toggle-display')) {
        body.toggleClass('sidebar-hidden');
      } else {
        body.toggleClass('sidebar-icon-only');
        $('.sidebar-cta').toggleClass('cta-hide')
      }
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    //Horizontal menu in mobile
    $('[data-toggle="horizontal-menu-toggle"]').on("click", function() {
      $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
    });
    // Horizontal menu navigation in mobile menu on click
    var navItemClicked = $('.horizontal-menu .page-navigation >.nav-item');
    navItemClicked.on("click", function(event) {
      if(window.matchMedia('(max-width: 991px)').matches) {
        if(!($(this).hasClass('show-submenu'))) {
          navItemClicked.removeClass('show-submenu');
        }
        $(this).toggleClass('show-submenu');
      }        
    })

    $(window).scroll(function() {
      if(window.matchMedia('(min-width: 992px)').matches) {
        var header = $('.horizontal-menu');
        if ($(window).scrollTop() >= 150) {
          $(header).addClass('fixed-on-scroll');
        } else {
          $(header).removeClass('fixed-on-scroll');
        }
      }
    });

    // fixed navbar on scroll
    $(window).scroll(function() {
      if(window.matchMedia('(min-width: 992px)').matches) {
        if ($(window).scrollTop() >= 197) {
          $(navbar).addClass('navbar-mini fixed-top');
          $(body).addClass('navbar-fixed-top');
        } else {
          $(navbar).removeClass('navbar-mini fixed-top');
          $(body).removeClass('navbar-fixed-top');
        }
      }
    });

    if(window.matchMedia('(max-width: 991px)').matches) {
      $(navbar).addClass('navbar-mini fixed-top');
      $(body).addClass('navbar-fixed-top');
    }    

    function toggleNavbarMini() {
      console.log('function working');
      
      if(window.matchMedia('(max-width: 991px)').matches) {
        $(navbar).addClass('navbar-mini');
      } else {
        $(navbar).removeClass('navbar-mini');
      }
    }

    window.addEventListener("resize", toggleNavbarMini);
    toggleNavbarMini();
    const proBanner = document.querySelector('#proBanner');
    const bannerClose = document.querySelector('#bannerClose');
    if (proBanner) {
      if ($.cookie('spica-pro-banner')!="true") {
        proBanner.classList.add('d-flex');
      }
      else {
        proBanner.classList.add('d-none');
      }
    }
    if (proBanner && bannerClose) {
      bannerClose.addEventListener('click',function() {
        proBanner.classList.add('d-none');
        proBanner.classList.remove('d-flex');
        var date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60 * 1000); 
        $.cookie('spica-pro-banner', "true", { expires: date });
      });
    }
    

  });
})(jQuery);

//CTA button hover
const ctaBtn = document.getElementById('cta-trigger')
const crownblack = document.querySelector('.crown-black')
const crownwhite = document.querySelector('.crown-white')

if (ctaBtn && crownblack && crownwhite) {
  ctaBtn.addEventListener('mouseover', () => {
    crownwhite.classList.remove('cta-hover')
    crownblack.classList.add('cta-hover')
  })
  ctaBtn.addEventListener('mouseout', () => {
    crownwhite.classList.add('cta-hover')
    crownblack.classList.remove('cta-hover')
  })
}

// Link redirection 
function redirection(url) {
  // create show message
  const message = document.createElement('div')

  message.style.cssText = `
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: black;
      color: white;
      padding: 8rem;
      font-size: 24px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      z-index: 1000;
  `
  message.textContent =  'Redirecting to Pro Demo....'
  document.body.appendChild(message)

  setTimeout(() => {
    window.open(url, '_blank')
    message.remove()
  }, 1500)
}

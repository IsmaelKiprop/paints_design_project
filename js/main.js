(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    
    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-100px');
        }
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });


    // Date and time picker
    $('.date').datetimepicker({
        format: 'L'
    });
    $('.time').datetimepicker({
        format: 'LT'
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        dotsData: true,
    });


    // Testimonials carousel
    $('.testimonial-carousel').owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        dotsData: true,
    });

    
})(jQuery);

 // Get the value of the 'category' query parameter from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const category = urlParams.get('category');

        // If a category is provided, scroll to the corresponding section
        if (category) {
            const categoryElement = document.getElementById(category);
            if (categoryElement) {
                // Scroll to the category element
                categoryElement.scrollIntoView({ behavior: 'smooth' });
            }
        }
 const modal = document.querySelector('.modal');
        const modalImg = document.getElementById('modal-img');
        const modalTriggers = document.querySelectorAll('.modal-trigger');
        const closeModal = document.querySelector('.close');

        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                modal.style.display = 'block';
                modalImg.src = trigger.src;
            });
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

 lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
    });



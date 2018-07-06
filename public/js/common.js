/* common */
$(document).ready(function() {
    if($('.mask').length>0) {
        $(".mask").mask("+7 (999) 999-99-99");
    }
    //$('input, select').styler();



    function ress() {
        $('.modal-tb').width($(window).width()).height($(window).height());
        //$('.block1__background').css({'left':$('.menu1').offset().left-60});
        if($(window).width()>1190) {
            $('.block1__background').width($(window).width()-$('.menu1').offset().left+60);
        }
        else {
            $('.block1__background').width($(window).width()-$('.menu1').offset().left+30);
        }
    }
    ress();
    $(window).resize(function() {
        ress();
    });
    $(window).load(function() {
        ress();
    });
    $('.slider1').slick({
        arrows: false,
        autoplay: true,
        autoplaySpeed: 2000,
        fade: true
    });
    $('.carousel1').slick({
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 2,
        dots: true,
        responsive: [
            {
                breakpoint: 1001,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 761,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('.carousel2').slick({
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 2,
        dots: true,
        responsive: [
            {
                breakpoint: 1001,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 761,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    /*particlesJS('particles-js1',

     {
     "particles": {
     "number": {
     "value": 40,
     "density": {
     "enable": true,
     "value_area": 800
     }
     },
     "color": {
     "value": "#dfecff"
     },
     "shape": {
     "type": "circle",
     "stroke": {
     "width": 1,
     "color": "#dfecff"
     },
     "polygon": {
     "nb_sides": 5
     }
     },
     "opacity": {
     "value": 0.5,
     "random": false,
     "anim": {
     "enable": false,
     "speed": 1,
     "opacity_min": 0.1,
     "sync": false
     }
     },
     "size": {
     "value": 5,
     "random": true,
     "anim": {
     "enable": false,
     "speed": 40,
     "size_min": 0.1,
     "sync": false
     }
     },
     "line_linked": {
     "enable": true,
     "distance": 150,
     "color": "#5489ff",
     "opacity": 0.4,
     "width": 1
     },
     "move": {
     "enable": true,
     "speed": 1,
     "direction": "none",
     "random": false,
     "straight": false,
     "out_mode": "out",
     "attract": {
     "enable": false,
     "rotateX": 600,
     "rotateY": 1200
     }
     }
     },
     "retina_detect": true
     }
     );

     particlesJS('particles-js2',

     {
     "particles": {
     "number": {
     "value": 40,
     "density": {
     "enable": true,
     "value_area": 800
     }
     },
     "color": {
     "value": "#dfecff"
     },
     "shape": {
     "type": "circle",
     "stroke": {
     "width": 1,
     "color": "#dfecff"
     },
     "polygon": {
     "nb_sides": 5
     }
     },
     "opacity": {
     "value": 0.5,
     "random": false,
     "anim": {
     "enable": false,
     "speed": 1,
     "opacity_min": 0.1,
     "sync": false
     }
     },
     "size": {
     "value": 5,
     "random": true,
     "anim": {
     "enable": false,
     "speed": 40,
     "size_min": 0.1,
     "sync": false
     }
     },
     "line_linked": {
     "enable": true,
     "distance": 150,
     "color": "#5489ff",
     "opacity": 0.4,
     "width": 1
     },
     "move": {
     "enable": true,
     "speed": 1,
     "direction": "none",
     "random": false,
     "straight": false,
     "out_mode": "out",
     "attract": {
     "enable": false,
     "rotateX": 600,
     "rotateY": 1200
     }
     }
     },
     "retina_detect": true
     }
     );*/
    $('.nav-tab1__item').click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $('.content-tab1__item').eq($(this).index()).addClass('active').siblings().removeClass('active');
    });
    $('.faq-list1__title').click(function() {
        if($(this).attr('data-dt')==0) {
            $(this).attr('data-dt','1').addClass('active').next().slideDown(200).parent().siblings().find('.faq-list1__title').attr('data-dt','0').removeClass('active').next().slideUp(200);
        }
        else {
            $(this).attr('data-dt','0').removeClass('active').next().slideUp(200);
        }
    });
    $('.chose-language1__current').click(function() {
        $(this).parent().toggleClass('active');
    });
    $(document).click(function(e){
        if ($(e.target).closest(".chose-language1").length) return;
        $('.chose-language1').removeClass('active');
        e.stopPropagation();
    });
    $('.slider2').slick({
        arrows: false,
        dots: true,
        adaptiveHeight: true
    });
    $('.slider3').slick({
        arrows: false,
        dots: true,
        adaptiveHeight: true
    });
    $('.menu-button1').click(function() {
        $(this).toggleClass('active');
        $('.menu-hide1,.content-page').toggleClass('active');
    });
    $('.setting-account1__current').click(function() {
        $(this).parent().toggleClass('active');
    });
    $('.setting-account2__current').click(function() {
        $(this).parent().toggleClass('active');
    });
    $(document).click(function(e){
        if ($(e.target).closest(".setting-account1").length) return;
        $('.setting-account1').removeClass('active');
        e.stopPropagation();
    });
});
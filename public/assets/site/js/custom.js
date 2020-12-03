 $(document).ready(function() {
        $('.test-popup-link').magnificPopup({
          type:'image',
             gallery: {
                enabled: true
            },
     });
        
    $('.project-samples').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 2,
        autoplay: true,
        rtl:true,
        autoplaySpeed: 3000,
        prevArrow:"<i class='fa fa-angle-left a-left control-c prev slick-prev' aria-hidden='true'></i>",
        nextArrow:"<i class='fa fa-angle-right a-right control-c next slick-next' aria-hidden='true'></i>",
        responsive: [{

        }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 2,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 3000,
            }
        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 3000
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 3000,
            }
        }]
    });
     $("#gallery").unitegallery();
    });


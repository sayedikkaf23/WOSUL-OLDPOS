<!-- javascript -->
<script src="{{ asset('website/js/jquery-2.2.4.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('website/js/bootstrap.bundle.min.js') }}"></script>
<!-- Main Js -->
<script src="{{ asset('website/js/custome.js') }}"></script>


<script src="{{ asset('website/js/fancybox.umd.js') }}"></script>

<!--
  <script>
      $(window).on('load', function() {
          var scrollLink = $('.page-scroll');
          $(window).scroll(function() {
              var scrollbarLocation = $(this).scrollTop();
              scrollLink.each(function() {
                  var sectionOffset = $(this.hash).offset().top - 5;
                  if (sectionOffset <= scrollbarLocation) {
                      $(this).parent().addClass('active');
                      $(this).parent().siblings().removeClass('active');
                  }
              });
          });
      });
  </script>
-->

<script src="{{ asset('website/js/jarallax.js') }}"></script>
<script src="{{ asset('website/js/jarallax-video.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.jarallax').jarallax({});
    });
</script>




<script src="{{ asset('website/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('website/js/jquery-ui.js') }}"></script>
<script>
    $('.switch-language').on('selectmenuchange', function() {
        var current_url      = window.location.href;
        var lang = $(this).val();
        if (lang == 'en') {
            if(current_url.includes("/ar")){
                current_url      = current_url.replace("/ar", "/en");
                location.href = current_url;
            }else{
                location.href = current_url+'en';
            }

        } else {
            if(current_url.includes("/en")) {
                current_url = current_url.replace("/en", "/ar");
                location.href = current_url;
            }else{
                location.href = current_url+'ar';
            }
        }
    });

    function submit_demo_request() {

        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

        let formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        formData.append('first_name', $('#demo_request_first_name').val());
        formData.append('last_name', $('#demo_request_last_name').val());
        formData.append('contact_number', $('#demo_request_contact_number').val());
        formData.append('email', $('#demo_request_email').val());
        formData.append('city', $('#demo_request_city').val());
        formData.append('domain', $('#demo_request_domain').val());


        $.ajax({
            url: "{{ route('save_demo_request') }}",
            type: 'POST',
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function(resp) {

                if (resp.status_code == 200) {
                    $('.error-string').removeClass('mb-5 alert alert-danger');
                    $('.error-string').addClass('mb-5 alert alert-success');
                    $('.error-string').html(resp.msg);
                }

                if (resp.status_code != 200) {

                    let error_json = JSON.parse(resp.msg);
                    var error_string = '';
                    $.each(error_json, (key, value) => {
                        error_string += value[0] + '<br>';
                    });

                    $('.error-string').addClass('mb-5 alert alert-success');
                    $('.error-string').addClass('mb-5 alert alert-danger');
                    $('.error-string').html(error_string);
                }

            }
        });



    }

    $(document).ready(function() {

        $('.logos-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: false,
            items: 1,
            autoplay: true,
            autoplayTimeout: 5000,
            smartSpeed: 1800,
            responsive: {
                0: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 3
                }
            }
        })
        $('.gallery-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: false,
            items: 1,
            autoplay: true,
            autoplayTimeout: 5000,
            smartSpeed: 1800,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 3
                },
                992: {
                    items: 3
                },
                1199: {
                    items: 5
                }
            }
        })
        $('.review-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: false,
            items: 1,
            autoplay: true,
            autoplayTimeout: 5000,
            smartSpeed: 1800,
            stagePadding: 120,
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 40,
                },
                768: {
                    items: 2,
                    stagePadding: 80,
                },
                992: {
                    items: 3
                },
                1199: {
                    items: 4
                }
            }
        })

        $(".customSelect").selectmenu();

    });
</script>



<script src="{{ asset('website/js/aos.js') }}"></script>



<script>
    if($('#counter').length >0){
        AOS.init();


        // ----- COUNTER ----- //

        var a = 0;
        $(window).scroll(function() {
            var oTop = $('#counter').offset().top() - window.innerHeight;
            if (a == 0 && $(window).scrollTop() > oTop) {
                $('.counter-value').each(function() {
                    var $this = $(this),
                        countTo = $this.attr('data-count');
                    $({
                        countNum: $this.text()
                    }).animate({
                        countNum: countTo
                    }, {
                        duration: 2000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function() {
                            $this.text(this.countNum);
                            //alert('finished');
                        }
                    });
                });
                a = 1;
            }
        });
    }

</script>

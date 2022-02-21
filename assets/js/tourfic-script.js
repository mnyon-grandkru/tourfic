(function ($) {
    'use strict';

    $(document).ready(function () {

        // // Date picker
        // var dateToday = new Date();
        // var hotel_checkin_input = jQuery(".tf-hotel-check-in");
        // var hotel_checkout_input = jQuery(".tf-hotel-check-out");

        // var dateFormat = 'DD-MM-YYYY';

        // // Trigger Check-in Date
        // $('.tf_selectdate-wrap, #check-in-out-date').daterangepicker({
        //     "locale": {
        //         "format": dateFormat,
        //         "separator": " - ",
        //         "firstDay": 1
        //     },
        //     minDate: dateToday,
        //     autoApply: true,
        // }, function (start, end, label) {
            
        //     var checkin_input = jQuery(".tf-tour-check-in");
        //     var checkout_input = jQuery(".tf-tour-check-out");

        //     checkin_input.val(start.format(dateFormat));
        //     hotel_checkin_input.val(start.format(dateFormat));
        //     $('.tf-widget-check-in').val(start.format(dateFormat));
        //     $('.checkin-date-text').text(start.format(dateFormat));

        //     checkout_input.val(end.format(dateFormat));
        //     hotel_checkout_input.val(end.format(dateFormat));
        //     $('.tf-widget-check-out').val(end.format(dateFormat));
        //     $('.checkout-date-text').text(end.format(dateFormat));
        // });

        // //Get continuous check in out date 
        // var continuousDate = $('.tf-tour-booking-wrap').data('continuous-array');
        // let customAvailability =  $('.tf-tour-booking-wrap').data('custom-availability');
        // if(continuousDate){
        //     for(let i = 0; i < continuousDate.length; i++){            
        //         var continuousCheckIn = continuousDate[i].check_in;
        //         var continuousCheckOut = continuousDate[i].check_out;
        //     }
        // }else if(customAvailability == "no"){
        //     continuousCheckOut == false; 
        // }

        // var fixedCheckIn = $('.tf-tour-booking-wrap').data('fixed-check-in');
        // var fixedCheckOut = $('.tf-tour-booking-wrap').data('fixed-check-out');
        // if (fixedCheckIn) {
        //    var tfMinDate = fixedCheckIn;
        // } else if(customAvailability == 'no') {
        //     tfMinDate =  dateToday;
        // }else{
        //     tfMinDate =  continuousCheckIn ;
        // }
        // if (fixedCheckOut) {
        //    var tfMaxDate = fixedCheckOut;
        // } else {
        //     tfMaxDate =  continuousCheckOut;
        // }

        //position fixed of sticky tour booking form
        $(window).scroll(function(){
            var sticky = $('.tf-tour-booking-wrap'),
                scroll = $(window).scrollTop();
          
            if (scroll >= 800) sticky.addClass('tf-tours-fixed');
            else sticky.removeClass('tf-tours-fixed');
          });

        // Number Decrement
        $('.acr-dec').on('click', function (e) {

            var input = $(this).parent().find('input');
            var min = input.attr('min');

            if (input.val() > min) {
                input.val(input.val() - 1).change();
            }

        });

        // Number Increment
        $('.acr-inc').on('click', function (e) {
            var input = $(this).parent().find('input');
            input.val(parseInt(input.val()) + 1).change();
        });

        // Adults change trigger
        $(document).on('change', '#adults', function () {
            var thisVal = $(this).val();

            if (thisVal > 1) {
                $('.adults-text').text(thisVal + " Adults");
            } else {
                $('.adults-text').text(thisVal + " Adult");
            }

        });

        // Children change trigger
        $(document).on('change', '#children', function () {
            var thisVal = $(this).val();

            if (thisVal > 1) {
                $('.child-text').text(thisVal + " Children");
            } else {
                $('.child-text').text(thisVal + " Child");
            }

        });

         // Infant change trigger
         $(document).on('change', '#infant', function () {
            var thisVal = $(this).val();

            if (thisVal > 1) {
                $('.infant-text').text(thisVal + " Infants");
            } else {
                $('.infant-text').text(thisVal + " Infant");
            }

        });

        // Room change trigger
        $(document).on('change', '#room', function () {
            var thisVal = $(this).val();

            if (thisVal > 1) {
                $('.room-text').text(thisVal + " Rooms");
            } else {
                $('.room-text').text(thisVal + " Room");
            }
        });

        // Adult, Child, Room Selection toggle
        $(document).on('click', '.tf_selectperson-wrap .tf_input-inner,.tf_person-selection-wrap .tf_person-selection-inner', function () {
            $('.tf_acrselection-wrap').slideToggle('fast');
        });

        jQuery(document).on("click", function (event) {

            if (!jQuery(event.target).closest(".tf_selectperson-wrap").length) {
                jQuery(".tf_acrselection-wrap").slideUp("fast");

            }
        });

        // Comment Reply Toggle
        $(document).on('click', '#reply-title', function () {
            var $this = $(this);
            $('#commentform').slideToggle('fast', 'swing', function () {
                $this.parent().toggleClass('active');
            });
        });

        // Smooth scroll to id
        $(".reserve-button a").click(function () {
            $('html, body').animate({
                scrollTop: $("#rooms").offset().top - 32
            }, 1000);
        });

        // Ask question
        $(document).on('click', '#tf-ask-question-trigger', function (e) {
            e.preventDefault();
            $('#tf-ask-question').fadeIn().find('.response').html("");
        });

        // Close Ask question
        $(document).on('click', 'span.close-aq', function () {
            $('#tf-ask-question').fadeOut();
        });

        // Ask question Submit
        $(document).on('submit', 'form#ask-question', function (e) {
            e.preventDefault();

            var $this = $(this);

            var formData = new FormData(this);
            formData.append('action', 'tf_ask_question');

            $.ajax({
                type: 'post',
                url: tf_params.ajax_url,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function (data) {
                    $this.block({
                        message: null,
                        overlayCSS: {
                            background: "#fff",
                            opacity: .5
                        }
                    });

                    $this.find('.response').html("Sending your question...");
                },
                complete: function (data) {
                    $this.unblock();
                },
                success: function (data) {
                    $this.unblock();

                    var response = JSON.parse(data);

                    if (response.status == 'sent') {
                        $this.find('.response').html(response.msg);

                        $this.find('[type="reset"]').trigger('click');
                    } else {
                        $this.find('.response').html(response.msg);
                    }
                },
                error: function (data) {
                    console.log(data);

                },

            });

        });



        // Change view
        $(document).on('click', '.change-view', function (e) {
            e.preventDefault();
            $('.change-view').removeClass('active');
            $(this).addClass('active');

            var dataid = $(this).data('id');
            if (dataid == 'grid-view') {
                $('.archive_ajax_result').addClass('tours-grid');
            } else {
                $('.archive_ajax_result').removeClass('tours-grid');
            }

        });

        // Change view
        
        var filter_xhr;
        $(document).on('change', '[name*=tf_filters],[name*=tf_features], #destination, #adults, #room, #children, #check-in-date, #check-out-date, #check-in-out-date', function () {
            var dest = $('#destination').val();
            var adults = $('#adults').val();
            var room = $('#room').val();
            var children = $('#children').val();
            var checkin = $('#check-in-date').val();
            var checkout = $('#check-out-date').val();
            var posttype = $('.tf-post-type').val();

            var filters = [];

            $('[name*=tf_filters]').each(function () {
                if ($(this).is(':checked')) {
                    filters.push($(this).val());
                }
            });
            var filters = filters.join();

            var features = [];

            $('[name*=tf_features]').each(function () {
                if ($(this).is(':checked')) {
                    features.push($(this).val());
                }
            });
            var features = features.join();

            var formData = new FormData();
            formData.append('action', 'tf_trigger_filter');
            formData.append('type', posttype);
            formData.append('dest', dest);
            formData.append('adults', adults);
            formData.append('room', room);
            formData.append('children', children);
            formData.append('checkin', checkin);
            formData.append('checkout', checkout);
            formData.append('filters', filters);
            formData.append('features', features);

            // abort previous request
            if (filter_xhr && filter_xhr.readyState != 4) {
                filter_xhr.abort();
            }

            filter_xhr = $.ajax({
                type: 'post',
                url: tf_params.ajax_url,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function (data) {
                    $('.archive_ajax_result').block({
                        message: null,
                        overlayCSS: {
                            background: "#fff",
                            opacity: .5
                        }
                    });

                },
                complete: function (data) {
                    $('.archive_ajax_result').unblock();
                },
                success: function (data) {
                    $('.archive_ajax_result').unblock();

                    $('.archive_ajax_result').html(data);
                },
                error: function (data) {
                    console.log(data);
                },

            });

        });

    });

})(jQuery);

// Infinite Scroll
(function ($) {
    'use strict';

    $(document).ready(function () {

        var flag = false;
        var main_xhr;

        var amPushAjax = function (url) {
            if (main_xhr && main_xhr.readyState != 4) {
                main_xhr.abort();
            }



            main_xhr = $.ajax({
                url: url,
                contentType: false, // Not to set any content header
                processData: false, // Not to process data
                asynch: true,
                beforeSend: function () {

                    $(document).find('.tf_posts_navigation').addClass('loading');
                    flag = true;
                },
                success: function (data) {
                    //console.log(data);
                    $('.archive_ajax_result').append($('.archive_ajax_result', data).html());

                    $('.tf_posts_navigation').html($('.tf_posts_navigation', data).html());

                    //document.title = $(data).filter('title').text();

                    flag = false;

                    $(document).find('.tf_posts_navigation').removeClass('loading');

                }
            });

            //console.log(main_xhr);
        };

        // Feed Ajax Trigger
        $(document).on('click', '.tf_posts_navigation a.next.page-numbers', function (e) {
            e.preventDefault();

            var targetUrl = (e.target.href) ? e.target.href : $(this).context.href;
            amPushAjax(targetUrl);
            window.history.pushState({ url: "" + targetUrl + "" }, "", targetUrl);
        });
        // End Feed Ajax Trigger

        // Feed Click Trigger
        $(window).on('scroll', function (e) {
            $('.tf_posts_navigation a.next.page-numbers').each(function (i, el) {

                var $this = $(this);

                var H = $(window).height(),
                    r = el.getBoundingClientRect(),
                    t = r.top,
                    b = r.bottom;

                var tAdj = parseInt(t - (H / 2));

                if (flag === false && (H >= tAdj)) {
                    //console.log( 'inview' );
                    $this.trigger('click');
                } else {
                    //console.log( 'outview' );
                }
            });
        });
        // End Feed Click Trigger

        //Ratings copy/move under gallery
        var avg_rating = $('.tf-overall-ratings .overall-rate').text();
        if(avg_rating){
            $('.reviews span').html(avg_rating);
        }else{
            $('.reviews span').html("0/5");
        }

        //code from J
        // $(".tf-suggestion-items-wrapper").owlCarousel({            
        //     margin:30,
        //     stagePadding: 20,
        //     loop:true,
        //     nav:true,
        //     dots:false,
        //     responsive: {
        //         0 : {
        //             items:1,
        //         },
        //         1000 : {
        //             items:2,
        //         },
        //         1241 : {
        //             items:3,
        //         }
        //     }
        // });
        // $(".tf-review-items-wrapper").owlCarousel({            
        //     margin:30,
        //     stagePadding: 20,
        //     loop:true,
        //     nav:true,
        //     dots:false,
        //     items:4,

        //     responsive: {
        //         0 : {
        //             items:1,
        //         },
        //         600 : {
        //             items:2,
        //         },
        //         1000 : {
        //             items:3,
        //         },
        //         1241 : {
        //             items:4,
        //         }
        //     }

        // });
        $(".tf-travel-text h4").click(function(){
            $(this).siblings('.tf-travel-contetn').slideToggle();
            $(this).parents('.tf-travel-itinerary-item').siblings().find('.tf-travel-contetn').slideUp();
        });
        $(".tf-faq-title").click(function(){
            $(this).siblings('.tf-faq-desc').slideToggle();
            $(this).parents('.tf-faq-item').siblings().find('.tf-faq-desc').slideUp();
        });

        
        $(".tf-header-menu-triger").click(function(){
            $('.tf-header-menu-wrap').slideToggle();
        });

    });

})(jQuery);


/*
* Trourfic autocomplete destination
*/
function tourfic_autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false; }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        var $notfound = [];
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                $notfound.push('found');

                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);

            } else {
                $notfound.push('notfound');
            }
        }

        if ($notfound.indexOf('found') == -1) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/

            b.innerHTML += 'Not Found';
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value=''>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function (e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

//get the tours and hotel destination array
var destinations = tf_params.destinations;
var tour_destinations = tf_params.tour_destinations;
let dest = document.getElementById("destination");
let tourDest = document.getElementById("tour_destination");

if(dest){
    //Autocomplete for Hotel
    tourfic_autocomplete(dest, destinations);
}

if(tourDest){
    //Autocomplete for Tours
    tourfic_autocomplete(tourDest, tour_destinations);
}

/**
 * Searchbox widgets tab scripts
 */
function tfOpenForm(evt, formName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tf-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
     
    }
    tablinks = document.getElementsByClassName("tf-tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(formName).style.display = "block";
    document.getElementById(formName).style.transition = "all 0.2s";
    evt.currentTarget.className += " active";
}
jQuery('#tf-hotel-booking-form').css('display','block');

(function ($, win) {
    $(document).ready(function () {

        // Create an instance of Notyf
        const notyf = new Notyf({
            ripple: true,
            duration: 3000,
            dismissable: true,
            position: {
                x: 'right',
                y: 'bottom',
            },
        });

        
        // FAQ Accordion
        $('.tf-faq-head').on("click", function () {
            var $this = $(this);
            if (!$this.hasClass("active")) {
                $(".tf-question-desc").slideUp(400);
                $(".tf-faq-head").removeClass("active");
                $('.tf-faq-col').removeClass('active');
            }
            $this.toggleClass("active");
            $this.next().slideToggle();
            $(this).closest('.tf-faq-col').toggleClass('active');
        });

        // Tabs Section
        $('.tf-details-menu ul li').on("click", function () {
            var $this = $(this);
            $('.tf-details-menu ul li').removeClass('active');
            $this.addClass("active");
        });

        // Car Location Autocomplete

        function tourfic_car_autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/

            // Executes when some one click in the search form location
            inp.addEventListener("focus", function () {
                if (this.value == '' || !this.value) {
                    // alert("Working....")
                    let a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.classList.add("autocomplete-items")
                    this.parentNode.appendChild(a);
                    for (const [key, value] of Object.entries(arr)) {
                        let b = document.createElement("DIV");
                        b.innerHTML = value;
                        b.innerHTML += `<input type='hidden' value="${value}" data-slug='${key}'>`;
                        b.addEventListener("click", function (e) {
                            let source = this.getElementsByTagName("input")[0];
                            inp.value = source.value;
                            inp.closest('input').nextElementSibling.value = source.dataset.slug
                        });
                        a.appendChild(b);
                    }
                }
            })

            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("keyup", function (e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                var $notfound = [];
                /*for each item in the array...*/
                for (const [key, value] of Object.entries(arr)) {
                    if (value.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        $notfound.push('found');
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + value.substr(0, val.length) + "</strong>";
                        b.innerHTML += value.substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += `<input type='hidden' value="${value}" data-slug='${key}'> `;
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function (e) {
                            let source = this.getElementsByTagName("input")[0];
                            /*insert the value for the autocomplete text field:*/
                            inp.value = source.value;
                            inp.closest('input').nextElementSibling.value = source.dataset.slug //source.dataset.slug
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

                    b.innerHTML += tf_params.no_found;
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
                        if (x) x[currentFocus].trigger("click");;
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
                // closeAllLists(e.target);
                if (e.target.id == "content" || e.target.id == "") {
                    closeAllLists(e.target);
                }
            });
        }

        // Car location autocomplete
        var car_pickup_input = document.getElementById("tf_pickup_location");
        var car_locations = tf_params.car_locations;
        if (car_pickup_input) {
            tourfic_car_autocomplete(car_pickup_input, car_locations);
        }

        var car_dropoff_input = document.getElementById("tf_dropoff_location");
        if (car_dropoff_input) {
            tourfic_car_autocomplete(car_dropoff_input, car_locations);
        }

        /*
        * Add Extra
        * @author Jahid
        */
        $(document).on('submit', '.tf-car-extra-infos', function (e) {
            e.preventDefault();
            let form = $(this);
            const formData = new FormData(e.target);
            submitBtn = form.find('.tf-extra-submit'),
            formData.append('action', 'tf_extra_add_to_booking');
            formData.append('_nonce', tf_params.nonce);

        
            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    form.css({'opacity': '0.5', 'pointer-events': 'none'});
                    submitBtn.addClass('tf-btn-loading');
                },
                success: function (response) {
                    form.css({'opacity': '1', 'pointer-events': 'all'});
                    submitBtn.removeClass('tf-btn-loading');
                    $('.tf-added-extra').html(response);
                    if(response){
                        $('.tf-extra-added-info').show();
                    }
                }
            });

        });

        $(document).on('click', '.tf-single-added-extra .delete', function (e) {
            e.preventDefault();
            let $this = $(this);
            $this.closest('.tf-single-added-extra').remove();
            var count = $('.tf-added-extra .tf-single-added-extra').length;
            if(count==0){
                $('.tf-extra-added-info').hide();
            }
        });

        $(document).on('click', '.tf-booking-popup-header .tf-close-popup', function (e) {
            e.preventDefault();
            $('.tf-car-booking-popup').hide();
        });

        /*
        * Car Booking Popup
        * @author Jahid
        */
        $(document).on('click', '.tf-car-booking', function (e) {
            e.preventDefault();
            $this = $(this);
            $('.tf-booking-content-wraper').html("");
            var pickup = $('#tf_pickup_location').val();
            let dropoff = $('#tf_dropoff_location').val();
            let pickup_date = $('.tf_pickup_date').val();
            let dropoff_date = $('.tf_dropoff_date').val();
            let pickup_time = $('.tf_pickup_time').val();
            let dropoff_time = $('.tf_dropoff_time').val();
            let post_id = $('#post_id').val();

            if( !pickup || !dropoff || !pickup_date || !dropoff_date || !pickup_time || !dropoff_time ){
                $('.error-notice').show();
                $('.error-notice').text('Fill up the all fields');
                return;
            }

            var data = {
                action: 'tf_car_booking_pupup',
                _nonce: tf_params.nonce,
                post_id: post_id
            };

            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    $this.addClass('tf-btn-loading');
                },
                success: function (data) {
                    $('.tf-booking-content-wraper').html(data);
                    $('.error-notice').hide();
                    $('.tf-car-booking-popup').css('display', 'flex');
                    $this.removeClass('tf-btn-loading');
                }
            });

        });

        /*
        * Car Archive Booking Popup
        * @author Jahid
        */
        $(document).on('click', '.tf-car-quick-booking', function (e) {
            e.preventDefault();
            $this = $(this);
            $('.tf-booking-content-wraper').html("");
            let post_id = $this.closest('.tf-booking-btn').find('#post_id').val();

            var data = {
                action: 'tf_car_booking_pupup',
                _nonce: tf_params.nonce,
                post_id: post_id
            };

            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    $this.addClass('tf-btn-loading');
                },
                success: function (data) {
                    $this.closest('.tf-booking-btn').find('.tf-booking-content-wraper').html(data);
                    $this.closest('.tf-booking-btn').find('.tf-car-booking-popup').css('display', 'flex');
                    $this.removeClass('tf-btn-loading');
                }
            });

        });

        $(document).on('click', '.booking-next', function (e) {
            let $this = $(this);
            $('.tf-booking-tabs ul li').removeClass('active');
            $('.tf-booking-tabs ul li.booking').addClass('active');

            $('.tf-protection-content').hide();
            $('.tf-booking-bar').hide();

            $('.tf-booking-form-fields').show();

            $('#protection_value').val($this.attr('data-charge'));
        });

        /*
        * Car Booking
        * @author Jahid
        */

        const BookingVallidation = (booking) => {
            let hasErrors = [];
            
            $('.error-text').text("");
            booking.find('.tf-single-field').each(function () {
                $(this).find('input, select').each(function () {
                    if ($(this).attr('data-required')) {
                        if ($(this).val() == "") {
                            hasErrors.push(true);
                            const errorContainer = $(this).siblings('.error-text');
                            errorContainer.text('This field is required.');
                            if (errorContainer.text() !== '') {
                                errorContainer.addClass('error-visible');
                            } else {
                                errorContainer.removeClass('error-visible');
                            }
                        }
                    }
                });
                $(this).find('input[type="radio"], input[type="checkbox"]').each(function () {
                    if ($(this).attr('data-required')) {
                        const radioName = $(this).attr('name');
                        const isChecked = $('input[name="' + radioName + '"]:checked').length > 0;

                        if (!isChecked) {
                            hasErrors.push(true);
                            const errorContainer = $(this).parent().siblings('.error-text');
                            errorContainer.text('This field is required.');
                            if (errorContainer.text() !== '') {
                                errorContainer.addClass('error-visible');
                            } else {
                                errorContainer.removeClass('error-visible');
                            }
                        }
                    }
                });
            });
            if (hasErrors.includes(true)) {
                return true;
            }
        }

        $(document).on('click', '.tf-car-booking-form .booking-process', function (e) {
            let $this = $(this);
            if($this.attr('data-charge')){
                $('#protection_value').val($this.attr('data-charge'));
            }
            let extra_ids = $("input[name='selected_extra[]']").map(function() {
                return $(this).val();
            }).get();

            let extra_qty = $("input[name='selected_qty[]']").map(function() {
                return $(this).val();
            }).get();

            var travellerData = {};
            if($this.hasClass('tf-offline-booking')){
                let booking = $(this).closest('.tf-booking-form-fields');
                let Validation_response = BookingVallidation(booking);
                if(Validation_response){
                    return;
                }
                // Text, email, date inputs
                $("input[name^='traveller[']").each(function() {
                    var name = $(this).attr('name').replace(/^traveller\[(.*)\]$/, '$1'); // Remove 'traveller_' prefix
                    travellerData[name] = $(this).val();
                });

                // Select dropdowns
                $("select[name^='traveller[']").each(function() {
                    var name = $(this).attr('name').replace(/^traveller\[(.*)\]$/, '$1'); // Remove 'traveller_' prefix
                    travellerData[name] = $(this).val();
                });

                // Checkbox and Radio buttons
                $("input[type='checkbox'][name^='traveller[']:checked, input[type='radio'][name^='traveller[']:checked").each(function() {
                    var name = $(this).attr('name').replace(/^traveller\[(.*)\]$/, '$1'); // Remove 'traveller_' prefix
                    if (!travellerData[name]) {
                        travellerData[name] = [];
                    }
                    travellerData[name].push($(this).val());
                });

            }
    
            if($this.hasClass('tf-final-step')){
                var pickup = $('#tf_pickup_location').val();
                let dropoff = $('#tf_dropoff_location').val();
                let pickup_date = $('.tf_pickup_date').val();
                let dropoff_date = $('.tf_dropoff_date').val();
                let pickup_time = $('.tf_pickup_time').val();
                let dropoff_time = $('.tf_dropoff_time').val();

                if( !pickup || !dropoff || !pickup_date || !dropoff_date || !pickup_time || !dropoff_time ){
                    $('.error-notice').show();
                    $('.error-notice').text('Fill up the all fields');
                    return;
                }
            }

            $('.error-notice').hide();

            var pickup = $('#tf_pickup_location').val();
            let dropoff = $('#tf_dropoff_location').val();
            let pickup_date = $('.tf_pickup_date').val();
            let dropoff_date = $('.tf_dropoff_date').val();
            let pickup_time = $('.tf_pickup_time').val();
            let dropoff_time = $('.tf_dropoff_time').val();
            let post_id = $('#post_id').val();
            let protection = $('#protection_value').val();

            var data = {
                action: 'tf_car_booking',
                _nonce: tf_params.nonce,
                post_id: post_id,
                pickup: pickup,
                dropoff: dropoff,
                pickup_date: pickup_date,
                dropoff_date: dropoff_date,
                pickup_time: pickup_time,
                dropoff_time: dropoff_time,
                protection: protection,
                extra_ids: extra_ids,
                extra_qty: extra_qty,
                travellerData: travellerData
            };

            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    $this.addClass('tf-btn-loading');
                },
                success: function (data) {
                    $this.unblock();

                    var response = JSON.parse(data);
                    if (response.status == 'error') {

                        if (response.errors) {
                            response.errors.forEach(function (text) {
                                notyf.error(text);
                            });
                        }

                        $('.tf-car-booking-popup').hide();
                        $this.removeClass('tf-btn-loading');
                        if($('.tf-protection-content')){
                            $('.tf-protection-content').show();
                            $('.tf-booking-bar').show();
                            $('.tf-booking-form-fields').hide();

                            $('.tf-booking-tabs ul li').removeClass('active');
                            $('.tf-booking-tabs ul li.protection').addClass('active');
                        }else{
                            $('.tf-booking-form-fields').show();
                            $('.tf-booking-tabs ul li').removeClass('active');
                            $('.tf-booking-tabs ul li.booking').addClass('active');
                        }
                        return false;
                    } else {
                        if (response.without_payment == 'false') {
                            if (response.status == 'error') {

                                if (response.errors) {
                                    response.errors.forEach(function (text) {
                                        notyf.error(text);
                                    });
                                }

                                return false;
                            } else {

                                if (response.redirect_to) {
                                    window.location.replace(response.redirect_to);
                                } else {
                                    jQuery(document.body).trigger('added_to_cart');
                                }

                            }
                        }else{
                            $('.tf-car-booking-popup').hide();
                            $this.removeClass('tf-btn-loading');
                            $('#tf_pickup_location').val('');
                            $('#tf_dropoff_location').val('');
                            $('.tf_pickup_date').val('');
                            $('.tf_dropoff_date').val('');
                            $('.tf_pickup_time').val('');
                            $('.tf_dropoff_time').val('');
                            if($('.tf-protection-content')){
                                $('.tf-protection-content').show();
                                $('.tf-booking-bar').show();
                                $('.tf-booking-form-fields').hide();

                                $('.tf-booking-tabs ul li').removeClass('active');
                                $('.tf-booking-tabs ul li.protection').addClass('active');
                            }else{
                                $('.tf-booking-form-fields').show();
                                $('.tf-booking-tabs ul li').removeClass('active');
                                $('.tf-booking-tabs ul li.booking').addClass('active');
                            }
                        }
                    }
                }
            });

        });

        /*
        * Car Search submit
        * @author Jahid
        */
        $(document).on('submit', '#tf_car_booking', function (e) {
            e.preventDefault();
            let form = $(this),
                submitBtn = form.find('.tf-submit'),
                formData = new FormData(form[0]);
            
            formData.append('action', 'tf_car_search');
            formData.append('_nonce', tf_params.nonce);

            if (formData.get('from') == null || formData.get('to') == null) {
                formData.append('from', tf_params.tf_car_min_price);
                formData.append('to', tf_params.tf_car_max_price);
            }

            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    form.css({'opacity': '0.5', 'pointer-events': 'none'});
                    submitBtn.addClass('tf-btn-loading');
                },
                success: function (response) {
                    let obj = JSON.parse(response);
                    form.css({'opacity': '1', 'pointer-events': 'all'});
                    submitBtn.removeClass('tf-btn-loading');
                    if (obj.status === 'error') {
                        notyf.error(obj.message);
                    }
                    if (obj.status === 'success') {
                        //location redirect to form action url with query string
                        location.href = form.attr('action') + '?' + obj.query_string;
                    }
                }
            });
        });

        /*
        * Car Quick Booking
        * @author Jahid
        */

        $(document).on('click', '.quick-booking', function (e) {
            let $this = $(this);

            var pickup = $('#tf_pickup_location').val();
            let dropoff = $('#tf_dropoff_location').val();
            let pickup_date = $this.closest('.tf-booking-btn').find('#pickup_date').val();
            let dropoff_date = $this.closest('.tf-booking-btn').find('#dropoff_date').val();
            let pickup_time = $this.closest('.tf-booking-btn').find('#pickup_time').val();
            let dropoff_time = $this.closest('.tf-booking-btn').find('#dropoff_time').val();
            let post_id = $this.closest('.tf-booking-btn').find('#post_id').val();

            var data = {
                action: 'tf_car_booking',
                _nonce: tf_params.nonce,
                post_id: post_id,
                pickup: pickup,
                dropoff: dropoff,
                pickup_date: pickup_date,
                dropoff_date: dropoff_date,
                pickup_time: pickup_time,
                dropoff_time: dropoff_time
            };

            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    $this.addClass('tf-btn-loading');
                },
                success: function (data) {
                    $this.unblock();

                    var response = JSON.parse(data);
                    if (response.without_payment == 'false') {
                        if (response.status == 'error') {

                            if (response.errors) {
                                response.errors.forEach(function (text) {
                                    notyf.error(text);
                                });
                            }

                            return false;
                        } else {

                            if (response.redirect_to) {
                                window.location.replace(response.redirect_to);
                            } else {
                                jQuery(document.body).trigger('added_to_cart');
                            }

                        }
                    }
                }
            });

        });

        $(document).on('click', '.tf-booking-btn .booking-process', function (e) {
            let $this = $(this);
            if($this.attr('data-charge')){
                $('#protection_value').val($this.attr('data-charge'));
            }


            var travellerData = {};
            if($this.hasClass('tf-offline-booking')){
                let booking = $(this).closest('.tf-booking-form-fields');
                let Validation_response = BookingVallidation(booking);
                if(Validation_response){
                    return;
                }
                // Text, email, date inputs
                $("input[name^='traveller[']").each(function() {
                    var name = $(this).attr('name');
                    travellerData[name] = $(this).val();
                });

                // Select dropdowns
                $("select[name^='traveller[']").each(function() {
                    var name = $(this).attr('name');
                    travellerData[name] = $(this).val();
                });

                // Checkbox and Radio buttons
                $("input[type='checkbox'][name^='traveller[']:checked, input[type='radio'][name^='traveller[']:checked").each(function() {
                    var name = $(this).attr('name');
                    if (!travellerData[name]) {
                        travellerData[name] = [];
                    }
                    travellerData[name].push($(this).val());
                });
            }
    
            var pickup = $('#tf_pickup_location').val();
            let dropoff = $('#tf_dropoff_location').val();
            let pickup_date = $this.closest('.tf-booking-btn').find('#pickup_date').val();
            let dropoff_date = $this.closest('.tf-booking-btn').find('#dropoff_date').val();
            let pickup_time = $this.closest('.tf-booking-btn').find('#pickup_time').val();
            let dropoff_time = $this.closest('.tf-booking-btn').find('#dropoff_time').val();
            let post_id = $this.closest('.tf-booking-btn').find('#post_id').val();
            let protection = $this.closest('.tf-booking-btn').find('#protection_value').val();

            var data = {
                action: 'tf_car_booking',
                _nonce: tf_params.nonce,
                post_id: post_id,
                pickup: pickup,
                dropoff: dropoff,
                pickup_date: pickup_date,
                dropoff_date: dropoff_date,
                pickup_time: pickup_time,
                dropoff_time: dropoff_time,
                protection: protection,
                travellerData: travellerData
            };
            
            $.ajax({
                url: tf_params.ajax_url,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    $this.addClass('tf-btn-loading');
                },
                success: function (data) {
                    $this.unblock();

                    var response = JSON.parse(data);
                    if (response.status == 'error') {

                        if (response.errors) {
                            response.errors.forEach(function (text) {
                                notyf.error(text);
                            });
                        }

                        $('.tf-car-booking-popup').hide();
                        $this.removeClass('tf-btn-loading');
                        return false;
                    } else {
                        if (response.without_payment == 'false') {
                            if (response.status == 'error') {

                                if (response.errors) {
                                    response.errors.forEach(function (text) {
                                        notyf.error(text);
                                    });
                                }

                                return false;
                            } else {

                                if (response.redirect_to) {
                                    window.location.replace(response.redirect_to);
                                } else {
                                    jQuery(document.body).trigger('added_to_cart');
                                }

                            }
                        }else{
                            $('.tf-car-booking-popup').hide();
                            $this.removeClass('tf-btn-loading');
                        }
                    }
                }
            });

        });

        /*
        * Car Archive View
        * @author Jahid
        */
         $(document).on('click', '.tf-archive-header .tf-archive-view ul li', function (e) {
            $('.tf-archive-header .tf-archive-view ul li').removeClass('active');
            let $this = $(this);
            $this.addClass('active');
            let view = $this.attr('data-view');
   
            if(view=='grid'){
                console.log(view);
                $('.tf-car-details-column .tf-car-archive-result .tf-car-result').removeClass('list-view');
                $('.tf-car-details-column .tf-car-archive-result .tf-car-result').addClass('grid-view');
            }else{
                $('.tf-car-details-column .tf-car-archive-result .tf-car-result').addClass('list-view');
                $('.tf-car-details-column .tf-car-archive-result .tf-car-result').removeClass('grid-view');
            }
        });

        // Booking Bar Show
        $(window).scroll(function() {
            // Check the position of the target div
            var targetOffset = $('.tf-date-select-box').offset().top;
            var targetHeight = $('.tf-date-select-box').outerHeight(); // Get the full height of the div including padding
            var targetBottom = targetOffset + targetHeight;

            var scrollPosition = $(window).scrollTop();
    
            // If the user has scrolled past the target div, show the other div
            if (scrollPosition > targetBottom) {
                $('.tf-single-booking-bar').fadeIn(); // You can change this to show() or add animations
            } else {
                $('.tf-single-booking-bar').fadeOut();
            }
        });

        // Back to Booking Form
        $(document).on('click', '.tf-back-to-booking', function (e) {
            e.preventDefault(); // Prevent any default action (optional if button is a link)
            $('.tf-single-booking-bar').fadeOut();
            // Scroll to the .tf-date-select-box div
            $('html, body').animate({
                scrollTop: $('.tf-date-select-box').offset().top
            }); // 800 is the duration of the scroll animation in milliseconds (adjust as needed)
        });        

    });

})(jQuery, window);
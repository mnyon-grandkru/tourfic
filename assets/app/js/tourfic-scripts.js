(()=>{var t,e;function a(t,e){var a,n,o;for(n=document.getElementsByClassName("tf-tabcontent"),a=0;a<n.length;a++)n[a].style.display="none";for(o=document.getElementsByClassName("tf-tablinks"),a=0;a<o.length;a++)o[a].className=o[a].className.replace(" active","");document.getElementById(e).style.display="block",document.getElementById(e).style.transition="all 0.2s",t.target.className+=" active"}t=jQuery,e=window,t(document).ready((function(){const n=new Notyf({ripple:!0,duration:3e3,dismissable:!0,position:{x:"right",y:"bottom"}}),o=()=>{if(""!=t.trim(t("input[name=check-in-out-date]").val())){var e=[];t(".tf-room-checkbox :checkbox:checked").each((function(a){e[a]=t(this).val()}));var a=t("input[name=tf_room_avail_nonce]").val(),n=t("input[name=post_id]").val(),o=t("select[name=adults] option").filter(":selected").val(),i=t("select[name=children] option").filter(":selected").val(),s=t("input[name=children_ages]").val(),r=t("input[name=check-in-out-date]").val(),l={action:"tf_room_availability",tf_room_avail_nonce:a,post_id:n,adult:o,child:i,features:e,children_ages:s,check_in_out:r};jQuery.ajax({url:tf_params.ajax_url,type:"post",data:l,success:function(e){t("html, body").animate({scrollTop:t("#rooms").offset().top},500),t("#rooms").html(e),t(".tf-room-filter").show()},error:function(t){console.log(t)}})}else 0===t("#tf-required").length&&t(".tf_booking-dates .tf_label-row").append('<span id="tf-required" class="required"><b>'+tf_params.field_required+"</b></span>")};t(document).on("click","#tf-single-hotel-avail .tf-submit",(function(t){t.preventDefault(),o()})),t(document).on("change",".tf-room-checkbox :checkbox",(function(){o()})),t(document).on("click",".hotel-room-availability",(function(e){e.preventDefault(),t("html, body").animate({scrollTop:t("#tf-single-hotel-avail").offset().top},500)})),t(document).on("click",".hotel-room-book",(function(e){e.preventDefault();var a=t(this),o=t("input[name=tf_room_booking_nonce]").val(),i=t("input[name=post_id]").val();if(t(this).closest(".room-submit-wrap").find("input[name=room_id]").val())var s=t(this).closest(".room-submit-wrap").find("input[name=room_id]").val();else s=t("#hotel_roomid").val();if(t(this).closest(".room-submit-wrap").find("input[name=unique_id]").val())var r=t(this).closest(".room-submit-wrap").find("input[name=unique_id]").val();else r=t("#hotel_room_uniqueid").val();var l=t("input[name=place]").val(),c=t("input[name=adult]").val(),d=t("input[name=child]").val(),f=t("input[name=children_ages]").val(),u=t("input[name=check_in_date]").val(),p=t("input[name=check_out_date]").val();if(t(this).closest(".reserve").find("select[name=hotel_room_selected] option").filter(":selected").val())var h=t(this).closest(".reserve").find("select[name=hotel_room_selected] option").filter(":selected").val(),m=t(this).closest(".room-submit-wrap").find("input[name=make_deposit]").is(":checked");else h=t("#hotel_room_number").val(),m=t("#hotel_room_depo").val();var _={action:"tf_hotel_booking",tf_room_booking_nonce:o,post_id:i,room_id:s,unique_id:r,location:l,adult:c,child:d,children_ages:f,check_in_date:u,check_out_date:p,room:h,deposit:m,airport_service:t(".fancybox-slide #airport-service").val()};t.ajax({type:"post",url:tf_params.ajax_url,data:_,beforeSend:function(e){a.block({message:null,overlayCSS:{background:"#fff",opacity:.5}}),t(".tf_notice_wrapper").html("").hide()},complete:function(t){a.unblock()},success:function(t){a.unblock();var e=JSON.parse(t);if("error"==e.status)return e.errors&&e.errors.forEach((function(t){n.error(t)})),!1;e.redirect_to?window.location.replace(e.redirect_to):jQuery(document.body).trigger("added_to_cart")},error:function(t){console.log(t)}})})),t('[data-fancybox="hotel-gallery"]').fancybox({loop:!0,buttons:["zoom","slideShow","fullScreen","close"],hash:!1});var i,s=t(".swiper-button-prev"),r=t(".swiper-button-next");t(".single-slider-wrapper .tf_slider-for").slick({slide:".slick-slide-item",slidesToShow:1,slidesToScroll:1,arrows:!1,fade:!1,dots:!1,centerMode:!1,variableWidth:!1,adaptiveHeight:!0}),s.on("click",(function(){t(this).closest(".single-slider-wrapper").find(".tf_slider-for").slick("slickPrev")})),r.on("click",(function(){t(this).closest(".single-slider-wrapper").find(".tf_slider-for").slick("slickNext")})),t(".reserve-button a").click((function(){t("html, body").animate({scrollTop:t("#rooms").offset().top-32},1e3)})),t(".tf-top-review a").click((function(){t("html, body").animate({scrollTop:t("#tf-review").offset().top-32},1e3)})),t(".tf-map-link a").click((function(){t("html, body").animate({scrollTop:t("#tour-map").offset().top-32},1e3)})),t(document).on("submit","form.tf_tours_booking",(function(e){e.preventDefault();var a=t(this),o=new FormData(this);o.append("action","tf_tours_booking");var i=0;jQuery(".tour-extra-single input:checkbox:checked").each((function(){i+=isNaN(parseInt(jQuery(this).val()))?0:parseInt(jQuery(this).val())})),o.append("tour_extra_total",i);var s=t(".tour-extra-single input:checkbox:checked").map((function(){return t(this).data("title")})).get();o.append("tour_extra_title",s),t.ajax({type:"post",url:tf_params.ajax_url,data:o,processData:!1,contentType:!1,beforeSend:function(e){a.block({message:null,overlayCSS:{background:"#fff",opacity:.5}}),t(".tf-notice-wrapper").html("").hide()},complete:function(t){a.unblock()},success:function(e){a.unblock();var o=JSON.parse(e);if("error"==o.status)return t.fancybox.close(),o.errors&&o.errors.forEach((function(t){n.error(t)})),!1;o.redirect_to?window.location.replace(o.redirect_to):jQuery(document.body).trigger("added_to_cart"),console.log(o)},error:function(t){console.log(t)}})})),t('[data-fancybox="tour-gallery"]').fancybox({loop:!0,buttons:["zoom","slideShow","fullScreen","close"],hash:!1}),t(".tf-itinerary-gallery").fancybox({buttons:["zoom","slideShow","fullScreen","close"]}),t(document).on("click",".tf-single-tour-pricing .tf-price-tab li",(function(){var e=t(this).attr("id");t(this).addClass("active").siblings().removeClass("active"),t(".tf-price").addClass("tf-d-n"),t("."+e+"-price").removeClass("tf-d-n")})),t(".tf-single-tour-pricing .tf-price-tab li:first-child").trigger("click");const l=()=>{var e=t("#tf-place").val(),a=t("#adults").val(),o=t("#room").val(),s=t("#children").val(),r=t("#check-in-out-date").val(),l=t("#startprice").val(),c=t("#endprice").val(),d=t("#tf_author").val(),f=r.split(" - "),u=f[0],p=f[1],h=t(".tf-post-type").val();if(""===t.trim(u)&&tf_params.date_hotel_search&&"tf_hotel"===h)0===t("#tf-required").length&&t(".tf_booking-dates .tf_label-row").append('<span id="tf-required" class="required" style="color:white;"><b>'+tf_params.field_required+"</b></span>");else if(""===t.trim(u)&&tf_params.date_tour_search&&"tf_tours"===h)0===t("#tf-required").length&&t(".tf_booking-dates .tf_label-row").append('<span id="tf-required" class="required" style="color:white;"><b>'+tf_params.field_required+"</b></span>");else{var m=[];t("[name*=tf_filters]").each((function(){t(this).is(":checked")&&m.push(t(this).val())})),m=m.join();var _=[];t("[name*=tf_features]").each((function(){t(this).is(":checked")&&_.push(t(this).val())})),_=_.join();var v=[];t("[name*=tour_features]").each((function(){t(this).is(":checked")&&v.push(t(this).val())})),v=v.join();var g=[];t("[name*=tf_attractions]").each((function(){t(this).is(":checked")&&g.push(t(this).val())})),g=g.join();var k=[];t("[name*=tf_activities]").each((function(){t(this).is(":checked")&&k.push(t(this).val())})),k=k.join();var b=new FormData;b.append("action","tf_trigger_filter"),b.append("type",h),b.append("dest",e),b.append("adults",a),b.append("room",o),b.append("children",s),b.append("checkin",u),b.append("checkout",p),b.append("filters",m),b.append("features",_),b.append("tour_features",v),b.append("attractions",g),b.append("activities",k),b.append("checked",r),l&&b.append("startprice",l),c&&b.append("endprice",c),d&&b.append("tf_author",d),i&&4!=i.readyState&&i.abort(),i=t.ajax({type:"post",url:tf_params.ajax_url,data:b,processData:!1,contentType:!1,beforeSend:function(e){t(".archive_ajax_result").block({message:null,overlayCSS:{background:"#fff",opacity:.5}}),""!==t.trim(u)&&t(".tf_booking-dates .tf_label-row").find("#tf-required").remove()},complete:function(e){if(t(".archive_ajax_result").unblock(),t(".tf-nothing-found")[0]){t(".tf_posts_navigation").hide();var a=t(".tf-nothing-found").data("post-count");t(".tf-total-results").find("span").html(a)}else{t(".tf_posts_navigation").show();var n=t(".tf-posts-count").html();t(".tf-total-results").find("span").html(n)}},success:function(e,a){t(".archive_ajax_result").unblock(),t(".archive_ajax_result").html(e),n.success(tf_params.ajax_result_success)},error:function(t){console.log(t)}})}};t(document).on("click",".tf_posts_ajax_navigation a.page-numbers",(function(e){var a;e.preventDefault(),page=((a=t(this).clone()).find("span").remove(),parseInt(a.html())),c(page)}));const c=e=>{var a=t("#tf-place").val(),o=t("#adults").val(),s=t("#room").val(),r=t("#children").val(),l=t("#check-in-out-date").val(),c=t("#startprice").val(),d=t("#endprice").val(),f=t("#tf_author").val(),u=l.split(" - "),p=u[0],h=u[1],m=t(".tf-post-type").val(),_=[];t("[name*=tf_filters]").each((function(){t(this).is(":checked")&&_.push(t(this).val())})),_=_.join();var v=[];t("[name*=tf_features]").each((function(){t(this).is(":checked")&&v.push(t(this).val())})),v=v.join();var g=[];t("[name*=tour_features]").each((function(){t(this).is(":checked")&&g.push(t(this).val())})),g=g.join();var k=[];t("[name*=tf_attractions]").each((function(){t(this).is(":checked")&&k.push(t(this).val())})),k=k.join();var b=[];t("[name*=tf_activities]").each((function(){t(this).is(":checked")&&b.push(t(this).val())})),b=b.join();var w=new FormData;w.append("action","tf_trigger_filter"),w.append("type",m),w.append("page",e),w.append("dest",a),w.append("adults",o),w.append("room",s),w.append("children",r),w.append("checkin",p),w.append("checkout",h),w.append("filters",_),w.append("features",v),w.append("tour_features",g),w.append("attractions",k),w.append("activities",b),w.append("checked",l),c&&w.append("startprice",c),d&&w.append("endprice",d),f&&w.append("tf_author",f),i&&4!=i.readyState&&i.abort(),i=t.ajax({type:"post",url:tf_params.ajax_url,data:w,processData:!1,contentType:!1,beforeSend:function(e){t(".archive_ajax_result").block({message:null,overlayCSS:{background:"#fff",opacity:.5}}),""!==t.trim(p)&&t(".tf_booking-dates .tf_label-row").find("#tf-required").remove()},complete:function(e){if(t(".archive_ajax_result").unblock(),t(".tf-nothing-found")[0]){t(".tf_posts_navigation").hide();var a=t(".tf-nothing-found").data("post-count");t(".tf-total-results").find("span").html(a)}else{t(".tf_posts_navigation").show();var n=t(".tf-posts-count").html();t(".tf-total-results").find("span").html(n)}},success:function(e,a){t(".archive_ajax_result").unblock(),t(".archive_ajax_result").html(e),n.success(tf_params.ajax_result_success)},error:function(t){console.log(t)}})};t(document).on("submit","#tf-widget-booking-search",(function(t){t.preventDefault(),l()})),t(document).on("change","[name*=tf_filters],[name*=tf_features],[name*=tour_features],[name*=tf_attractions],[name*=tf_activities]",(function(){l()})),t(document).on("submit",".tf_archive_search_result",(function(t){t.preventDefault(),l()})),t.fn.inViewport=function(a){return this.each((function(n,o){function i(){var e=t(this).height(),n=o.getBoundingClientRect(),i=n.top,s=n.bottom;return a.call(o,Math.max(0,i>0?e-i:s<e?s:e))}i(),t(e).on("resize scroll",i)}))},t(window).load((function(){jQuery("[data-width]").each((function(){var t=jQuery(this),e=t.attr("data-width");t.inViewport((function(a){a>0?t.css("width",+e+"%"):t.css("width","0%")}))}))})),t('.share-toggle[data-toggle="true"]').click((function(e){e.preventDefault();var a=t(this).attr("href");t(a).slideToggle("fast")})),t("button#share_link_button").click((function(){t(this).addClass("copied"),setTimeout((function(){t("button#share_link_button").removeClass("copied")}),3e3),t(this).parent().find("#share_link_input").select(),document.execCommand("copy")})),t(".tf-slider-items-wrapper,.tf-slider-activated").slick({dots:!0,arrows:!1,infinite:!0,speed:300,autoplaySpeed:2e3,slidesToShow:3,slidesToScroll:1,responsive:[{breakpoint:1024,settings:{slidesToShow:3,slidesToScroll:1,infinite:!0,dots:!0}},{breakpoint:600,settings:{slidesToShow:2,slidesToScroll:1}},{breakpoint:480,settings:{slidesToShow:1,slidesToScroll:1}}]}),t(".tf-review-items-wrapper").slick({dots:!0,arrows:!1,infinite:!0,speed:300,autoplay:!0,autoplaySpeed:2e3,slidesToShow:4,slidesToScroll:1,responsive:[{breakpoint:1024,settings:{slidesToShow:4,slidesToScroll:1,infinite:!0,dots:!0}},{breakpoint:600,settings:{slidesToShow:3,slidesToScroll:1}},{breakpoint:480,settings:{slidesToShow:2,slidesToScroll:1}}]});const d="wishlist_item",f=()=>{let t=localStorage.getItem(d);return null===t?[]:JSON.parse(t)},u=()=>{let e=t(".tf-wishlist-holder");t.each(e,(function(e,a){let n=t(a).data("type");n=n?n.split(","):void 0;let o=f();void 0!==n&&(o=o.filter((t=>n.includes(t.type))));let i=o.map((t=>t.post)),s={nonce:t(a).data("nonce"),action:"tf_generate_table",ids:i};t.post(tf_params.ajax_url,s,(function(e){e.success&&t(a).html(e.data)}))}))},p=t=>{t.addClass("remove-wishlist"),t.addClass("fa-heart"),t.addClass("tf-text-red"),t.removeClass("fa-heart-o"),t.removeClass("add-wishlist")},h=t=>{t.addClass("add-wishlist"),t.addClass("fa-heart-o"),t.removeClass("fa-heart"),t.removeClass("tf-text-red"),t.removeClass("remove-wishlist")};function m(t,e){var a;function n(t){if(!t)return!1;!function(t){for(var e=0;e<t.length;e++)t[e].classList.remove("autocomplete-active")}(t),a>=t.length&&(a=0),a<0&&(a=t.length-1),t[a].classList.add("autocomplete-active")}function o(e){for(var a=document.getElementsByClassName("autocomplete-items"),n=0;n<a.length;n++)e!=a[n]&&e!=t&&a[n].parentNode.removeChild(a[n])}t.addEventListener("input",(function(n){var i,s,r=this.value;if(o(),!r)return!1;a=-1,(i=document.createElement("DIV")).setAttribute("id",this.id+"autocomplete-list"),i.setAttribute("class","autocomplete-items"),this.parentNode.appendChild(i);var l=[];for(const[a,n]of Object.entries(e))n.substr(0,r.length).toUpperCase()==r.toUpperCase()?(l.push("found"),(s=document.createElement("DIV")).innerHTML="<strong>"+n.substr(0,r.length)+"</strong>",s.innerHTML+=n.substr(r.length),s.innerHTML+=`<input type='hidden' value="${n}" data-slug='${a}'> `,s.addEventListener("click",(function(e){let a=this.getElementsByTagName("input")[0];console.log(a.dataset.slug),t.value=a.value,t.closest("input").nextElementSibling.value=a.dataset.slug,o()})),i.appendChild(s)):l.push("notfound");-1==l.indexOf("found")&&((s=document.createElement("DIV")).innerHTML+=tf_params.no_found,s.innerHTML+="<input type='hidden' value=''>",s.addEventListener("click",(function(e){t.value=this.getElementsByTagName("input")[0].value,o()})),i.appendChild(s))})),t.addEventListener("keydown",(function(t){var e=document.getElementById(this.id+"autocomplete-list");e&&(e=e.getElementsByTagName("div")),40==t.keyCode?(a++,n(e)):38==t.keyCode?(a--,n(e)):13==t.keyCode&&(t.preventDefault(),a>-1&&e&&e[a].click())})),document.addEventListener("click",(function(t){o(t.target)}))}t(document).on("click",".add-wishlist",(function(){let e=t(".add-wishlist"),a={type:e.data("type"),post:e.data("id")};return e.data("page-title"),e.data("page-url"),t("body").hasClass("logged-in")?(a.action="tf_add_to_wishlists",a.nonce=e.data("nonce"),t.ajax({type:"post",url:tf_params.ajax_url,data:a,beforeSend:function(t){n.success(tf_params.wishlist_add)},success:function(t){t.success&&(p(e),n.success({message:t.data,duration:4e3}))}})):!0===(t=>{let e=f();return 0===e.filter((e=>e.post==t.post)).length&&(e.push(t),localStorage.setItem(d,JSON.stringify(e)),!0)})(a)?(n.success(tf_params.wishlist_add),p(e),n.success({message:tf_params.wishlist_added,duration:4e3})):n.error(tf_params.wishlist_add_error),!1})),t("body").find(".tf-wishlist-holder").length&&u(),t(document).on("click",".remove-wishlist",(function(){let e=t(".remove-wishlist"),a=e.data("id");if(t("body").hasClass("logged-in")){let o=e.closest("table"),i={id:a,action:"tf_remove_wishlist",type:o.data("type"),nonce:e.data("nonce")};t.get(tf_params.ajax_url,i,(function(t){t.success&&("1"!=tf_params.single&&o.closest(".tf-wishlists").html(t.data),h(e),n.success(tf_params.wishlist_removed))}))}else 1==(t=>{let e=f(),a=e.findIndex((e=>e.post==t));return console.log(a,t,e),a>=0&&(e.splice(a,1),console.log(e),localStorage.setItem(d,JSON.stringify(e)),"1"!=tf_params.single&&u(),!0)})(a)?(h(e),n.success(tf_params.wishlist_removed)):n.error(tf_params.wishlist_remove_error)})),(()=>{if(!t(document).hasClass("logged-in")&&t(document).find(".add-wishlist")){let e=t(".add-wishlist"),a=e.data("id");f().findIndex((t=>t.post==a))>=0?p(e):h(e)}})();var _=document.getElementById("tf-location"),v=tf_params.locations;_&&m(_,v);var g,k=document.getElementById("tf-destination"),b=tf_params.tour_destinations;k&&m(k,b),t(window).scroll((function(){var e=t(".tf-tour-booking-wrap");t(window).scrollTop()>=800?e.addClass("tf-tours-fixed"):e.removeClass("tf-tours-fixed")})),t(document).on("click",".tf_selectperson-wrap .tf_input-inner,.tf_person-selection-wrap .tf_person-selection-inner",(function(){t(".tf_acrselection-wrap").slideToggle("fast")})),jQuery(document).on("click",(function(t){jQuery(t.target).closest(".tf_selectperson-wrap").length||jQuery(".tf_acrselection-wrap").slideUp("fast")})),t(".acr-inc").on("click",(function(e){var a=t(this).parent().find("input");a.val(parseInt(a.val())+1).change()})),t(".acr-dec").on("click",(function(e){var a=t(this).parent().find("input"),n=a.attr("min");a.val()>n&&a.val(a.val()-1).change()})),t(document).on("change","#adults",(function(){let e=t(this),a=e.val();e.closest(".tf_selectperson-wrap").find(".adults-text").text(a+" "+tf_params.adult)})),t(document).on("change","#children",(function(){let e=t(this),a=e.val();e.closest(".tf_selectperson-wrap").find(".child-text").text(a+" "+tf_params.children)})),t(document).on("change","#infant",(function(){let e=t(this),a=e.val();e.closest(".tf_selectperson-wrap").find(".infant-text").text(a+" "+tf_params.infant)})),t(document).on("change","#room",(function(){let e=t(this),a=e.val();e.closest(".tf_selectperson-wrap").find(".room-text").text(a+" "+tf_params.room)})),t(document).on("click","#reply-title",(function(){var e=t(this);t("#commentform").slideToggle("fast","swing",(function(){e.parent().toggleClass("active")}))})),t(document).on("click","#tf-ask-question-trigger",(function(e){e.preventDefault(),t("#tf-ask-question").fadeIn().find(".response").html("")})),t(document).on("click","span.close-aq",(function(){t("#tf-ask-question").fadeOut()})),t(document).on("submit","form#ask-question",(function(e){e.preventDefault();var a=t(this),n=new FormData(this);n.append("action","tf_ask_question"),t.ajax({type:"post",url:tf_params.ajax_url,data:n,processData:!1,contentType:!1,beforeSend:function(t){a.block({message:null,overlayCSS:{background:"#fff",opacity:.5}}),a.find(".response").html(tf_params.sending_ques)},complete:function(t){a.unblock()},success:function(t){a.unblock();var e=JSON.parse(t);"sent"==e.status?(a.find(".response").html(e.msg),a.find('[type="reset"]').trigger("click")):a.find(".response").html(e.msg)},error:function(t){console.log(t)}})})),t(document).on("click",".change-view",(function(e){e.preventDefault(),t(".change-view").removeClass("active"),t(this).addClass("active"),"grid-view"==t(this).data("id")?t(".archive_ajax_result").addClass("tours-grid"):t(".archive_ajax_result").removeClass("tours-grid")})),t(document).on("click",".tf_posts_page_navigation a.page-numbers",(function(e){e.preventDefault();var a,n=e.target.href?e.target.href:t(this).context.href;a=n,g&&4!=g.readyState&&g.abort(),g=t.ajax({url:a,contentType:!1,processData:!1,asynch:!0,beforeSend:function(){t(document).find(".tf_posts_navigation").addClass("loading"),t(document).find(".archive_ajax_result").addClass("loading")},success:function(e){t(".archive_ajax_result").html(t(".archive_ajax_result",e).html()),t(".tf_posts_navigation").html(t(".tf_posts_navigation",e).html()),t(document).find(".tf_posts_navigation").removeClass("loading"),t(document).find(".archive_ajax_result").removeClass("loading")}}),window.history.pushState({url:""+n},"",n)})),t(".tf_selectdate-wrap.tf_more_info_selections .tf_input-inner").click((function(){t(".tf-more-info").toggleClass("show")}));let w={range:{min:parseInt(tf_params.tf_hotel_min_price),max:parseInt(tf_params.tf_hotel_max_price),step:1},initialSelectedValues:{from:parseInt(tf_params.tf_hotel_min_price),to:parseInt(tf_params.tf_hotel_max_price)/2},grid:!1,theme:"dark"};0!=tf_params.tf_hotel_min_price&&0!=tf_params.tf_hotel_max_price&&t(".tf-hotel-filter-range").alRangeSlider(w);let y={range:{min:parseInt(tf_params.tf_tour_min_price),max:parseInt(tf_params.tf_tour_max_price),step:1},initialSelectedValues:{from:parseInt(tf_params.tf_tour_min_price),to:parseInt(tf_params.tf_tour_max_price)/2},grid:!1,theme:"dark"};0!=tf_params.tf_tour_min_price&&0!=tf_params.tf_tour_max_price&&t(".tf-tour-filter-range").alRangeSlider(y),t("#tf-destination-adv").click((function(e){t(this).val()?t(".tf-hotel-locations").removeClass("tf-locations-show"):t(".tf-hotel-locations").addClass("tf-locations-show")})),t("#tf-destination-adv").keyup((function(e){var a=t(this).val();t("#tf-place-destination").val(a)})),t("#tf-location").keyup((function(e){var a=t(this).val();t("#tf-search-hotel").val(a)})),t(document).on("click",(function(e){t(e.target).closest("#tf-destination-adv").length||t(".tf-hotel-locations").removeClass("tf-locations-show")})),t("#ui-id-1 li").click((function(e){var a=t(this).attr("data-name"),n=t(this).attr("data-slug");t(".tf-preview-destination").val(a),t("#tf-place-destination").val(n),t(".tf-hotel-locations").removeClass("tf-locations-show")})),t("#tf-tour-location-adv").click((function(e){t(this).val()?t(".tf-tour-results").removeClass("tf-destination-show"):t(".tf-tour-results").addClass("tf-destination-show")})),t("#tf-tour-location-adv").keyup((function(e){var a=t(this).val();t("#tf-tour-place").val(a)})),t("#tf-destination").keyup((function(e){var a=t(this).val();t("#tf-search-tour").val(a)})),t(document).on("click",(function(e){t(e.target).closest("#tf-tour-location-adv").length||t(".tf-tour-results").removeClass("tf-destination-show")})),t("#ui-id-2 li").click((function(e){var a=t(this).attr("data-name"),n=t(this).attr("data-slug");t(".tf-tour-preview-place").val(a),t("#tf-tour-place").val(n),t(".tf-tour-results").removeClass("tf-destination-show")})),t(".tf-accordion-head").click((function(){t(this).toggleClass("active"),t(this).parent().find(".arrow").toggleClass("arrow-animate"),t(this).parent().find(".tf-accordion-content").slideToggle(),t(this).siblings().find(".ininerary-other-gallery").slick({slidesToShow:6,slidesToScroll:1,arrows:!0,fade:!1,adaptiveHeight:!0,infinite:!0,useTransform:!0,speed:400,cssEase:"cubic-bezier(0.77, 0, 0.18, 1)",responsive:[{breakpoint:1024,settings:{slidesToShow:4,slidesToScroll:1}},{breakpoint:640,settings:{slidesToShow:2,slidesToScroll:1}},{breakpoint:420,settings:{slidesToShow:2,slidesToScroll:1}}]})})),t(".tf-faq-title").click((function(){var e=t(this);e.hasClass("active")||(t(".tf-faq-desc").slideUp(400),t(".tf-faq-title").removeClass("active"),t(".arrow").removeClass("arrow-animate")),e.toggleClass("active"),e.next().slideToggle(),t(".arrow",this).toggleClass("arrow-animate")})),t(window).on("load",(function(){t(".tf-tablinks").length>0&&t(".tf-tablinks").first().trigger("click").addClass("active")})),t(document).on("click",".tf-tablinks",(function(e){let n=t(this).data("form-id");a(event,n)})),t(document).on("change",'select[name="tf-booking-form-tab-select"]',(function(){var e=t(this).val();a(event,e)})),t(document).on("keyup",".tf-hotel-side-booking #tf-location, .tf-hotel-side-booking #tf-destination",(function(){let e=t(this).val();t(this).next("input[name=place]").val(e)})),t(".child-age-limited")[0]&&(t(".acr-select .child-inc").on("click",(function(){var e=t('div[id^="tf-age-field-0"]'),a=t('div[id^="tf-age-field-"]:last');if(0!=a.length)var n=parseInt(a.prop("id").match(/\d+/g),10)+1;var o=a.clone().prop("id","tf-age-field-"+n);o.find("label").html("Child age "+n),o.find("select").attr("name","children_ages[]"),a.after(o),o.show(),e.hide()})),t(".acr-select .child-dec").on("click",(function(){var e=t(".tf-children-age").length,a=t('div[id^="tf-age-field-"]:last');1!=e&&a.remove()})));var x=t(".tf-posts-count").html();t(".tf-total-results").find("span").html(x),t(".tf-widget-title").on("click",(function(){t(this).find("i").toggleClass("collapsed"),t(this).siblings(".tf-filter").slideToggle("medium")})),t("a.see-more").on("click",(function(e){var a=t(this);e.preventDefault(),a.parent(".tf-filter").find(".filter-item").filter((function(t){return t>3})).removeClass("hidden"),a.hide()})),t(".tf-filter").each((function(){var e=t(this).find("ul").children().length;t(this).find(".see-more").hide(),e>4&&t(this).find(".see-more").show(),t(this).find(".filter-item").filter((function(t){return t>3})).addClass("hidden")})),t(".tf_widget input").on("click",(function(){t(this).parent().parent().toggleClass("active")})),t("form.checkout").on("click",".cart_item a.remove",(function(e){e.preventDefault();var a=t(this).attr("data-cart_item_key");t.ajax({type:"POST",url:tf_params.ajax_url,data:{action:"tf_checkout_cart_item_remove",cart_item_key:a},beforeSend:function(){t("body").trigger("update_checkout")},success:function(e){t("body").trigger("update_checkout")},error:function(t){}})})),t(document).on("submit","#tf_hotel_aval_check",(function(e){e.preventDefault();let a=t(this),o=a.find(".tf-submit"),i=new FormData(a[0]);i.append("action","tf_hotel_search"),t.ajax({url:tf_params.ajax_url,type:"POST",data:i,contentType:!1,processData:!1,beforeSend:function(){a.css({opacity:"0.5","pointer-events":"none"}),o.addClass("tf-btn-loading")},success:function(t){let e=JSON.parse(t);a.css({opacity:"1","pointer-events":"all"}),o.removeClass("tf-btn-loading"),"error"===e.status&&n.error(e.message),"success"===e.status&&(location.href=a.attr("action")+"?"+e.query_string)}})})),t(document).on("submit","#tf_tour_aval_check",(function(e){e.preventDefault();let a=t(this),o=a.find(".tf-submit"),i=new FormData(a[0]);i.append("action","tf_tour_search"),t.ajax({url:tf_params.ajax_url,type:"POST",data:i,contentType:!1,processData:!1,beforeSend:function(){a.css({opacity:"0.5","pointer-events":"none"}),o.addClass("tf-btn-loading")},success:function(t){let e=JSON.parse(t);a.css({opacity:"1","pointer-events":"all"}),o.removeClass("tf-btn-loading"),"error"===e.status&&n.error(e.message),"success"===e.status&&(location.href=a.attr("action")+"?"+e.query_string)}})}))}))})();
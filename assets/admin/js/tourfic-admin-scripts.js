(()=>{var t;(t=jQuery)(document).ready((function(){const e=new Notyf({ripple:!0,dismissable:!0,duration:3e3,position:{x:"right",y:"bottom"}});t(document).on("click",".tf-del-old-review-fields",(function(a){a.preventDefault(),t(this);var s={action:"tf_delete_old_review_fields",deleteAll:t(this).data("delete-all")};t.ajax({type:"post",url:tf_admin_params.ajax_url,data:s,beforeSend:function(t){e.success(tf_admin_params.deleting_old_review_fields)},success:function(t){e.success(t.data)},error:function(t){e.error(t.data)}})})),t(document).on("click",".remove-order-ids",(function(a){a.preventDefault();var s=t(this),n=t("#post_ID").val(),o={action:"tf_remove_room_order_ids",meta_field:s.closest(".tf-repeater-content-wrap").find(".tf-order_id input").attr("name"),post_id:n};t.ajax({type:"post",url:ajaxurl,data:o,beforeSend:function(t){e.success(tf_admin_params.deleting_room_order_ids)},success:function(t){e.success(t.data),location.reload()},error:function(t){e.error(t.data)}})})),t(document).on("click",".post-type-tf_tours #publish, .post-type-tf_tours #save-post",(function(a){if(0===t('textarea[name="tf_tours_opt[text_location]"]').val().length)return a.preventDefault,a.stopImmediatePropagation(),e.error(tf_admin_params.tour_location_required),!1})),t(document).on("click",".post-type-tf_hotel #publish, .post-type-tf_hotel #save-post",(function(a){if(0===t('textarea[name="tf_hotels_opt[address]"]').val().length)return a.preventDefault,a.stopImmediatePropagation(),e.error(tf_admin_params.hotel_location_required),!1})),t(document).on("click",".post-type-tf_tours #publish, .post-type-tf_tours #save-post",(function(a){if(0==t("#set-post-thumbnail").find("img").size())return a.preventDefault,a.stopImmediatePropagation(),e.error(tf_admin_params.tour_feature_image_required),!1})),t(document).on("click",".post-type-tf_hotel #publish, .post-type-tf_hotel #save-post",(function(a){if(0==t("#set-post-thumbnail").find("img").size())return a.preventDefault,a.stopImmediatePropagation(),e.error(tf_admin_params.hotel_feature_image_required),!1})),t(document).on("click",".tf-install",(function(e){e.preventDefault();var a=t(this),s=a.attr("data-plugin-slug");a.addClass("updating-message").text(tf_admin_params.installing);var n={action:"tf_ajax_install_plugin",_ajax_nonce:tf_admin_params.tf_nonce,slug:s};jQuery.post(tf_admin_params.ajax_url,n,(function(t){a.removeClass("updating-message"),a.addClass("updated-message").text(tf_admin_params.installed),a.attr("href",t.data.activateUrl)})).fail((function(){a.removeClass("updating-message").text(tf_admin_params.install_failed)})).always((function(){a.removeClass("install-now updated-message").addClass("activate-now button-primary").text(tf_admin_params.activating),a.unbind(e),a[0].click()}))})),t(document).on("click",".tf-pro",(function(t){t.preventDefault(),window.open("https://tourfic.com/")})),t(window).on("load",(function(){t(".tf-field-disable").find("input, select, textarea, button, div, span").attr("disabled","disabled")})),t(document).on("click",".tf-field-pro",(function(t){t.preventDefault(),window.open("https://tourfic.com/")})),t(document).on("click",".room-repeater > div.csf-fieldset > a.csf-repeater-add",(function(e){var a=t('.room-repeater .csf-repeater-wrapper [data-depend-id="room"]').length-2;t(".room-repeater .unique-id input").each((function(){a++,0===t('.room-repeater [data-depend-id="room"] [data-depend-id="unique_id"]').val().length&&t('.room-repeater [name="tf_hotel[room]['+a+'][unique_id]"]').val((new Date).valueOf()+a)}))}))})),(()=>{const{select:t,dispatch:e}=wp.data;function a(){let a=!1;tf_params.error=!1,tf_params.messages=[];let s=Object.assign({},t("core/editor").getCurrentPost(),t("core/editor").getPostEdits());s.hasOwnProperty("categories")&&(s.categories=s.categories.filter((function(t){return 1!==t}))),jQuery.each(tf_params.taxonomies,(function(t,n){s.hasOwnProperty(t)&&0===s[t].length?(e("core/notices").createNotice("error",n.message,{id:"tfNotice_"+t,isDismissible:!1}),tf_params.error=a=!0):e("core/notices").removeNotice("tfNotice_"+t)})),!0===a?e("core/editor").lockPostSaving():e("core/editor").unlockPostSaving()}a(),setInterval(a,500)})(),jQuery((function(t){const e=new Notyf({ripple:!0,dismissable:!0,duration:3e3,position:{x:"right",y:"bottom"}});function a(a){return tf_params.error=!1,t.each(tf_params.taxonomies,(function(a,s){"hierarchical"==s.type?0==t("#taxonomy-"+a+" input:checked").length&&(e.error(s.message),tf_params.error=!0):t("#tagsdiv-"+a+" .tagchecklist").is(":empty")&&(e.error(s.message),tf_params.error=!0)})),!tf_params.error||(a.stopImmediatePropagation(),!1)}if(t("#publish, #save-post").on("click.require-post-category",a),t("#post").on("submit.require-post-category",a),null!=t("#publish")[0]&&null!=t._data(t("#publish")[0],"events")){var s=t._data(t("#publish")[0],"events").click;s&&s.length>1&&s.unshift(s.pop())}if(null!=t("#save-post")[0]&&null!=t._data(t("#save-post")[0],"events")){var n=t._data(t("#save-post")[0],"events").click;n&&n.length>1&&n.unshift(n.pop())}if(null!=t("#post")[0]&&null!=t._data(t("#post")[0],"events")){var o=t._data(t("#post")[0],"events").submit;o&&o.length>1&&o.unshift(o.pop())}})),function(t){t(document).ready((function(){t(document).on("click",".tf-setup-start-btn",(function(e){e.preventDefault(),t(".tf-welcome-step").hide(),t(".tf-setup-step-1").show()})),t(document).on("click",".tf-setup-next-btn, .tf-setup-skip-btn",(function(e){e.preventDefault();let a=t("#tf-setup-wizard-form"),s=a.find('input[name="tf-skip-steps"]').val(),n=t(this).closest(".tf-setup-step-container").data("step"),o=n+1;if(1===n&&t(this).hasClass("tf-setup-next-btn")){if(!t('input[name="tf-services[]"]:checked').length)return alert(tf_setup_wizard.i18n.no_services_selected),!1;t('input[name="tf-services[]"][value="hotel"]').is(":checked")?t(".tf-hotel-setup-wizard").show():t(".tf-hotel-setup-wizard").hide(),t('input[name="tf-services[]"][value="tour"]').is(":checked")?t(".tf-tour-setup-wizard").show():t(".tf-tour-setup-wizard").hide()}t(this).hasClass("tf-setup-skip-btn")&&(s=s?-1===s.indexOf(n)?s+","+n:s:n,a.find('input[name="tf-skip-steps"]').val(s),1===n&&(t(".tf-hotel-setup-wizard").show(),t(".tf-tour-setup-wizard").show())),t(this).hasClass("tf-setup-next-btn")&&-1!==s.indexOf(n)&&(s=s.replace(n,""),a.find('input[name="tf-skip-steps"]').val(s)),t(this).hasClass("tf-setup-submit-btn")||t(".tf-setup-step-"+n).fadeOut(300,(function(){t(".tf-setup-step-"+o).fadeIn(300)}))})),t(document).on("click",".tf-setup-prev-btn",(function(e){e.preventDefault();let a=t(this).closest(".tf-setup-step-container").data("step"),s=a-1;t(".tf-setup-step-"+a).fadeOut(300,(function(){t(".tf-setup-step-"+s).fadeIn(300)}))})),t(document).on("click",".tf-setup-submit-btn",(function(e){e.preventDefault();let a=t(".tf-setup-submit-btn.tf-admin-btn"),s=t(this).closest("#tf-setup-wizard-form"),n=t(this).closest(".tf-setup-step-container").data("step"),o=s.find('input[name="tf-skip-steps"]').val();t(this).hasClass("tf-admin-btn")&&-1!==o.indexOf(n)&&(o=o.replace(n,""),s.find('input[name="tf-skip-steps"]').val(o));let i=new FormData(s[0]);i.append("action","tf_setup_wizard_submit"),t.ajax({url:tf_setup_wizard.ajaxurl,type:"POST",data:i,processData:!1,contentType:!1,beforeSend:function(){a.addClass("tf-btn-loading")},success:function(t){let e=JSON.parse(t);a.removeClass("tf-btn-loading"),e.success&&(window.location.href=e.redirect_url)},error:function(t){a.removeClass("tf-btn-loading"),console.log(t)}})}))}))}(jQuery)})();
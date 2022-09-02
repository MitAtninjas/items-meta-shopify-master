(function () {
    if (window.jQuery) {
        jQuery = window.jQuery;
    } else if (window.Checkout && window.Checkout.$) {
        jQuery = window.Checkout.$;
    }
	
	const baseUrl = 'https://shopify-test.konex.systems';
	var packstatuionRegUrl = baseUrl + '/webhook/updatePackstationNo';
	var updateDeliveryDateUrl = baseUrl + '/webhook/updateDeliveryDate';
    var method_handle = decodeURIComponent(
        Shopify.checkout.shipping_rate.handle
    );

    var standardShippingMethod = [
        "bpost_standard",
        "bpost_hvo",
        "dhl_de_standard",
        "postnl_nl_standard",
        "dhl_nl_standard"
    ];
    var method_title = method_handle.split("#")[1].split("-")[0];
	var default_ttl = 6*60*60*1000;
    var order_id = Shopify.checkout.order_id;
    var store = Shopify.shop;
    var shipping_title = Shopify.checkout.shipping_rate.title;
	
		
	
	
	function aa_shop_setWithExpiry(key, value, ttl) {
		const now = new Date()

		// `item` is an object which contains the original value
		// as well as the time when it's supposed to expire
		const item = {
			value: value,
			expiry: now.getTime() + ttl,
		}
		localStorage.setItem(key, JSON.stringify(item))
	}
	
	function aa_shop_getWithExpiry(key) {
		const itemStr = localStorage.getItem(key)

		// if the item doesn't exist, return null
		if (!itemStr) {
			return null
		}

		const item = JSON.parse(itemStr)
		const now = new Date()

		// compare the expiry time of the item with the current time
		if (now.getTime() > item.expiry) {
			// If the item is expired, delete the item from storage
			// and return null
			localStorage.removeItem(key)
			return null
		}
		return item.value
	}
	
	function aa_shipping_title_load() {
		
		jQuery('.aa-shipping-title').parent().css('background-color','#004c3f');
		jQuery('.aa-shipping-title').css('color','white');
		jQuery('.aa-shipping-title').parents('div.content-box').insertBefore(jQuery('.section__content').children().first());
		
	}
	
	
	function validate_form_submission(e) {
	  
	//if(aa_shop_getWithExpiry(order_id+'_aa_dhlpackstation_'+store))
		//	return;
	  
	  var confirmationMessage = "\o/";
	  e.preventDefault();
	  event.stopPropagation();
	  
	  if(!aa_shop_getWithExpiry(order_id+'_aa_dhlpackstation_'+store)){
		  jQuery('.aa-shipping-title').parent().css('background-color','red');
		  jQuery('.aa-shipping-title').css('color','white');
		  jQuery("#error_msg").show();
		  jQuery('[name="registration_no"]').focus();
	  }
	  
	  
	  (e || window.event).returnValue = confirmationMessage; //Gecko + IE
	  return confirmationMessage;                            //Webkit, Safari, Chrome
		
	}
	
    //standard method
    if (standardShippingMethod.includes(method_title)) {
        //default setting
        var sectionTitle = "Select Preferred Delivery Date";
        var buttonText = "Submit";
        var successMessage = "Success! Delivery date updated";
        var errorMessage = "Error! Please,Try again later";

        const loadContentBox = () => {
            Shopify.Checkout.OrderStatus.addContentBox(
                `<h2 class="heading-2 os-step__title aa-shipping-title">${sectionTitle}</h2>`,
                `<form id="order_status" action=${updateDeliveryDateUrl} style="padding-top: 0" class="content-box__row animate-floating-labels" method="post">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />
                    <input type="hidden" name="order_id" value=${order_id}>
                    <input type="hidden" name="store_url" value=${store}>
                    <div class="os-step__description">
                        <div class="enquire__response" style="align-items: center; padding: 8px 0;">
                            <input type="text" name="delivery_date" style="border: 1px solid grey; padding: 5px; border-radius: 3px;" id="delivery_datepicker" required>
                            <button class="btn btn--size-small" type="submit" id="submit_date" style="min-width: 100px;max-width: 130px;
                                float: right;
                                margin-top: -9px; padding: 10px;">
                                    <span class="btn__content enquire__action-content">${buttonText}</span>
                                </button>
                        </div>
                    </div>
                    <div class="os-step__description">
                        <p style="color: green; display:none; font-size: 14px; font-weight: bold; padding: 10px" id="success_msg">${successMessage}</p>
                        <p style="color: red; display:none; font-size: 14px; font-weight: bold; padding: 10px" id="error_msg">${errorMessage}</p>
                    </div>
                </form>`
            );
        };

        const initialiseDatePicker = () => {
            if (jQuery("#delivery_datepicker").length) {
                jQuery("#delivery_datepicker").datepicker({
                    dateFormat: "dd/mm/yy",
                    minDate: 0,
                    maxDate: "+8D",
                    beforeShowDay: $.datepicker.noWeekends,
                });
				
				
				if(aa_shop_getWithExpiry(order_id+'_aa_deliverydate_'+store)){
					
					jQuery("#delivery_datepicker").val(aa_shop_getWithExpiry(order_id+'_aa_deliverydate_'+store));
					//jQuery("#submit_date").text(buttonText);
					jQuery('#submit_date').removeClass('btn--loading');
					jQuery("#submit_date").text('Update');
					if(jQuery('#submit_date').attr('disabled') == 'disabled'){
						//jQuery("#submit_date").css("background-color","#cfcfcf");
						jQuery('#submit_date').removeAttr('disabled');
					}
						
					jQuery("#success_msg").show();
					
				}
				
            }
        };

        const attachFormHandler = () => {
            jQuery("#order_status").submit(function (e) {
                e.preventDefault();
				jQuery("#success_msg").hide();
				jQuery("#error_msg").hide();
                
				jQuery("#submit_date").html(
                    '<svg width="24" height="24" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#fff"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".5" cx="18" cy="18" r="18"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"/></path></g></g></svg>'
                );
				
				
				
                var form = jQuery(this);
                var url = form.attr("action");
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function (data) {
                        console.log(data);
                        //jQuery("#submit_date").text(buttonText);
						jQuery("#submit_date").text('Update');
						jQuery('#submit_date').removeClass('btn--loading');
                        if(jQuery('#submit_date').attr('disabled') == 'disabled'){
							//jQuery("#submit_date").css("background-color","#cfcfcf");
							jQuery('#submit_date').removeAttr('disabled');
						}
							
                        jQuery("#success_msg").show();
						jQuery("#error_msg").hide();
						//Store set
						aa_shop_setWithExpiry(order_id+'_aa_deliverydate_'+store,jQuery("#delivery_datepicker").val(),default_ttl);
						
                    },
                    error: function () {
                        jQuery("#submit_date").text(buttonText);
						jQuery('#submit_date').removeClass('btn--loading');
                        if(jQuery('#submit_date').attr('disabled') == 'disabled'){
							//jQuery("#submit_date").css("background-color","#cfcfcf");
							jQuery('#submit_date').removeAttr('disabled');
						}
							
                        jQuery("#error_msg").show();
                    },
                });
            });
        };

        const updateSetting = (settingObj) => {

            if(typeof window.aa_preferred_date_loaded == "undefined"  ) {
                sectionTitle = settingObj.section_title;
                buttonText = settingObj.button_text;
                successMessage = settingObj.success_message;
                errorMessage = settingObj.error_message;

                if (settingObj.date_enabled) {
                    loadContentBox();
					
					
					
					
                    initialiseDatePicker();
					//box Styling
					aa_shipping_title_load();
					jQuery('#delivery_datepicker').focus();
					
                    attachFormHandler();
                }

                window.aa_preferred_date_loaded = true;
            }

        };

        //fetch date settings
        var settingUrl = baseUrl + '/webhook/getDateSetting';
            
        jQuery.ajax({
            type: "POST",
            url: settingUrl,
            dataType: "json",
            data: { method_name: method_title },
            success: function (res) {
                updateSetting(res);
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    //packstations
    if (method_title === "dhl_de_service_packstations") {
        //default setting
        var sectionTitle = "Enter Packstation Registration No";
        var buttonText = "Submit";
        var successMessage = "Success! Packstation Registartion No updated";
        var errorMessage = "Error! Please,Try again later";

        const loadPackStationBox = () => {
            Shopify.Checkout.OrderStatus.addContentBox(
                `<h2 class="heading-2 os-step__title aa-shipping-title">${sectionTitle}</h2>`,
                `<form id="packstation_no" action=${packstatuionRegUrl} style="padding-top: 0" class="content-box__row animate-floating-labels" method="post">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />
                    <input type="hidden" name="order_id" value=${order_id}>
                    <input type="hidden" name="store_url" value=${store}>
                    <div class="os-step__description">
                        <div class="enquire__response" style="align-items: center; padding: 8px 0;">
                            <input type="text" name="registration_no" style="border: 1px solid grey; padding: 5px; border-radius: 3px; width: 300px; " required>
                            <button class="btn btn--size-small" type="submit" id="submit_no" style="min-width: 100px;max-width: 130px;
                                float: right;
                                margin-top: -4px; padding: 10px;">
                                    <span class="btn__content enquire__action-content">${buttonText}</span>
                                </button>
                        </div>
                    </div>
                    <div class="os-step__description">
                        <p style="color: green; display:none; font-size: 14px; font-weight: bold; padding: 10px" id="success_msg">${successMessage}</p>
                        <p style="color: red; display:none; font-size: 14px; font-weight: bold; padding: 10px" id="error_msg">${errorMessage}</p>
                    </div>
                </form>`
            );
        };

        const attachPackStationFormHandler = () => {
            jQuery("#packstation_no").submit(function (e) {
                e.preventDefault();
                jQuery("#success_msg").hide();
				jQuery("#error_msg").hide();
				jQuery("#submit_no").html(
                    '<svg width="24" height="24" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#fff"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".5" cx="18" cy="18" r="18"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"/></path></g></g></svg>'
                );
                var form = jQuery(this);
                var url = form.attr("action");
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function (data) {
                        console.log(data);
						jQuery('#submit_no').removeClass('btn--loading');
                        //jQuery("#submit_no").text(buttonText);
						jQuery("#submit_no").text('Update');
                        if(jQuery('#submit_no').attr('disabled') == 'disabled'){
							//jQuery("#submit_no").css("background-color", "#cfcfcf");
							
							jQuery('#submit_no').removeAttr('disabled');
							
							
						}
							
                        jQuery("#success_msg").show();
						jQuery("#error_msg").hide();
						
						//jQuery('.aa-shipping-title').parent().css('background-color','white');
						//jQuery('.aa-shipping-title').css('color','black');
						jQuery('.aa-shipping-title').parent().css('background-color','#004c3f');
						jQuery('.aa-shipping-title').css('color','white');
						aa_shop_setWithExpiry(order_id+'_aa_dhlpackstation_'+store,jQuery('[name="registration_no"]').val(),default_ttl);
						//jQuery(window).off("beforeunload", validate_form_submission,  {capture: false});	
						
                    },
                    error: function () {
                        jQuery("#submit_no").text(buttonText);
						jQuery('#submit_no').removeClass('btn--loading');
                        if(jQuery('#submit_no').attr('disabled') == 'disabled'){
													
							jQuery('#submit_no').removeAttr('disabled');
							
						}
                        jQuery("#error_msg").show();
                    },
                });
            });
        };

        const updatePackStationSetting = (packSetting) => {
            if(typeof window.aa_dhl_packstation_loaded == "undefined"  ) {
                sectionTitle = packSetting.section_title;
                buttonText = packSetting.button_text;
                successMessage = packSetting.success_message;
                errorMessage = packSetting.error_message;

                loadPackStationBox();
				//jQuery('.aa-shipping-title').parent().css('background-color','#004c3f');
				//jQuery('.aa-shipping-title').css('color','white');
				
                attachPackStationFormHandler();
				aa_shipping_title_load();
				jQuery('[name="registration_no"]').focus();
				
				window.addEventListener("beforeunload", validate_form_submission);	
				//jQuery(window).on("beforeunload", validate_form_submission,  {capture: true});	
				
				if(aa_shop_getWithExpiry(order_id+'_aa_dhlpackstation_'+store)){
					
					jQuery('[name="registration_no"]').val(aa_shop_getWithExpiry(order_id+'_aa_dhlpackstation_'+store));
					//jQuery("#submit_no").text(buttonText);
					jQuery("#submit_no").text('Update');
					if(jQuery('#submit_no').attr('disabled') == 'disabled'){
						//jQuery("#submit_no").css("background-color", "#cfcfcf");
						jQuery('#submit_no').removeAttr('disabled');
						
					}
					jQuery("#success_msg").show();
					window.removeEventListener("beforeunload", validate_form_submission);	
					
				}  
					
				
				
                window.aa_dhl_packstation_loaded = true;
				
				
				
				
            }

        };

        //fetch date settings
        var packStationSettingUrl = baseUrl + '/webhook/getPackstationSetting';
            

        jQuery.ajax({
            type: "GET",
            url: packStationSettingUrl,
            success: function (res) {
                updatePackStationSetting(res);
            },
            error: function (error) {
                console.log(error);
                if(typeof window.aa_dhl_packstation_loaded == "undefined"  ) {
                    loadPackStationBox();
                    attachPackStationFormHandler();

                    window.aa_dhl_packstation_loaded = true;
                }

            },
        });
    }
})();

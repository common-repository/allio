// Based on blog post: https://www.sitepoint.com/how-to-build-your-own-ai-assistant-using-api-ai/
// Source code: https://github.com/sitepoint-editors/Api-AI-Personal-Assistant-Demo/blob/master/index.html.
// Demo: https://devdiner.com/demos/barry/

// When ready :)

function detectmob() { 
	if( navigator.userAgent.match(/Android/i)
	|| navigator.userAgent.match(/webOS/i)
	|| navigator.userAgent.match(/iPhone/i)
	|| navigator.userAgent.match(/iPad/i)
	|| navigator.userAgent.match(/iPod/i)
	|| navigator.userAgent.match(/BlackBerry/i)
	|| navigator.userAgent.match(/Windows Phone/i)
	){
		 return true;
	 }
	else {
		 return false;
	 }
 }

 var greco = null;
 function speech() {
	 try {
		if(window.webkitSpeechRecognition || window.SpeechRecognition){
			greco = new webkitSpeechRecognition() || new SpeechRecognition();
			greco.interimResults = true;
			greco.lang = 'en';
		}
	 } catch (e) {
		greco = null;
	 }
		 
 }

 function disabledInput(obj) {
		/*jQuery('#allioc-input-area').hide();*/
		/*jQuery('.allioc-content-overlay-container').addClass('hide-input-text');*/
		jQuery('.allioc-conversation-request.allioc-is-active').parent().addClass('hide-input-text');

		jQuery(obj).closest('.allioc-conversation-bubble-container-response-wrapper').find('a').off('click');		
 }

 var gquery = '';
 var gWelcome = 0;
 var gtoken = '';
 var gcharge = '';
 var gsk = '';

 var gchatbotEnd = false;
jQuery(document).ready(function() {

/*	jQuery('body').off('keypress').on('keypress', function(e) {
		if (jQuery('.allioc-content-overlay').hasClass('allioc-toggle-open')) {
			if (jQuery('#allioc-input-area').css('display') == 'none') {
				jQuery('.allioc-content-overlay-container').removeClass('hide-input-text');
				jQuery('#allioc-input-area').css('display', 'flex');
				jQuery('#allioc-input-area .allioc-text').focus();
			}
		}		
	});
*/
	jQuery(".allioc-content-overlay-container").off('click').on('click', function(e) {
		if (gchatbotEnd) {
			gchatbotEnd = false;
			chatreload();
		} 
	});

	speech();
	if (greco != null) {
		jQuery('.allioc-micro-button').css('display', 'block');
	}

	if (jQuery('.allioc-micro-button').length > 0) {
		jQuery('.allioc-micro-button').off('click').on('click', function(e) {
		
			if (jQuery(this).hasClass('micro-active')) {
				jQuery(this).removeClass('micro-active');
				if (greco)
					greco.abort();
			} else {
				jQuery('input.allioc-text').trigger('click');
				jQuery(this).addClass('micro-active');
				if (greco == null) {
					speech();
				}
	
				greco.start()
				greco.onresult = function(event) {
					for (var i = event.resultIndex; i < event.results.length; ++i){
						gquery = event.results[i][0].transcript // <- push results to the Text input
					}
				};
				
				greco.onend = function() {
					greco.stop();
					
					if (gquery != '') {
						jQuery("input.allioc-text").val(gquery);
						setTimeout(function(e) {
							jQuery("#allioc-input-area .allioc-send-button").trigger('click');
						}, 500);
					
						gquery = '';
						greco.abort();
						jQuery('.allioc-micro-button').removeClass('micro-active');
					
					}
				
				}
			}
		});
	}

	jQuery("input.allioc-text").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			jQuery(".allioc-conversation-area .allioc-conversation-request").removeClass("allioc-is-active");

			var text = jQuery(this).val();
			var date = new Date();
			if (text.trim() == "") {
				return false;
			}

			jQuery(this).val('');
			var containerId = jQuery(".allioc-content-overlay-container .allioc-container").attr('id');
			var parts = containerId.split("-");
			var sequence = parts[2];

			var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-request\"><div class=\"allioc-conversation-bubble allioc-conversation-request allioc-is-active\">" + escapeTextInput(text) + "</div>";
			if (allioc_script_vars.show_time) {
				innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
			}
			innerHTML += "</div>";
			if (allioc_script_vars.show_loading) {
				innerHTML += "<div class=\"allioc-loading\"><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /></div>";
			}
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);
			jQuery("#allioc-container-" + sequence + " input.allioc-text").val("");
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
					.scrollTop(jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
					.prop("scrollHeight"));
			
			textQuery(text, sequence);
					
		}
	});

	jQuery('input.allioc-text').off('click').on('click', function(e) {
		var d1 = jQuery(this).data('curdate');
		if (typeof d1 == "undefined") {
			return;
		}
		var d2 = new Date().getTime();
		var dif = (d2 - d1) / (60 * 1000);
		if (dif >= 9) {
			chatreload();
			jQuery(this).data('curdate', d2);
		}
	});

	jQuery("#allioc-input-area .allioc-send-button").off('click').on('click', function(e) {
		if (jQuery("input.allioc-text").val() != '') {
			jQuery(".allioc-conversation-area .allioc-conversation-request").removeClass("allioc-is-active");

				var text = jQuery("input.allioc-text").val();
				var date = new Date();
				if (text.trim() == "") {
					return false;
				}
				jQuery("input.allioc-text").val('');
				var containerId = jQuery(".allioc-content-overlay-container .allioc-container").attr('id');
				var parts = containerId.split("-");
				var sequence = parts[2];

				var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-request\"><div class=\"allioc-conversation-bubble allioc-conversation-request allioc-is-active\">" + escapeTextInput(text) + "</div>";
				if (allioc_script_vars.show_time) {
					innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
				}
				innerHTML += "</div>";
				if (allioc_script_vars.show_loading) {
					innerHTML += "<div class=\"allioc-loading\"><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /></div>";
				}
				jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);
				jQuery("#allioc-container-" + sequence + " input.allioc-text").val("");
				jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
						.scrollTop(jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
						.prop("scrollHeight"));
				
					textQuery(text, sequence);
					
				
		}
	});


	/* Overlay slide toggle */
	jQuery(".allioc-content-overlay .allioc-content-overlay-header").click(function(event){

		if (!jQuery(".allioc-content-logo").hasClass("opend")) { // toggle open

			var container = jQuery(".allioc-content-overlay-container .allioc-container");

			// if welcome intent enabled and no conversation exists yet
			if (jQuery(container).find(".allioc-conversation-bubble-container").length == 0) {

				var containerId = jQuery(container).attr('id');
				var parts = containerId.split("-");
				var sequence = parts[2];

				welcomeIntent(sequence);
			}
			jQuery('.allioc-content-logo').addClass('opend');
			
			
			jQuery(this).parent().addClass("allioc-toggle-open");
			jQuery(this).parent().removeClass("allioc-toggle-closed");
			
			jQuery(this).siblings(".allioc-content-overlay-container").slideToggle("slow", function() {
				
			});
		} else { // toggle close
			jQuery('.allioc-content-logo').removeClass('opend');			
			jQuery(this).parent().removeClass("allioc-toggle-open");
			jQuery(this).parent().addClass("allioc-toggle-closed");
			var that = this;	
			jQuery(this).siblings(".allioc-content-overlay-container").slideToggle("slow", function() {
			
			});
		}
	});

	jQuery('.allioc-content-logo').off('click').on('click', function(e) {
		jQuery(".allioc-content-overlay .allioc-content-overlay-header").trigger('click');
	//	jQuery(this).toggleClass('opend');
	});

		/*
	 * Welcome
	 */

	if (allioc_script_vars.enable_welcome_event) {
		gWelcome = 1;
		// show welcome intent on first chatbot only
		if ( jQuery(".allioc-content-overlay-container .allioc-container").length > 0 ) {
			var self = jQuery(".allioc-content-overlay .allioc-content-overlay-header");

			var container = jQuery(".allioc-content-overlay-container .allioc-container");

			// if welcome intent enabled and no conversation exists yet
			if (jQuery(container).find(".allioc-conversation-bubble-container").length == 0) {

				var containerId = jQuery(container).attr('id');
				var parts = containerId.split("-");
				var sequence = parts[2];

				welcomeIntent(sequence);
			}
			jQuery('.allioc-content-logo').addClass('opend');
			
			jQuery(self).parent().removeClass("allioc-toggle-closed");
			jQuery(self).parent().addClass("allioc-toggle-open");
		
		}
	}

	function resizeChatbot() {

		jQuery('.allioc-conversation-area').css('height', '');
		jQuery('.allioc-content-overlay').removeClass('responsive-chat');

		var h = jQuery('.allioc-content-overlay').height() + 90;				
		if (h > jQuery(window).height()) {
			var dif = h - jQuery(window).height();					
			var areah = jQuery('.allioc-conversation-area').outerHeight(true) - dif;
			if (areah < 150) {
				jQuery('.allioc-content-overlay').addClass('responsive-chat');
				return;
			}
			jQuery('.allioc-conversation-area').height(areah);
		} else {
			jQuery('.allioc-conversation-area').css('height', '');
		}
	}
	if (detectmob()) {
		var h = jQuery('.allioc-content-overlay').height() - jQuery('.allioc-content-overlay-header').outerHeight(true);
		h = h - jQuery('#allioc-input-area').outerHeight(true)- 30;
		jQuery('.allioc-content-overlay-container .allioc-conversation-area').css('height', h);
	} else {
		jQuery(window).resize(function(e) {
			resizeChatbot();
		});
		resizeChatbot();
	}

	//jQuery('input.allioc-text').focus();
});


/**
 * Displays welcome intent for a specific chatbot identified by sequence
 *
 * @params sequence
 */
function welcomeIntent(sequence) {
	//worker.postMessage({'cmd': 'reload', 'msg': ''});
    if (allioc_script_vars.welcome_msg) {
        setTimeout(function(){
            if (allioc_script_vars.show_loading) {
                jQuery("#allioc-container-" + sequence + " .allioc-loading").empty();
            }
            //var res = JSON.parse(allioc_script_vars.welcome_msg);
            prepareResponse({fulfillmentMessages: [{text: {text:[allioc_script_vars.welcome_msg]}}]}, sequence);
        }, 1000);
				gWelcome = 0; 
				
				jQuery.ajax({
					type : "POST",
					url : allioc_script_vars.base_url,
					dataType : "json",
					data : {
						security: allioc_script_vars.security,
						action: "query_text",
						event :"WELCOME",			
						lang : allioc_script_vars.language,
						session : allioc_script_vars.session_id,
					},
					success : function(response) {
					},
					error : function(response) {						
					}
				});
        return;
    }
	var innerHTML = "";
	if (allioc_script_vars.show_loading) {
		innerHTML = "<div class=\"allioc-loading\"><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /></div>";
	}
	
	jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);

	jQuery.ajax({
		type : "POST",
		url : allioc_script_vars.base_url,
		dataType : "json",
		data : {
			security: allioc_script_vars.security,
			action: "query_text",
			event :"WELCOME",			
			lang : allioc_script_vars.language,
			session : allioc_script_vars.session_id,
		},
		success : function(response) {

			setTimeout(function(){
				if (allioc_script_vars.show_loading) {
					jQuery("#allioc-container-" + sequence + " .allioc-loading").empty();
				}
				prepareResponse(response,sequence);
			}, allioc_script_vars.response_delay);
		},
		error : function(response) {
			if (gWelcome == 1) {
				setTimeout(function() {
					chatreload();
				}, 1000);
				gWelcome = 0;
				return;
			}

			if (allioc_script_vars.show_loading) {
				jQuery("#allioc-container-" + sequence + " .allioc-loading").empty();
			}
			
			textResponse(allioc_script_vars.messages.internal_error, sequence);
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
					.scrollTop(jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
					.prop("scrollHeight"));
		}
	});

}

/**
 * Send Dialogflow query
 *
 * @param text
 * @param sequence
 * @returns
 */
function textQuery(text, sequence) {
	//worker.postMessage({'cmd': 'reload', 'msg': ''});
	jQuery.ajax({
		type : "POST",
		url : allioc_script_vars.base_url,
		dataType : "json",	
		data: {
			security: allioc_script_vars.security,
			action: "query_text",
			message: text,
			lang : allioc_script_vars.language,
			session: allioc_script_vars.session_id,
			token: gtoken,
			charge: gcharge,
			sk: gsk
		},
		success : function(response) {
			gtoken = '';
			gcharge = '';gsk = '';
			setTimeout(function(){
				if (allioc_script_vars.show_loading) {
					jQuery("#allioc-container-" + sequence + " .allioc-loading").empty();
				}
				prepareResponse(response,sequence);
			}, allioc_script_vars.response_delay);

		},
		error : function(response) {
			gtoken = '';
			gcharge = '';gsk = '';

			if (allioc_script_vars.show_loading) {
				jQuery("#allioc-container-" + sequence + " .allioc-loading").empty();
			}
			textResponse(allioc_script_vars.messages.internal_error, sequence);
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
					.scrollTop(jQuery(".allioc-container-" + sequence + " .allioc-conversation-area")
					.prop("scrollHeight"));
		}
	});
}

/**
 * Handle Dialogflow response
 *
 * @param response
 * @param response
 */
function prepareResponse(response, sequence) {

	//if (response.status.code == "200" ) {
		jQuery('.allioc-text').data('curdate', new Date().getTime());
		jQuery(window).trigger("allioc_response_success", response);

		jQuery("#allioc-container-" + sequence + " .allioc-conversation-area .allioc-conversation-response").removeClass("allioc-is-active");

		var messages = response.fulfillmentMessages;
		var numMessages = messages.length;
		var index = 0;
		var parameters = response.parameters ? response.parameters: false;

		if (parameters && parameters.charge) {
			gcharge = parameters.charge;
		}

		if (parameters && parameters.secretKey) {
			gsk = parameters.secretKey;
		}

		for (index; index<numMessages; index++) {
			var message = messages[index];

			if (allioc_script_vars.messaging_platform == message.platform
					|| allioc_script_vars.messaging_platform == "default" && message.platform === undefined
					|| message.platform === undefined && ! hasPlatform(messages, allioc_script_vars.messaging_platform) ) {
				
				if (!message.type) {
					textResponse(message.text, sequence);
				} else {
					switch (message.type) {
				    case 0: // text response
						textResponse(message.speech, sequence);
				        break;
				    case 1: // TODO card response
				        cardResponse(message.title, message.subtitle, message.buttons, message.text, message.postback, sequence);
				        break;
				    case 2: // quick replies
				    	quickRepliesResponse(message.title, message.replies, sequence);
				        break;
				    case 3: // image response
						imageResponse(message.imageUrl, sequence);
				        break;
				    case 3: // custom payload
							
				        break;
				    default:
					}
				}
				
			}
		}

		if (response.diagnosticInfo && response.diagnosticInfo.end_conversation) {
			jQuery('#allioc-input-area').hide();
			gchatbotEnd = true;
			return false;
		}

		if (jQuery("#allioc-container-" + sequence + " .allioc-conversation-area > .allioc-conversation-bubble-container-response:last-child").find('.card-option a').length > 0) {
			
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area > .allioc-conversation-bubble-container-response:last-child").addClass('allioc-card-wrapper');
			
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area > .allioc-conversation-bubble-container-response:last-child").find('.card-option a').each(function(e) {
				if (jQuery(this).text() == '') {
					jQuery(this).parent().parent().hide();
				}
				jQuery(this).off('click').on('click', function(e) {
					var title = jQuery(this).attr('title');
					if (title !== "") {
						jQuery("input.allioc-text").val(title);
						jQuery("#allioc-input-area .allioc-send-button").trigger('click');
						disabledInput(this);
					}
				});
			});

			if (jQuery("#allioc-container-" + sequence + " .allioc-conversation-area > .allioc-conversation-bubble-container-response:last-child").find('form').length > 0) {
				resetForm();
			}

			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area > .allioc-conversation-bubble-container-response:last-child").find('.card-footer a').off('click').on('click', function(e) {
				var title = jQuery(this).attr('title');
				if (title !== "") {
					jQuery("input.allioc-text").val(title);
					jQuery("#allioc-input-area .allioc-send-button").trigger('click');
				}
			});
		}
		
		//jQuery('input.allioc-text').focus();
	/*} else {
		textResponse(allioc_script_vars.messages.internal_error, sequence);
	}
*/
	jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
			.scrollTop(jQuery("#allioc-container-" + sequence + " .allioc-conversation-area")
			.prop("scrollHeight"));

	if (jQuery("#allioc-container-" + sequence + " #allioc-debug-data").length) {
		var debugData = JSON.stringify(response, undefined, 2);
		jQuery("#allioc-container-" + sequence + " #allioc-debug-data").text(debugData);
	}
}

/**
 * Checks if messages support a specific platform
 *
 * @param messages
 * @param platform
 * @returns {Boolean}
 */
function hasPlatform(messages, platform) {
	var numMessages = messages.length;
	var index = 0;
	for (index; index<numMessages; index++) {
		var message = messages[index];
		if (message.platform === platform) {
			return true;
		}
	}

	return false;
}

/**
 * Displays a text response
 *
 * @param text
 * @param sequence
 * @returns
 */
function textResponse(text, sequence) {
	if (!text)
		return false;
	if (text === "") {
		return false;
	}

	jQuery('.allioc-text').data('curdate', new Date().getTime());
	var date = new Date();
	if (text.text && text.text.length > 0) {
		jQuery('#allioc-input-area').css('display', 'flex');
		jQuery.each(text.text, function(index, item) {
			var pos = item.indexOf('$$menu');
			if (pos >= 0) {

				if (item.indexOf('$$input') < 0)
					jQuery('#allioc-input-area').hide();				
				//var str = '<div class="chat-card no-image">';
				// if (item.substring(6, 7) == "1")
				//str = '<div class="chat-card type2 no-image">';
				var header = "";
				var htype = 0;

				if (pos > 0) {
					header = item.substring(0, pos);
					item = item.substring(pos);
				}

				htype = item.substring(6, 7);
				
				var strs = item.split(/\n/);				
				var footers = [];
				var contents = {};

				for ( var i in strs) {

					var text = strs[i];					
					var els = strs[i].split(" ");
					if (els[0].indexOf("$$menu") >= 0) {
						if (els.length > 1) {
							els.shift();
							header = els.join(' ').trim();
						}
					} else if (els[0].indexOf("#footer") >= 0) {
						if (els.length > 1) {
							els.shift();
							footers.push(els.join(' ').trim());
						}
					} else {
						var index = els[0].trim();
						els.shift();
						contents[index] = els.join(' ').trim();
					}

					/*
					if (jQuery.isNumeric(strs[i].substring(0, 2))) {
						if (bcontent)  {
							bcontent =false;
							str += '<div class="card-content"><ul class="card-option">';
						}
							
							str+= '<li class="card-el"><div class="description"><a title="' + strs[i].substring(0, 2).trim() + '">' + strs[i].substring(2)+ '</a></div></li>';
					} else if (strs[i].indexOf('$$menu') >= 0) {
						var sheader = strs[i].replace('$$menu', '');						
						str += '<div class="card-header"><a>' + sheader.substring(1).trim() + '</a></div>';
					} else {
							var nextprev = strs[i].split('.');							
							if (bfooter == 1) {
								str += '</ul></div>';
								str += '<div class="card-footer col-wrap $$footer">';								
							}

							bfooter++;
							if (nextprev.length > 1) {
								str += '<div class="col"><a title="' + nextprev[0] + '">'+ nextprev[1] + '</a></div>';
							}
							
					}*/
				}

				/*	if (bfooter > 1) 
						str += '</div>';
					else 
						str += '</ul></div>';
					
					if (bfooter == 2) {
						str = str.replace('$$footer', 'one-column');
					}
					str += '</div>';
					*/

					var str = '<div class="chat-card no-image">';

					if (htype == "1")
						str = '<div class="chat-card type2 no-image">';

					str += '<div class="card-header">' + header.trim() + '</div>';

				
						str += '<div class="card-content"><ul class="card-option">';
						for (var i in contents) {
							str+= '<li class="card-el"><div class="description"><a title="' + i.trim() + '">' + contents[i].trim() + '</a></div></li>';
						}
						str += '</ul></div>';
					

					if (footers.length > 0) {
						str += '<div class="card-footer col-wrap $$footer">';
						
						for (var i in footers) {
							var nextprev = footers[i].split('.');
							if (nextprev.length > 1) {
								str += '<div class="col"><a title="' + nextprev[0] + '">'+ nextprev[1] + '</a></div>';
							} else {
								str += '<div class="col"><a>'+ footers[i] + '</a></div>';
							}
						}

						if (footers.length == 1) 
							str = str.replace('$$footer', 'one-column');
						else
							str = str.replace('$$footer', '');

						str += '</div>';
					}

					str += '</div>';

					item = str;
			}
			var reload = false;
			if (item.indexOf("[Payment Form]") >= 0) {
				jQuery('.allioc-container .cell.example.example2').remove();
				template = jQuery('#hidden-template').html();
				item = item.replace("[Payment Form]", template);
				//reload = true;
			} 

			var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-response\"><div class=\"allioc-conversation-bubble-container-response-wrapper\"><a class=\"chat-user-icon\"><img src=\"" + allioc_script_vars.user_icon + "\"></img></a><div class=\"allioc-conversation-bubble allioc-conversation-response allioc-is-active allioc-text-response\">" + item + "</div></div>";
			if (allioc_script_vars.show_time) {
				innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
			}
			innerHTML += "</div>";
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").scrollTop(100000);
			/*if (reload) {
				resetForm();
				reload = false;
			}*////////////
		});
	} else {
		text = allioc_script_vars.messages.internal_error;
		var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-response\"><div class=\"allioc-conversation-bubble-container-response-wrapper\"><a class=\"chat-user-icon\"><img src=\"" + allioc_script_vars.user_icon + "\"></img></a><div class=\"allioc-conversation-bubble allioc-conversation-response allioc-is-active allioc-text-response\">" + text + "</div></div>";
			if (allioc_script_vars.show_time) {
				innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
			}
			innerHTML += "</div>";
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);
			jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").scrollTop(100000);
	}
	
}

/**
 * Displays a image response
 *
 * @param imageUrl
 * @param sequence
 * @returns
 */
function imageResponse(imageUrl, sequence) {
	if (imageUrl === "") {
		textResponse(allioc_script_vars.messages.internal_error, sequence)
	} else {
		// FIXME wait for image to load by creating HTML first
		var date = new Date();
		var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-response\"><div class=\"allioc-conversation-bubble allioc-conversation-response allioc-is-active allioc-image-response\"><img src=\"" + imageUrl + "\"/></div>";
		if (allioc_script_vars.show_time) {
			innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
		}
		innerHTML += "</div>";
		jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);
	}
}

/**
 * Card response
 *
 * @param title
 * @param subtitle
 * @param buttons
 * @param text
 * @param postback
 * @param sequence
 */
function cardResponse(title, subtitle, buttons, text, postback, sequence) {
	var html = "<div class=\"allioc-card-title\">" + title + "</div>";
	html += "<div class=\"allioc-card-subtitle\">" + subtitle + "</div>";
	// TODO
}

/**
 * Quick replies response
 *
 * @param title
 * @param replies
 * @param sequence
 */
function quickRepliesResponse(title, replies, sequence) {

	var html = "<div class=\"allioc-quick-replies-title\">" + title + "</div>";

	var index = 0;
	for (index; index<replies.length; index++) {
		html += "<input type=\"button\" class=\"allioc-quick-reply\" value=\"" + replies[index] + "\" />";
	}

	var date = new Date();
	var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-response\"><div class=\"allioc-conversation-bubble allioc-conversation-response allioc-is-active allioc-quick-replies-response\">" + html + "</div>";
	if (allioc_script_vars.show_time) {
		innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
	}
	innerHTML += "</div>";
	jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);

	jQuery("#allioc-container-" + sequence + " .allioc-conversation-area .allioc-is-active .allioc-quick-reply").click(function(event) {
		event.preventDefault();
		jQuery("#allioc-container-" + sequence + " .allioc-conversation-area .allioc-conversation-request").removeClass("allioc-is-active");
		var text = jQuery(this).val()
		var date = new Date();
		var innerHTML = "<div class=\"allioc-conversation-bubble-container allioc-conversation-bubble-container-request\"><div class=\"allioc-conversation-bubble allioc-conversation-request allioc-is-active\">" + escapeTextInput(text) + "</div>";
		if (allioc_script_vars.show_time) {
			innerHTML += "<div class=\"allioc-datetime\">" + date.toLocaleTimeString() + "</div>";
		}
		if (allioc_script_vars.show_loading) {
			innerHTML += "<div class=\"allioc-loading\"><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /><i class=\"allioc-icon-loading-dot\" /></div>";
		}
		innerHTML += "</div>";
		jQuery("#allioc-container-" + sequence + " .allioc-conversation-area").append(innerHTML);
		textQuery(text, sequence);
	});

}

/**
 * Custom payload
 *
 * @param payload
 */
function customPayload(payload, sequence) {

}


var entityMap = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;',
  '/': '&#x2F;',
  '`': '&#x60;',
  '=': '&#x3D;'
};

/**
 * Escapes HTML in text input
 */
function escapeTextInput(text) {
  return String(text).replace(/[&<>"'`=\/]/g, function (s) {
    return entityMap[s];
  });
}
/*
var worker = new Worker(allioc_script_vars.worker_url);
//worker.start();
worker.addEventListener('message', function(e) {
	var message = e.data;
	if (message == "reload") {
		window.location.reload();
	}
});*/

jQuery(window).load(function(e) {
	//jQuery('input.allioc-text').focus();
});

function chatreload() {
	var container = jQuery(".allioc-content-overlay-container .allioc-container");
	jQuery(container).find('.allioc-conversation-area').html('');
		var containerId = jQuery(container).attr('id');
		var parts = containerId.split("-");
		var sequence = parts[2];

		welcomeIntent(sequence);
		//jQuery('input.allioc-text').focus();
}
var stripe;
function resetForm() {
	stripe = Stripe(allioc_script_vars.stripe_pk);
  var elements = stripe.elements({
    fonts: [
      {
        cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
      },
    ],
    // Stripe's examples are localized to specific languages, but if
    // you wish to have Elements automatically detect your user's locale,
    // use `locale: 'auto'` instead.
  });

  // Floating labels
  var inputs = document.querySelectorAll('.cell.example.example2 .input');
  Array.prototype.forEach.call(inputs, function(input) {
    input.addEventListener('focus', function() {
      input.classList.add('focused');
    });
    input.addEventListener('blur', function() {
      input.classList.remove('focused');
    });
    input.addEventListener('keyup', function() {
      if (input.value.length === 0) {
        input.classList.add('empty');
      } else {
        input.classList.remove('empty');
      }
    });
  });

  var elementStyles = {
    base: {
      color: '#32325D',
      fontWeight: 500,
      fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
      fontSize: '16px',
      fontSmoothing: 'antialiased',

      '::placeholder': {
        color: '#CFD7DF',
      },
      ':-webkit-autofill': {
        color: '#e39f48',
      },
    },
    invalid: {
      color: '#E25950',

      '::placeholder': {
        color: '#FFCCA5',
      },
    },
  };

  var elementClasses = {
    focus: 'focused',
    empty: 'empty',
    invalid: 'invalid',
  };

  var cardNumber = elements.create('cardNumber', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardNumber.mount('#example2-card-number');

  var cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardExpiry.mount('#example2-card-expiry');

  var cardCvc = elements.create('cardCvc', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardCvc.mount('#example2-card-cvc');

  registerElements([cardNumber, cardExpiry, cardCvc], 'example2');
}



function registerElements(elements, exampleName) {
  var formClass = '.' + exampleName;
  var example = document.querySelector(formClass);

  var form = example.querySelector('form');
  var error = form.querySelector('.error');
  var errorMessage = error.querySelector('.message');

  function enableInputs() {
    Array.prototype.forEach.call(
      form.querySelectorAll(
        "input[type='text'], input[type='email'], input[type='tel']"
      ),
      function(input) {
        input.removeAttribute('disabled');
      }
    );
  }

  function disableInputs() {
    Array.prototype.forEach.call(
      form.querySelectorAll(
        "input[type='text'], input[type='email'], input[type='tel']"
      ),
      function(input) {
        input.setAttribute('disabled', 'true');
      }
    );
  }

  function triggerBrowserValidation() {
    // The only way to trigger HTML5 form validation UI is to fake a user submit
    // event.
    var submit = document.createElement('input');
    submit.type = 'submit';
    submit.style.display = 'none';
    form.appendChild(submit);
    submit.click();
    submit.remove();
  }

  // Listen for errors from each Element, and show error messages in the UI.
  var savedErrors = {};
  elements.forEach(function(element, idx) {
    element.on('change', function(event) {
      if (event.error) {
        error.classList.add('visible');
        savedErrors[idx] = event.error.message;
        errorMessage.innerText = event.error.message;
      } else {
        savedErrors[idx] = null;

        // Loop over the saved errors and find the first one, if any.
        var nextError = Object.keys(savedErrors)
          .sort()
          .reduce(function(maybeFoundError, key) {
            return maybeFoundError || savedErrors[key];
          }, null);

        if (nextError) {
          // Now that they've fixed the current error, show another one.
          errorMessage.innerText = nextError;
        } else {
          // The user fixed the last error; no more errors.
          error.classList.remove('visible');
        }
      }
    });
  });

	// Listen on the form's 'submit' handler...
	jQuery('.cell.example.example2').parent().parent().find('.card-el a').off('click').on('click', function(e) {
		e.preventDefault();
		var self = this;
    // Trigger HTML5 validation UI on the form if any of the inputs fail
    // validation.
    var plainInputsValid = true;
    Array.prototype.forEach.call(form.querySelectorAll('input'), function(
      input
    ) {
      if (input.checkValidity && !input.checkValidity()) {
        plainInputsValid = false;
        return;
      }
    });
    if (!plainInputsValid) {
      triggerBrowserValidation();
      return;
    }

    // Show a loading screen...
    example.classList.add('submitting');

    // Disable all inputs.
    disableInputs();

   
    var zip = form.querySelector('#' + exampleName + '-zip');
    var additionalData = {
      address_zip: zip ? zip.value : undefined,
    };

    // Use Stripe.js to create a token. We only need to pass in one Element
    // from the Element group in order to create a token. We can also pass
    // in the additional customer data we collected in our form.
    stripe.createToken(elements[0], additionalData).then(function(result) {
      // Stop loading!
      example.classList.remove('submitting');

      if (result.token) {
        // If we received a token, show the token ID.
      
        gtoken = result.token.id;

				var title = jQuery(self).attr('title');
				if (title !== "") {
					jQuery("input.allioc-text").val(title);
					jQuery("#allioc-input-area .allioc-send-button").trigger('click');
				}
      } else {
        // Otherwise, un-disable inputs.
        enableInputs();
      }
		});
		
	});
}

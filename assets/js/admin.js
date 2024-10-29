/**
 * Pickers
 */
jQuery(document).ready(function() {
		
	jQuery('.color-picker').wpColorPicker({
	    defaultColor: false,
	    change: function(event, ui){},
	    clear: function() {},
	    hide: true,
	    palettes: true
	});

	jQuery('input#allioc_logo_manager').click(function(e) {

		var img_id = jQuery(this).data('id');
		e.preventDefault();
		var image_frame;
		if(image_frame){
			image_frame.open();
		}
		// Define image_frame as wp.media object
		image_frame = wp.media({
						title: 'Select Media',
						multiple : false,
						library : {
							type : 'image',
						}
					});

					image_frame.on('close',function() {
						// On close, get selections and save to the hidden input
						// plus other AJAX stuff to refresh the image preview
						var selection =  image_frame.state().get('selection');
						var gallery_ids = new Array();
						var my_index = 0;
						selection.each(function(attachment) {
						gallery_ids[my_index] = attachment['id'];
						my_index++;
						});
						var ids = gallery_ids.join(",");
						jQuery('input.img-media-value' + img_id).val(ids);
						Refresh_Image(ids, img_id);
					});

					image_frame.on('open',function() {
					// On open, get the id from the hidden input
					// and select the appropiate images in the media manager
					var selection =  image_frame.state().get('selection');
					var ids = jQuery('input.img-media-value' + img_id).val().split(',');
					ids.forEach(function(id) {
						var attachment = wp.media.attachment(id);
						attachment.fetch();
						selection.add( attachment ? [ attachment ] : [] );
					});

					});

				image_frame.open();
	});   
	
	function Refresh_Image(the_id, img_id){
        var data = {
            action: 'myprefix_get_image',
            id: the_id
        };

        jQuery.get(ajaxurl, data, function(response) {

            if(response.success === true) {
                jQuery('#allioc-preview-image' + img_id).replaceWith( response.data.image );
            }
        });
	}

	
});
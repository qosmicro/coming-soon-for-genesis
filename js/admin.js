/*

Genesis Coming Soon
https://qosmicro.com/plugins/coming-soon-for-genesis
 _____     _____ _____ _             
|     |___|   __|     |_|___ ___ ___ 
|  |  | . |__   | | | | |  _|  _| . |
|__  _|___|_____|_|_|_|_|___|_| |___|
   |__|                              

================================================================== */

jQuery(document).ready(function($) {
	"use strict";
	
	// Open Media Popup
	$('.upload-button').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
		
		var mediaFrame;
		var boxTitle = $(this).data('boxtitle');
		var boxButton = $(this).data('boxbutton');
		var imgIdInput = $(this).data('target');
		
		// If the media frame already exists, reopen it.
		if( mediaFrame ) {
			mediaFrame.open();
			return;
		}

		// Create a new media frame
		mediaFrame = new wp.media.view.MediaFrame.Select({
			title:  boxTitle,
			button: { text: boxButton },
			multiple: false,
		});
		
		// When image is selected
		mediaFrame.on( 'select', function() {

			// Get media attachment details from the frame state
			var attachment = mediaFrame.state().get('selection').first().toJSON();

			// Send the attachment id to our hidden input
			$('#'+imgIdInput).val( attachment.url );

		});
		
		// Open Frame
		mediaFrame.open();

	});

	// Open Color Picker
	$('.color-field').each(function(){
		$(this).wpColorPicker();
	});

});





























/* --- end */
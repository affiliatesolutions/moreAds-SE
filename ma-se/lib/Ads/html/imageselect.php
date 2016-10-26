<div class="mase-bs">
    <input name="_media_id" type="hidden" class="media-id" value="<?php echo (isset($_media_id) && !empty($_media_id)) ? $_media_id : ''; ?>" />
    <img src="<?php echo (isset($_media_url) && !empty($_media_url)) ? $_media_url : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mPYXw8AAgABP/FSvcAAAAAASUVORK5CYII='; ?>" class="media-image" style="padding: 5px; margin: 20px 5px 20px 0; border: 2px dotted; display: block;" />
    <input name="media_url" type="hidden" class="media-input" />
    <button id="ma_ad_gfx" name="ma_ad_gfx" class="btn btn-primary media-button"><?php _e('Select picture', MASE_TEXT_DOMAIN); ?></button>
</div>
<script type="text/javascript">
    var gk_media_init = function(selector, button_selector)  {
        var clicked_button = false;

        jQuery(selector).each(function (i, input) {
            var button = jQuery(input).next(button_selector);
            button.click(function (event) {
                event.preventDefault();
                var selected_img;
                clicked_button = jQuery(this);

                // check for media manager instance
                if(wp.media.frames.gk_frame) {
                    wp.media.frames.gk_frame.open();
                    return;
                }
                // configuration of the media manager new instance
                wp.media.frames.gk_frame = wp.media({
                    title: 'Select image',
                    multiple: false,
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Use selected image'
                    }
                });

                // Function used for the image selection and media manager closing
                var gk_media_set_image = function() {
                    var selection = wp.media.frames.gk_frame.state().get('selection');
                    if (!selection) return;

                    // iterate through selected elements
                    var fnd = false;
                    var fnd2 = false;
                    var re = /(\d+x\d+)/gmi;
                    var re2 = /[^\x00-\x7F]/gmi;
                    var m;
                    var m2;
                    selection.each(function(attachment) {
                        while ((m = re.exec(attachment.attributes.url)) !== null) {
                            if (m.index === re.lastIndex) {
                                re.lastIndex++;
                            }
                            fnd=m[0];
                        }

                        while ((m2 = re2.exec(attachment.attributes.url)) !== null) {
                            if (m2.index === re2.lastIndex) {
                                re2.lastIndex++;
                            }
                            fnd2=m2[0];
                        }
                    });

                    if(fnd && this == "close") {
                        alert("<?php printf(__('The picture name contains the string %s and would get blocked by every adblocker. Please change the name of your picture for example to hff9tfhasd and upload it again.', MASE_TEXT_DOMAIN), '"+fnd+"'); ?>");
                        return false;
                    }
                    if(fnd2 && this == "close") {
                        alert("<?php printf(__('The picture name contains non ascii chars and would get blocked by every adblocker. Please change the name of your picture for example to mysolution123 and upload it again.', MASE_TEXT_DOMAIN), '"+fnd2+"'); ?>");
                        return false;
                    }
                    if(fnd) {
                        return false;
                    }
                    if(fnd2) {
                        return false;
                    }

                    // iterate through selected elements
                    selection.each(function(attachment) {
                        clicked_button.prev(selector).val(attachment.attributes.url);
                        clicked_button.prev(selector).prev('.media-image').attr('src', attachment.attributes.url);
                        clicked_button.prev(selector).prev('.media-image').attr('src', attachment.attributes.url);
                        clicked_button.prev(selector).prev('.media-image').prev('.media-id').val(attachment.attributes.id);
                    });
                };

                // closing event for media manger
                wp.media.frames.gk_frame.on('close', gk_media_set_image, 'close');
                // image selection event
                wp.media.frames.gk_frame.on('select', gk_media_set_image, 'select');
                // showing media manager
                wp.media.frames.gk_frame.open();
            });
        });
    };
    gk_media_init('.media-input', '.media-button');
</script>
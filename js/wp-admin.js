(function($) {

    /* Disable the wpview TinyMCE plugin for ACF WYSIWYG fields (unless the enabled in settings) */

    acf.addFilter('wysiwyg_tinymce_settings', function(mceInit, id, field){
        if (field.$el.hasClass('ks-disable-autoembed')) {
            var plugins = mceInit['plugins'].split(',');
            var wpviewIndex = plugins.indexOf('wpview');

            if (wpviewIndex > -1) {
                plugins.splice(wpviewIndex, 1);
            }
            mceInit['plugins'] = plugins.join(',');
        }

        return mceInit;		
    });
    
    
    
    /* Update character count for text and textarea fields with a character limit */
    
    acf.field.extend({
		type: 'text',
		events: {
			'input input': 'onChangeValue',
			'change input': 'onChangeValue'
		},
		onChangeValue: function(e){
            var countContainer = e.$el.closest('.acf-input').find('.ks-char-count');
            
            if (countContainer.length != 0 && e.$el[0].hasAttribute('maxlength')) {
                var max = e.$el.attr('maxlength');
                var cur = e.$el.val().length;
                
                countContainer.find('.ks-char-count__current').text(cur);
            }
		}
	});
    
    acf.field.extend({
		type: 'textarea',
		events: {
			'input textarea': 'onChangeValue',
			'change textarea': 'onChangeValue'
		},
		onChangeValue: function(e){
            var countContainer = e.$el.closest('.acf-input').find('.ks-char-count');
            
            if (countContainer.length != 0 && e.$el[0].hasAttribute('maxlength')) {
                var max = e.$el.attr('maxlength');
                var cur = e.$el.val().length;
                
                countContainer.find('.ks-char-count__current').text(cur);
            }
		}
	});



    /* Update color picker swatches with theme colors */

    acf.addFilter('color_picker_args', function(args, field){
        args.width = 400;
        args.palettes = [];

        $.each(wpVars.colorList, function(id, hex) {
            args.palettes.push(hex);
        });

        return args;
    });



    /* Add color swatches to theme selector */

    function addThemeSelectorSwatches(field) {
        if (field.$el.hasClass('kf-theme-selector') && field.$el.find('.kf-selector-swatches').length == 0) {
            var optionLabels = field.$el.find('.acf-input .acf-button-group > label');

            optionLabels.each(function() {
                var input = $(this).find('input');
                var swatches = $('<span class="kf-selector-swatches" />');

                for (var i = 0; i < 6; i++) {
                    $('<span class="kf-selector-swatches__item" />').css('background-color', wpVars.colorList[wpVars.themeMaps[input.attr('value')][i]]).appendTo(swatches);
                }

                input.after(swatches);
            });
        }
    }

    acf.addAction('new_field/type=button_group', addThemeSelectorSwatches);

})(jQuery);

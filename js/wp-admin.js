(function($) {

    /* Disable the wpview TinyMCE plugin for ACF WYSIWYG fields (unless enabled in settings) */

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



    /* Update TinyMCE settings for ACF WYSIWYG fields based on selected toolbar */

    acf.addFilter('wysiwyg_tinymce_settings', function(mceInit, id, field){
        if (field.data.toolbar in wpVars.wysiwygConfigs) {
            var config = wpVars.wysiwygConfigs[field.data.toolbar];
            var mediaAllowed = field.$el.find('.wp-media-buttons').length > 0;

            // update block_formats setting
            if ('formats' in config) {
                mceInit['block_formats'] = config['formats'];
            }

            // update valid_elements setting
            if (mediaAllowed && 'elements_with_media' in config) {
                mceInit['valid_elements'] = config['elements_with_media'];
            } else if ('elements' in config) {
                mceInit['valid_elements'] = config['elements'];
            }

            // update valid_styles setting
            if (mediaAllowed && 'styles_with_media' in config) {
                mceInit['valid_styles'] = config['styles_with_media'];
            } else if ('styles' in config) {
                mceInit['valid_styles'] = config['styles'];
            }
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

        $.each(wpVars.colorSwatchList, function(i, colorID) {
            args.palettes.push(wpVars.colorList[colorID]);
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



    /* Add color swatches to button color variation selector */

    function addButtonColorSelectorSwatches(field) {
        if (field.$el.hasClass('kf-button-color-selector') && field.$el.find('.kf-selector-swatches').length == 0) {
            var optionLabels = field.$el.find('.acf-input .acf-button-group > label');

            optionLabels.each(function() {
                var input = $(this).find('input');

                if (input.attr('value') == 'default') return;

                var swatchesDefault = $('<span class="kf-selector-swatches" />');
                var swatchesHover = $('<span class="kf-selector-swatches" />');

                $('<span class="kf-selector-swatches__item" />').css('background-color', wpVars.buttonColorVariations[input.attr('value')]['default']['bg']).appendTo(swatchesDefault);
                $('<span class="kf-selector-swatches__item" />').css('background-color', wpVars.buttonColorVariations[input.attr('value')]['default']['text']).appendTo(swatchesDefault);

                $('<span class="kf-selector-swatches__item" />').css('background-color', wpVars.buttonColorVariations[input.attr('value')]['hover']['bg']).appendTo(swatchesHover);
                $('<span class="kf-selector-swatches__item" />').css('background-color', wpVars.buttonColorVariations[input.attr('value')]['hover']['text']).appendTo(swatchesHover);

                input.after(swatchesDefault);
                swatchesDefault.after(swatchesHover);
            });
        }
    }
    acf.addAction('new_field/type=button_group', addButtonColorSelectorSwatches);



    /* Hide search section from the flex layout selector if search is not enabled */

    function hideSearchFlexSection(field) {
        if (field.$el.hasClass('kf-hide-search')) {
            var tmplPopup = field.$el.find('.tmpl-popup:last');
            var popupProtoEl = $(tmplPopup.html());

            popupProtoEl.find('[data-layout="search"]').parent().remove();

            tmplPopup.html(popupProtoEl.outerHTML());
        }
    }
    acf.addAction('new_field/key=field_5f296dcbe2111', hideSearchFlexSection);
    
    
    
    /* Prevent values of disabled fields from moving between repeater and flexible content rows */
    
    function storeInitialRowOrder(field) {
        var fieldRows = (field.type == 'repeater') ? field.$rows() : field.$layouts();
        var initialRowOrder = [];
        
        fieldRows.each(function() {
            initialRowOrder.push($(this).attr('data-id'));
        });
        
        field.set('initialRowOrder', initialRowOrder);
    }
    acf.addAction('prepare_field/type=repeater', storeInitialRowOrder);
    acf.addAction('prepare_field/type=flexible_content', storeInitialRowOrder);
    
    $('#post').on('submit', function(e) {
        var form = $(this);
        var repeaterFields = acf.getFields({
            type: 'repeater'
        });
        var fcFields = acf.getFields({
            type: 'flexible_content'
        });
        var allFields = repeaterFields.concat(fcFields);
        var skipValidationNames = [];
        
        $.each(allFields, function(i, field) {
            if (field.has('initialRowOrder')) {
                var fieldRows = (field.type == 'repeater') ? field.$rows() : field.$layouts();
                var initialRowOrder = field.get('initialRowOrder');
                var newRowOrder = [];
                
                fieldRows.each(function() {
                    newRowOrder.push($(this).attr('data-id'));
                });
                
                // compare the old/new row order to determine if it has changed
                $.each(newRowOrder, function (rowIndex, rowID) {
                    if ((rowIndex < initialRowOrder.length && initialRowOrder[rowIndex] != rowID) || (rowIndex >= initialRowOrder.length && $.inArray(rowID, initialRowOrder) > -1)) {
                        var subFields = acf.getFields({
                            parent: fieldRows.filter('[data-id=' + rowID + ']')
                        });
                        
                        // manually enable any disabled fields within moved rows to allow data to be saved in the proper row
                        $.each(subFields, function (j, subField) {
                            if (subField.$el.prop('disabled')) {
                                subField.$input().prop('disabled', false).prop('required', false);
                                
                                skipValidationNames.push(subField.getInputName()); // add this field to the list to skip validation
                            }
                        });
                    }
                });
            }
        });
        
        if (skipValidationNames.length > 0) {
            // remove inputs from past failed submissions
            form.find('[name^="_kf_acf_skip_validation"]').remove();
            
            // add hidden inputs to pass the skip validation list to PHP
            $.each(skipValidationNames, function (i, name) {
                $('<input type="hidden" name="_kf_acf_skip_validation[' + i + ']" />').attr('value', name).appendTo(form);
            });
        }
    });

})(jQuery);

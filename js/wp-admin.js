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



/* Update color picker swatches with theme colors */

acf.addFilter('color_picker_args', function(args, field){
    args.width = 400;
    args.palettes = [];
    
    jQuery.each(wpVars.colorList, function(id, hex) {
        args.palettes.push(hex);
    });
    
    return args;
});



/* Add color swatches to theme selector */

function addThemeSelectorSwatches(field) {
    if (field.$el.hasClass('kf-theme-selector') && field.$el.find('.kf-selector-swatches').length == 0) {
        var optionLabels = field.$el.find('.acf-input .acf-button-group > label');
        
        optionLabels.each(function() {
            var input = jQuery(this).find('input');
            var swatches = jQuery('<span class="kf-selector-swatches" />');
            
            for (var i = 0; i < 6; i++) {
                jQuery('<span class="kf-selector-swatches__item" />').css('background-color', wpVars.colorList[wpVars.themeMaps[input.attr('value')][i]]).appendTo(swatches);
            }
            
            input.after(swatches);
        });
    }
}

acf.addAction('new_field/type=button_group', addThemeSelectorSwatches);

/* Enhance Mouse Focus */

var enhanceMouseFocusActive = false;
var enhanceMouseFocusEnabled = false;
var enhanceMouseFocusElements = $();
var enhanceMouseFocusNewElements = $();


function enhanceMouseFocusUpdate() {
    if (enhanceMouseFocusEnabled) {
        // add any new focusable elements
        enhanceMouseFocusNewElements = $('button, input[type="submit"], input[type="button"], [tabindex]:not(input, textarea)').not(enhanceMouseFocusElements);
        enhanceMouseFocusElements = enhanceMouseFocusElements.add(enhanceMouseFocusNewElements);
        
        // if an element gets focus due to a mouse click, prevent it from keeping focus
        enhanceMouseFocusNewElements.mousedown(function() {
            enhanceMouseFocusActive = true;
            setTimeout(function(){
                enhanceMouseFocusActive = false;
            }, 50);
        }).on('focus', function() {
            if (enhanceMouseFocusActive) {
                $(this).blur();
            }
        });
    }
}

function initEnhanceMouseFocus() {
    enhanceMouseFocusElements = $();
    enhanceMouseFocusEnabled = true;
    
    enhanceMouseFocusUpdate();
    
    // update focusable elements on Gravity Forms render
    $(document).on('gform_post_render', function() {
        enhanceMouseFocusUpdate();
    });
}



/* FitVids */

function initFitVids() {
    $('.site-content').fitVids();
}



/* Last Item in Flex Row */

function initLastItemFlexRow() {
    var itemSets = [];
    var resizeTimeout = null;
    
    
    function addLastItemClass(items, className) {
        var lastItem = false;
        
        items.each(function() {
            if (lastItem && lastItem.offset().top != $(this).offset().top) {
                lastItem.addClass(className);
            } else if (lastItem) {
                lastItem.removeClass(className);
            }
            
            lastItem = $(this);
        }).last().addClass(className);
    }
    
    function triggerLastItemUpdate() {
        $.each(itemSets, function(i, set) {
            addLastItemClass(set.items, set.className);
        });
    }
    
    function addItemSet(items, className) {
        if (items.length > 1) {
            itemSets.push({
                items: items,
                className: className
            });
        }
    }
    
    
    $('.site-nav_lines').each(function() {
        addItemSet($(this).find('.site-nav__item_line'), 'site-nav__item_last');
    });
    $('.sub-footer-2').each(function() {
        addItemSet($(this).find('.sub-footer-2__item'), 'sub-footer-2__item_last');
    });
    $('.address').each(function() {
        addItemSet($(this).find('.address__line'), 'address__line_last');
    });
    
    triggerLastItemUpdate();
    $(window).on('load', triggerLastItemUpdate);
    $(window).resize(function() {
        triggerLastItemUpdate();
        
        if (resizeTimeout) {
            clearTimeout(resizeTimeout);
        }
        resizeTimeout = setTimeout(triggerLastItemUpdate, 500);
    });
}



/* General */

$(function() {
    initFitVids();
    initLastItemFlexRow();
    initEnhanceMouseFocus();
});

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



/* Dialog Boxes */

function initDialogBoxes() {
    var throttledUpdateDialogBoxPosition = kinectivThrottle(updateDialogBoxPosition, 100);
    var body = $('body');
    var boxWrap = $('<div class="dialog-box" />');
    var boxContent = $('<div class="dialog-box__content" />').appendTo(boxWrap);
    var box = $('<div class="dialog-box__box" tabindex="-1" />').appendTo(boxContent);
    var boxEnd = $('<div class="dialog-box__end" tabindex="0" />').appendTo(boxContent);
    boxEnd.focus(function() {
        box.focus();
        return false;
    });
    
    var individualBoxes = $();
    
    
    function openDialogBox(id) {
        var scrollTop = $(window).scrollTop();
        
        box.children().not(individualBoxes).remove();
        individualBoxes.css('display', 'none');
        individualBoxes.filter('[data-dialog-box-id="' + id + '"]').css('display', 'block');
        
        typeof contentAppended === 'function' && contentAppended();
        
        positionDialogBox();
        
        enhanceMouseFocusUpdate();
        
        boxWrap.addClass('dialog-box_active');
        
        boxWrap.data('lastFocus', $(document.activeElement));
        box.focus();
        $(window).scrollTop(scrollTop);
    }
    
    function openGalleryBox(content, contentAppended) {
        var scrollTop = $(window).scrollTop();
        
        box.children().not(individualBoxes).remove();
        individualBoxes.css('display', 'none');
        box.append(content);
        
        typeof contentAppended === 'function' && contentAppended();
        
        positionDialogBox();
        
        enhanceMouseFocusUpdate();
        
        boxWrap.addClass('dialog-box_active');
        
        boxWrap.data('lastFocus', $(document.activeElement));
        box.focus();
        $(window).scrollTop(scrollTop);
    }
    
    function closeDialogBox() {
        var scrollTop = $(window).scrollTop();
        
        boxWrap.removeClass('dialog-box_active');
        
        boxWrap.data('lastFocus').focus();
        $(window).scrollTop(scrollTop);
    }
    
    function positionDialogBox() {
        var margin = $(window).scrollTop() + 50;
        var windowHeight = $(window).height();
        var containerHeight = boxWrap.height();
        var boxHeight = box.height();
        
        box.css('margin-top', '');
        
        if (windowHeight > boxHeight + 100) {
            margin += ((windowHeight - boxHeight) / 2) - 50;
        }
        margin = Math.min(margin, containerHeight - boxHeight - 50);
        margin = Math.max(margin, 50);
        box.css('margin-top', margin + 'px');
    }
    
    function updateDialogBoxPosition() {
        if (box.outerHeight(true) > boxWrap.height() - 50) {
            positionDialogBox();
        }
    }
    
    function createDialogBox(id) {
        var content = $('[data-dialog-box-content="' + id + '"]').removeAttr('data-dialog-box-content');
        var closeButton = $('<button type="button" class="dialog-box__close"><span class="screen-reader-text">Close</span></button>');
        var individualContainer = $('<div />').attr('data-dialog-box-id', id).css('display', 'none');
        
        closeButton.click(closeDialogBox);
        closeButton.prependTo(content);
        
        individualContainer.append(content);
        individualContainer.appendTo(box);
        individualBoxes = individualBoxes.add(individualContainer);
    }
    
    function createGalleryBox(id, slide) {
        slide = (typeof slide === 'number') ? slide : 0; // default to first if no valid slide index is provided
        
        var content = $('<div class="box-slider" />');
        var slider = $('<div class="box-slider__slider" />').appendTo(content);
        var closeButton = $('<button type="button" class="dialog-box__close"><span class="screen-reader-text">Close</span></button>');
        
        // build slide for each image in the source element
        $('[data-gallery="' + id + '"]').find('[data-gallery-box-image]').each(function() {
            var slide = $('<div class="box-slider__slide"></div>').appendTo(slider);
            var src = $(this).attr('data-gallery-box-image');
            var alt = $(this).attr('data-alt');
            
            $('<div class="box-slider__image" role="img" />').attr('aria-label', alt).css('background-image', 'url("' + src + '")').appendTo(slide);
        });
        
        closeButton.click(closeDialogBox);
        closeButton.prependTo(content);
        
        openGalleryBox(content, function() {
            // initialize slick slider if there is more than one slide in the container
            if (slider.find('.box-slider__slide').length > 1) {
                slider.slick({
                    accessibility: false,
                    arrows: false,
                    initialSlide: slide,
                    speed: 400
                });
                
                $('<button type="button" class="box-slider__arrow box-slider__arrow_prev"><span class="screen-reader-text">Previous Slide</span></button>').click(function() {
                    slider.slick('slickPrev');
                }).appendTo(content);
                $('<button type="button" class="box-slider__arrow box-slider__arrow_next"><span class="screen-reader-text">Next Slide</span></button>').click(function() {
                    slider.slick('slickNext');
                }).appendTo(content);
            }
        });
    }
    
    function preloadGalleryBoxImages() {
        $('[data-gallery-box-image]').each(function() {
            $('<img />').attr('src', $(this).attr('data-gallery-box-image')).load(function() {
                $(this).remove();
            });
        });
    }
    
    
    $('.site').prepend(boxWrap);
    
    $(window).load(preloadGalleryBoxImages);
    
    $(window).resize(throttledUpdateDialogBoxPosition);
    $(document).on('gform_post_render gform_confirmation_loaded dialog_box_position', positionDialogBox);
    
    boxWrap.click(function(e) {
        if ($(e.target).hasClass('dialog-box') || $(e.target).hasClass('dialog-box__content')) {
            closeDialogBox();
        }
    });
    
    $(document).keydown(function(e) {
        if (boxWrap.hasClass('dialog-box_active')) {
            if (e.which === 27) {
                closeDialogBox();
            } else if (boxContent.find('.box-slider__slider.slick-slider').length > 0) {
                if (e.which === 37) {
                    boxContent.find('.box-slider__slider.slick-slider').slick('slickPrev');
                } else if (e.which === 39) {
                    boxContent.find('.box-slider__slider.slick-slider').slick('slickNext');
                }
            }
        }
    });
    
    $('[data-dialog-box-content]').each(function() {
        createDialogBox($(this).attr('data-dialog-box-content'));
    });
    
    $('[data-dialog-box]').click(function() {
        openDialogBox($(this).attr('data-dialog-box'));
    });
    $('[data-gallery-box]').click(function() {
        createGalleryBox($(this).attr('data-gallery-box'), parseInt($(this).attr('data-start')));
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



/* Masonry Grid */

function initMasonryGrid() {
    $('.image-masonry').each(function() {
        var masonry = $(this);
        var truncate = masonry.hasClass('image-masonry_truncate');
        var height = 250;
        
        if (masonry.hasClass('image-masonry_h_sm')) {
            height = 175;
        } else if (masonry.hasClass('image-masonry_h_lg')) {
            height = 350;
        }
        
        
        masonry.flexImages({
            container: '.image-masonry__item',
            object: '.image-masonry__block',
            rowHeight: height,
            truncate: truncate
        });
    });
}



/* Image Slider */

function initImageSlider() {
    $('.image-slider_slick').each(function() {
        var sliderWrap = $(this);
        var slider = sliderWrap.find('.image-slider__slider');
        var sliderButtons = sliderWrap.find('.image-slider__nav');
        
        
        // get all clones of a given slide
        function getSlideClones(index, total) {
            var slideList = slider.find('.image-slider__item'); // get all slides
            var parentSlide = slideList.filter('[data-slick-index="' + index + '"]'); // get slide with exact index
            var resultSlides = $().add(parentSlide); // add parent slide to list

            // if the parent slide exists, look for clones
            if (parentSlide.length > 0) {
                // loop through all slides
                slideList.each(function() {
                    var curIndex = parseInt($(this).attr('data-slick-index')); // get index of current slide in loop

                    // check if slide index is offset by the correct amount to make it a clone of the parent slide (if so, add it to the list)
                    if ((curIndex >= 0 && curIndex % total == index) || (curIndex < 0 && (curIndex - total) % total == index - total)) {
                        resultSlides = resultSlides.add($(this));
                    }
                });
            }

            return resultSlides; // return final list of slides
        }
        
        // update slide hover/focus states
        function updateSlideHover() {
            var slick = slider.slick('getSlick'); // get slick object

            // remove existing hover/focus classes
            slider.find('.image-slider__slide_hover').removeClass('image-slider__slide_hover');
            slider.find('.image-slider__slide_focus').removeClass('image-slider__slide_focus');

            // check each button for hover/focus
            sliderButtons.each(function() {
                var dir = ($(this).hasClass('image-slider__nav_prev')) ? -1 : 1; // get the change in slide index caused by this button
                var linkedSlide = slick.currentSlide + dir; // get the slide behind this button

                // get correct slide index if the direction causes looping to the beginning/end of the slider
                if (linkedSlide < 0) {
                    linkedSlide = slick.slideCount - 1;
                } else if (linkedSlide > slick.slideCount - 1) {
                    linkedSlide = 0;
                }

                // if button is hovered, add hover class to corresponding slide and all clones
                if ($(this).is(':hover')) {
                    getSlideClones(linkedSlide, slick.slideCount).each(function() {
                        $(this).find('.image-slider__slide').addClass('image-slider__slide_hover');
                    });
                }

                // if button is focused, add focus class to corresponding slide and all clones
                if ($(this).is(':focus')) {
                    getSlideClones(linkedSlide, slick.slideCount).each(function() {
                        $(this).find('.image-slider__slide').addClass('image-slider__slide_focus');
                    });
                }
            });
        }
        
        
        // initialize slick
        slider.slick({
            arrows: false,
            centerMode: true,
            centerPadding: '120px',
            speed: 600,
            touchThreshold: 4,
            responsive: [
                {
                    breakpoint: 1700,
                    settings: {
                        centerPadding: '100px'
                    }
                },
                {
                    breakpoint: 1450,
                    settings: {
                        centerPadding: '80px'
                    }
                }
            ]
        });
        
        // remove slick focus/blur function (which stops propagation to other handlers), and update enhanced mouse focus elements
        slider.off('focus.slick blur.slick', '*');
        enhanceMouseFocusUpdate();
        slider.on('init reInit breakpoint', function() {
            slider.off('focus.slick blur.slick', '*');
            enhanceMouseFocusUpdate();
        });
        
        // advance the slider in the corresponding direction on next/previous button click
        sliderButtons.click(function() {
            if ($(this).hasClass('image-slider__nav_prev')) {
                slider.slick('slickPrev');
            } else {
                slider.slick('slickNext');
            }
        });
        
        // update hover states on next/previous button hover/focus change
        sliderButtons.on('mouseenter mouseleave focus blur', function(e) {
            updateSlideHover();
        });
        
        // update slide styles on slide change
        slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            slider.find('.image-slider__slide_active').removeClass('image-slider__slide_active'); // remove class from active slide and any clones

            // add class to new slide and all clones
            getSlideClones(nextSlide, slick.slideCount).each(function() {
                $(this).find('.image-slider__slide').addClass('image-slider__slide_active');
            });

            // update hover/focus states after slide change begins
            setTimeout(function() {
                updateSlideHover();
            }, 10);
        });
        
        // update hover/focus states after slide change
        slider.on('afterChange', function() {
            updateSlideHover();
            
            // if old slide is focused, move focus to current slide
            if ($(document.activeElement).is('.image-slider__item') || $(document.activeElement).parents('.image-slider__item:not(.slick-active)').length > 0) {
                setTimeout(function() {
                    slider.find('.image-slider__item.slick-active').focus();
                }, 10);
            }
        });
    });
}



/* General */

$(function() {
    initFitVids();
    initMasonryGrid();
    initImageSlider();
    initLastItemFlexRow();
    initEnhanceMouseFocus();
    initDialogBoxes();
});

function kinectivThrottle(func, wait, options) {
    var timeout, context, args, result;
    var previous = 0;
    if (!options) options = {};

    var later = function() {
        previous = options.leading === false ? 0 : Date.now();
        timeout = null;
        result = func.apply(context, args);
        if (!timeout) context = args = null;
    };

    var throttled = function() {
        var now = Date.now();
        if (!previous && options.leading === false) previous = now;
            var remaining = wait - (now - previous);
            context = this;
            args = arguments;
            if (remaining <= 0 || remaining > wait) {
                if (timeout) {
                clearTimeout(timeout);
                timeout = null;
            }
            previous = now;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
        } else if (!timeout && options.trailing !== false) {
            timeout = setTimeout(later, remaining);
        }
        return result;
    };

    return throttled;
}

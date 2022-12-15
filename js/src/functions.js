/* Enhance Mouse Focus */

var enhanceMouseFocusActive = false;
var enhanceMouseFocusEnabled = false;
var enhanceMouseFocusElements = $();
var enhanceMouseFocusNewElements = $();


function enhanceMouseFocusUpdate() {
    if (enhanceMouseFocusEnabled) {
        // add any new focusable elements
        enhanceMouseFocusNewElements = $('button, input[type="submit"], input[type="button"], [tabindex]:not(input, textarea), a').not(enhanceMouseFocusElements);
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



/* Dialog Boxes */

function initDialogBoxes() {
    var throttledUpdateDialogBoxPosition = kinectivThrottle(updateDialogBoxPosition, 100);

    var siteHeader = $('.site-header');
    var siteContent = $('.site-content');
    var siteFooter = $('.site-footer');

    var boxWrap = $('<div class="dialog-box" />');
    var boxBg = $('<div class="dialog-box__bg" />').appendTo(boxWrap);
    var boxContent = $('<div class="dialog-box__content" />').appendTo(boxWrap);
    var box = $('<div class="dialog-box__box" tabindex="-1" />').appendTo(boxContent);
    var boxEnd = $('<div class="dialog-box__end" tabindex="0" />').appendTo(boxContent);
    boxEnd.focus(function() {
        box.focus();
        return false;
    });
    
    var individualBoxes = $();
    
    
    function openDialogBox(id, contentAppended) {
        var scrollTop = $(window).scrollTop();
        var individualBoxToShow = individualBoxes.filter('[data-dialog-box-id="' + id + '"]');
        
        box.children().not(individualBoxes).remove();
        individualBoxes.css('display', 'none');
        individualBoxToShow.css('display', 'block');
        
        boxBg.removeClass().addClass('dialog-box__bg').addClass('c_bg_' + wpVars.themeMaps[individualBoxToShow.attr('data-theme')][1]);

        boxContent.removeClass('dialog-box__content_w_md');
        if (individualBoxToShow.attr('data-variation') == 'search') {
            boxContent.addClass('dialog-box__content_w_md');
        }
        
        typeof contentAppended === 'function' && contentAppended();
        
        positionDialogBox();
        
        enhanceMouseFocusUpdate();
        
        boxWrap.addClass('dialog-box_active');
        
        boxWrap.data('lastFocus', $(document.activeElement));
        box.focus();
        $(window).scrollTop(scrollTop);

        siteHeader.attr('aria-hidden', true);
        siteContent.attr('aria-hidden', true);
        siteFooter.attr('aria-hidden', true);

        if (individualBoxToShow.attr('data-variation') == 'search') {
            individualBoxToShow.find('.search-bar__field').focus(); // immediately set focus to search field for search popup
        }
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

        siteHeader.attr('aria-hidden', true);
        siteContent.attr('aria-hidden', true);
        siteFooter.attr('aria-hidden', true);
    }
    
    function closeDialogBox() {
        var scrollTop = $(window).scrollTop();

        siteHeader.removeAttr('aria-hidden');
        siteContent.removeAttr('aria-hidden');
        siteFooter.removeAttr('aria-hidden');
        
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
    
    function createDialogBox(id, theme, variation) {
        theme = (typeof theme === 'string') ? theme : 'main'; // default to main theme if no theme is provided
        variation = (typeof variation === 'string') ? variation : 'default'; // set to defalt variation if no variation is provided
        
        var content = $('[data-dialog-box-content="' + id + '"]').removeAttr('data-dialog-box-content');
        var closeButton = $('<button type="button" class="dialog-box__close" />');
        var individualContainer = $('<div />').attr('data-dialog-box-id', id).css('display', 'none');
        
        closeButton.addClass('c_bg_' + wpVars.themeMaps[theme][5]).addClass('c_h_bg_' + wpVars.themeMaps[theme][4]);
        closeButton.append('<span class="screen-reader-text">Close</span>');
        closeButton.append('<svg viewBox="0 0 27.03 27.04" class="dialog-box__close-x"><polygon class="c_fill_' + wpVars.themeMaps[theme][0] + '" points="17.45 13.52 17.45 13.51 27.03 3.94 23.09 0 13.52 9.58 3.94 0 0 3.94 9.58 13.51 9.57 13.52 9.58 13.52 0 23.1 3.94 27.04 13.51 17.46 23.09 27.04 27.03 23.1 17.45 13.52 17.45 13.52"/></svg>');
        closeButton.click(closeDialogBox);
        closeButton.prependTo(content);

        if (variation == 'search') {
            closeButton.addClass('dialog-box__close_no-offset');
        }
        
        individualContainer.attr('data-theme', theme);
        individualContainer.attr('data-variation', variation);
        
        individualContainer.append(content);
        individualContainer.appendTo(box);
        individualBoxes = individualBoxes.add(individualContainer);
    }
    
    function createGalleryBox(id, slide, theme) {
        slide = (typeof slide === 'number') ? slide : 0; // default to first if no valid slide index is provided
        theme = (typeof theme === 'string') ? theme : 'main'; // default to main theme if no theme is provided
        
        var content = $('<div class="box-slider" />');
        var slider = $('<div class="box-slider__slider" />').appendTo(content);
        var closeButton = $('<button type="button" class="dialog-box__close" />');
        
        // build slide for each image in the source element
        $('[data-gallery="' + id + '"]').find('[data-gallery-box-image]').each(function() {
            var slide = $('<div class="box-slider__slide"></div>').appendTo(slider);
            var src = $(this).attr('data-gallery-box-image');
            var alt = $(this).attr('data-alt');
            
            $('<div class="box-slider__image" role="img" />').attr('aria-label', alt).css('background-image', 'url("' + src + '")').appendTo(slide);
        });
        
        closeButton.addClass('c_bg_' + wpVars.themeMaps[theme][5]).addClass('c_h_bg_' + wpVars.themeMaps[theme][4]);
        closeButton.append('<span class="screen-reader-text">Close</span>');
        closeButton.append('<svg viewBox="0 0 27.03 27.04" class="dialog-box__close-x"><polygon class="c_fill_' + wpVars.themeMaps[theme][0] + '" points="17.45 13.52 17.45 13.51 27.03 3.94 23.09 0 13.52 9.58 3.94 0 0 3.94 9.58 13.51 9.57 13.52 9.58 13.52 0 23.1 3.94 27.04 13.51 17.46 23.09 27.04 27.03 23.1 17.45 13.52 17.45 13.52"/></svg>');
        closeButton.click(closeDialogBox);
        closeButton.prependTo(content);
        
        openGalleryBox(content, function() {
            // initialize slick slider if there is more than one slide in the container
            if (slider.find('.box-slider__slide').length > 1) {
                var prevButton = $('<button type="button" class="box-slider__nav box-slider__nav_prev" />');
                var nextButton = $('<button type="button" class="box-slider__nav box-slider__nav_next" />');
                
                // update overlay color
                boxBg.removeClass().addClass('dialog-box__bg').addClass('c_bg_' + wpVars.themeMaps[theme][1]);
                
                // init slick
                slider.slick({
                    accessibility: false,
                    arrows: false,
                    initialSlide: slide,
                    speed: 400
                });
                
                // add previous button
                prevButton.addClass('c_bg_' + wpVars.themeMaps[theme][5]).addClass('c_h_bg_' + wpVars.themeMaps[theme][4]);
                prevButton.append('<span class="screen-reader-text">Previous Slide</span>');
                prevButton.append('<svg viewBox="0 0 17.45 27.04" class="box-slider__nav-arrow box-slider__nav-arrow_reverse"><polygon class="c_fill_' + wpVars.themeMaps[theme][0] + '" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/></svg>');
                prevButton.click(function() {
                    slider.slick('slickPrev');
                });
                prevButton.appendTo(content);
                
                // add next button
                nextButton.addClass('c_bg_' + wpVars.themeMaps[theme][5]).addClass('c_h_bg_' + wpVars.themeMaps[theme][4]);
                nextButton.append('<span class="screen-reader-text">Next Slide</span>');
                nextButton.append('<svg viewBox="0 0 17.45 27.04" class="box-slider__nav-arrow"><polygon class="c_fill_' + wpVars.themeMaps[theme][0] + '" points="3.94 0 0 3.94 9.58 13.52 0 23.1 3.94 27.04 17.45 13.52 3.94 0"/></svg>');
                nextButton.click(function() {
                    slider.slick('slickNext');
                });
                nextButton.appendTo(content);
            }
        });
    }
    
    function preloadGalleryBoxImages() {
        $('[data-gallery-box-image]').each(function() {
            $('<img />').attr('src', $(this).attr('data-gallery-box-image')).on('load', function() {
                $(this).remove();
            });
        });
    }
    
    
    $('.site').prepend(boxWrap);
    
    $(window).on('load', preloadGalleryBoxImages);
    
    $(window).resize(throttledUpdateDialogBoxPosition);
    $(document).on('gform_post_render gform_confirmation_loaded dialog_box_position', positionDialogBox);
    
    boxWrap.click(function(e) {
        if ($(e.target).hasClass('dialog-box') || $(e.target).hasClass('dialog-box__content') || $(e.target).hasClass('dialog-box__bg')) {
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
        createDialogBox($(this).attr('data-dialog-box-content'), $(this).attr('data-dialog-theme'), $(this).attr('data-dialog-variation'));
    });
    
    $('[data-dialog-box]').click(function() {
        openDialogBox($(this).attr('data-dialog-box'));
    });
    $('[data-gallery-box]').click(function() {
        createGalleryBox($(this).attr('data-gallery-box'), parseInt($(this).attr('data-start')), $(this).attr('data-box-theme'));
    });
}



/* Last Item in Flex Row */

function initLastItemFlexRow() {
    var itemSets = [];
    var resizeTimeout = null;
    
    
    function addLastItemClass(items, className) {
        var prevItem = false; // store the previous item in the loop (start as false, since the first item has no previous)
        
        // loop through each item in the set and determine which items are the last in their respective row
        items.each(function() {
            if (prevItem && prevItem.offset().top != $(this).offset().top) {
                prevItem.addClass(className); // if the current item is positioned lower than the previous, add class to the previous item (since it must be the last in its row)
            } else if (prevItem) {
                prevItem.removeClass(className); // remove class from any item that is not last
            }
            
            prevItem = $(this); // update the previous item
        }).last().addClass(className); // add class to final item in the set (since that will always be the last in its row)
    }
    
    function triggerLastItemUpdate() {
        // update each item set
        $.each(itemSets, function(i, set) {
            addLastItemClass(set.items, set.className);
        });
    }
    
    function addItemSet(items, className) {
        if (items.length > 1) {
            // add new item set to list
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
    $('.post-tile__info').each(function() {
        addItemSet($(this).find('.post-tile__info-item'), 'post-tile__info-item_last');
    });
    $('.post-intro__info').each(function() {
        addItemSet($(this).find('.post-intro__info-item'), 'post-intro__info-item_last');
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
                    breakpoint: 1400,
                    settings: {
                        centerPadding: '80px'
                    }
                },
                {
                    breakpoint: 1100,
                    settings: {
                        centerPadding: '70px'
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        centerPadding: '64px'
                    }
                },
                {
                    breakpoint: 850,
                    settings: {
                        centerPadding: '53px'
                    }
                },
                {
                    breakpoint: 750,
                    settings: {
                        centerPadding: '38px'
                    }
                },
                {
                    breakpoint: 650,
                    settings: {
                        centerPadding: '28px'
                    }
                },
                {
                    breakpoint: 550,
                    settings: {
                        centerPadding: '22px'
                    }
                },
                {
                    breakpoint: 450,
                    settings: {
                        centerPadding: '17px'
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
            if (slider.has(document.activeElement) && ($(document.activeElement).is('.image-slider__item') || $(document.activeElement).parents('.image-slider__item:not(.slick-active)').length > 0)) {
                setTimeout(function() {
                    slider.find('.image-slider__item.slick-active').focus();
                }, 10);
            }
        });
    });
}



/* Testimonial Slider */

function initTestimonialSlider() {
    $('.testimonials_slick').each(function() {
        var sliderWrap = $(this);
        var slider = sliderWrap.find('.testimonials__slider');
        var sliderButtons = sliderWrap.find('.testimonials__nav');
        
        
        // initialize slick
        slider.slick({
            arrows: false,
            speed: 600,
            touchThreshold: 4
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
            if ($(this).hasClass('testimonials__nav_prev')) {
                slider.slick('slickPrev');
            } else {
                slider.slick('slickNext');
            }
        });
        
        // if old slide is focused after change, move focus to current slide
        slider.on('afterChange', function() {
            if (slider.has(document.activeElement) && ($(document.activeElement).is('.testimonials__item') || $(document.activeElement).parents('.image-slider__item:not(.slick-active)').length > 0)) {
                setTimeout(function() {
                    slider.find('.testimonials__item.slick-active').focus();
                }, 10);
            }
        });
    });
}



/* AJAX Grid Load */

function initAjaxGridLoad() {
    function ajaxLoad(type, tags, special, page, passthrough) {
        var request = $.ajax(wpVars.ajaxURL, {
            method: 'POST',
            data: {
                action: 'kf_grid_load',
                type: type,
                tags: tags,
                special: special,
                page_num: page,
                passthrough_data: passthrough
            },
            dataType: 'html'
        });
        
        return request;
    }

    function ajaxLoadSearch(search, page, passthrough) {
        var request = $.ajax(wpVars.ajaxURL, {
            method: 'POST',
            data: {
                action: 'kf_search_load',
                search: search,
                page_num: page,
                passthrough_data: passthrough
            },
            dataType: 'html'
        });
        
        return request;
    }
    
    
    $('.tile-grid__wrap[data-type]').each(function() {
        var gridWrap = $(this);
        var grid = gridWrap.find('.tile-grid__grid');
        var gridNoneItem = grid.find('.tile-grid__item_none');
        var gridMoreItem = grid.find('.tile-grid__item_more');
        var gridMoreButton = gridMoreItem.find('.tile-grid__more-button');
        
        var type = gridWrap.attr('data-type');
        var tags = gridWrap.attr('data-tags');
        var special = gridWrap.attr('data-special');
        var passthrough = gridWrap.attr('data-passthrough');
        var search = gridWrap.attr('data-search');
        
        var currentPage = 1;
        
        
        function loadGridPage(page) {
            function addItemsToGrid(data) {
                var jData = $(data);
                
                var newItems = jData.filter('.tile-grid__item');
                var morePages = jData.filter('#more-pages');
                
                
                // add new items to the grid
                if (newItems.length > 0) {
                    gridNoneItem.addClass('tile-grid__item_hidden'); // hide no items message
                    
                    if (page == 1) {
                        grid.children('.tile-grid__item').not(gridNoneItem).not(gridMoreItem).remove(); // if loading the first page, remove any existing items
                    }
                    
                    newItems.appendTo(grid); // append new items
                    
                    enhanceMouseFocusUpdate();
                    
                    // move no items message and load more button to the end
                    gridNoneItem.appendTo(grid);
                    gridMoreItem.appendTo(grid);
                } else if (page == 1) {
                    gridNoneItem.removeClass('tile-grid__item_hidden'); // if this is the first page and there are no items, show the no items message
                }
                
                // if there are more pages, make sure the load more button is visible, otherwise hide it
                if (morePages.length > 0) {
                    gridMoreItem.removeClass('tile-grid__item_hidden');
                } else {
                    gridMoreItem.addClass('tile-grid__item_hidden');
                }
                
                currentPage = page; // update current page
                
                gridMoreButton.prop('disabled', false).removeClass('button_loading'); // re-enable load more button
            }


            gridMoreButton.prop('disabled', true).addClass('button_loading'); // disable load more button until loading is complete
            
            if (type == 'search') {
                ajaxLoadSearch(search, page, passthrough).done(addItemsToGrid);
            } else {
                ajaxLoad(type, tags, special, page, passthrough).done(addItemsToGrid);
            }
        }
        
        
        gridWrap.removeAttr('data-type').removeAttr('data-tags').removeAttr('data-special').removeAttr('data-passthrough').removeAttr('data-search'); // remove attributes that are no longer needed
        
        gridMoreButton.click(function() {
            loadGridPage(currentPage + 1);
        });
    });
}



/* Gravity Forms Multi-File Upload Enhancements */

function initGformEnhanceFileInput() {
    var rafSupported = (typeof window.requestAnimationFrame === 'function' && typeof window.cancelAnimationFrame === 'function');
    
    var uniqueID = 0;
    
    var fileInputs = $();
    var frameRequest = null;
    
    
    function requestFileInputFrame() {
        if (rafSupported) {
            window.cancelAnimationFrame(frameRequest);
            frameRequest = window.requestAnimationFrame(updateFileInputs);
        } else {
            clearTimeout(frameRequest);
            frameRequest = setTimeout(updateFileInputs, 64);
        }
    }
    
    function updateFileInputPreview(customPreview, sourcePreview) {
        var status = '';
        var progressSource = sourcePreview.find('b');
        var boxEl = customPreview.find('.kfgf-file-previews__box');
        var statusEl = customPreview.find('.kfgf-file-previews__status');
        var progressEl = customPreview.find('.kfgf-file-previews__progress');
        var cancelEl = customPreview.find('.kfgf-file-previews__cancel');
        
        // if this is a new preview, perform initial setup
        if (customPreview.hasClass('kfgf-file-previews__item_new')) {
            var name = '';
            
            // set name text
            if (sourcePreview.find('strong').length > 0) {
                name = sourcePreview.find('strong').text();
            } else {
                name = sourcePreview[0].childNodes[0].nodeValue.trim();
                name = name.substr(0, name.lastIndexOf('(') - 1);
            }
            customPreview.find('.kfgf-file-previews__name').text(name);
            customPreview.find('.kfgf-file-previews__box').attr('title', name);
            
            // add cancel event handler
            cancelEl.click(function() {
                sourcePreview.find('.gform_delete_file').trigger('click');
                sourcePreview.find('a').trigger('click');
                
                customPreview.parents('.kfgf-file-previews').find('.kfgf-file-previews__item_error').not('.kfgf-file-previews__item_proto').remove();
            });
            
            // add IDs and aria attributes	
            customPreview.find('.kfgf-file-previews__name').attr('id', 'kfgf-file-preview-name-' + uniqueID).attr('aria-describedby', 'kfgf-file-preview-status-' + uniqueID);	
            customPreview.find('.kfgf-file-previews__status').attr('id', 'kfgf-file-preview-status-' + uniqueID).attr('aria-labelledby', 'kfgf-file-preview-name-' + uniqueID + ' kfgf-file-preview-status-' + uniqueID);	
            customPreview.find('.kfgf-file-previews__cancel').attr('id', 'kfgf-file-preview-cancel-text-' + uniqueID).attr('aria-labelledby', 'kfgf-file-preview-cancel-text-' + uniqueID + ' kfgf-file-preview-name-' + uniqueID);	
            uniqueID++;
            
            customPreview.removeClass('kfgf-file-previews__item_new');
        }
        
        // get status based on the current configuration of the source
        if (sourcePreview.children().length == 0) {
            status = 'failed';
        } else if (progressSource.length == 0) {
            status = 'complete';
        } else if (sourcePreview.find('a').length == 0 && sourcePreview.find('img').length == 0 && sourcePreview.find('strong').length == 0) {
            status = 'cancelled';
        } else if (progressSource.text().trim() == '') {
            status = 'waiting';
        } else {
            status = 'loading';
        }
        
        // if the status changed since the last frame, update it
        if (customPreview.data('file-status') != status) {
            if (status == 'waiting') {
                statusEl.text('Waiting');
                statusEl.removeClass('screen-reader-text');
                
                progressEl.addClass('kfgf-file-previews__progress_hidden');
            } else if (status == 'loading') {
                statusEl.text('Uploading');
                statusEl.addClass('screen-reader-text');
                
                progressEl.removeClass('kfgf-file-previews__progress_hidden');
            } else if (status == 'complete') {
                statusEl.text('Uploaded');
                statusEl.removeClass('screen-reader-text');
                
                progressEl.addClass('kfgf-file-previews__progress_hidden');
            } else if (status == 'failed') {
                boxEl.addClass('kfgf-file-previews__box_error');
                
                statusEl.text('Failed');
                statusEl.removeClass('screen-reader-text');
                
                progressEl.addClass('kfgf-file-previews__progress_hidden');
                
                cancelEl.parent().remove();
            } else if (status == 'cancelled') {
                boxEl.addClass('kfgf-file-previews__box_error');
                
                statusEl.text('Cancelled');
                statusEl.removeClass('screen-reader-text');
                
                progressEl.addClass('kfgf-file-previews__progress_hidden');
                
                cancelEl.parent().remove();
            }
            
            customPreview.data('file-status', status);
        }
        
        // update progress bar
        if (status == 'loading') {
            progressEl.find('.kfgf-file-previews__progress-bar').css('width', progressSource.text());
        }
    }
    
    function updateFileInputs() {
        fileInputs.each(function() {
            var inputWrap = $(this);
            
            // if this file input is still in the DOM, update it (otherwise, remove it from the list)
            if (jQuery.contains(document, inputWrap[0])) {
                var inputContainer = inputWrap.find('.ginput_container_fileupload');
                var errors = inputWrap.find('[id^="gform_multifile_messages_"] li');
                var previews = inputWrap.find('[id^="gform_preview_"] .ginput_preview');
                var customPreviewWrap = inputContainer.find('.kfgf-file-previews');
                var customPreviews = customPreviewWrap.find('.kfgf-file-previews__item_preview').not('.kfgf-file-previews__item_proto');
                var protoWrap = customPreviewWrap.find('.kfgf-file-previews__proto');
                var protoError = protoWrap.find('.kfgf-file-previews__item_error');
                var protoPreview = protoWrap.find('.kfgf-file-previews__item_preview');
                
                // add new errors
                errors.each(function() {
                    var sourceError = $(this);
                    var customError = protoError.clone().removeClass('kfgf-file-previews__item_proto');
                    var prevError = customPreviewWrap.children('.kfgf-file-previews__item_error').last();
                    
                    customError.find('.kfgf-file-previews__error-text').text(sourceError.text()); // copy error text
                    
                    // insert the new error after the previous one (or at the beginning if there is no previous error)
                    if (prevError.length > 0) {
                        customError.insertAfter(prevError);
                    } else {
                        customError.prependTo(customPreviewWrap);
                    }
                    
                    sourceError.remove(); // remove source error from DOM
                });
                
                // update existing previews
                customPreviews.each(function() {
                    var customPreview = $(this);
                    var sourcePreview = customPreview.data('src-preview');
                    
                    if (sourcePreview && sourcePreview.length > 0 && jQuery.contains(document, sourcePreview[0])) {
                        previews = previews.not(sourcePreview); // review the source preview from the master list since it is accounted for
                        
                        updateFileInputPreview(customPreview, sourcePreview);
                    } else {
                        // if there's no source element in the DOM for this custom preview, remove it
                        customPreview.remove();
                    }
                });
                
                // add new previews
                previews.each(function() {
                    var sourcePreview = $(this);
                    var customPreview = protoPreview.clone().removeClass('kfgf-file-previews__item_proto');
                    
                    customPreview.data('src-preview', sourcePreview);
                    customPreview.appendTo(customPreviewWrap);
                    
                    enhanceMouseFocusUpdate();
                    
                    updateFileInputPreview(customPreview, sourcePreview);
                });
            } else {
                // remove this file input from the list
                fileInputs = fileInputs.not(inputWrap);
            }
        });
        
        // if there are still active inputs, request the next frame
        if (fileInputs.length > 0) {
            requestFileInputFrame();
        }
    }
    
    function setupFileInputs() {
        fileInputs = fileInputs.add($('.gfield .ginput_container_fileupload').parents('.gfield').not(fileInputs)); // add any new file inputs

        // start updating
        if (fileInputs.length > 0) {
            requestFileInputFrame();
        }
    }
    
    
    setupFileInputs();
    $(document).on('gform_post_render', setupFileInputs);
}



/* Gravity Forms List Enhancements */

function initGformEnhanceList() {
    function updateAddButtons(container) {
        container.find('.add_list_item').each(function() {
            var addButton = $(this);
            
            // update title and aria-label based on whether the button is disabled
            if (addButton.hasClass('gfield_icon_disabled')) {
                addButton.attr('title', 'Maximum number of rows reached');
                addButton.attr('aria-label', 'Can\'t add another row (Maximum number of rows reached)');
            } else {
                addButton.attr('title', '');
                addButton.attr('aria-label', 'Add another row');
            }
        });
    }
    
    
    if (typeof gform === 'object') {
        gform.addAction('gform_list_post_item_add', function (item, container) {
            updateAddButtons(container);

            enhanceMouseFocusUpdate();
        });
        gform.addAction('gform_list_post_item_delete', function (container) {
            updateAddButtons(container);
        });
    }
}



/* Gravity Forms Stripe Field Enhancements */

function initGformEnhanceStripe() {
    var rafSupported = (typeof window.requestAnimationFrame === 'function' && typeof window.cancelAnimationFrame === 'function');
    
    var stripeFields = $();
    var frameRequest = null;
    
    
    function requestStripeFieldFrame() {
        if (rafSupported) {
            window.cancelAnimationFrame(frameRequest);
            frameRequest = window.requestAnimationFrame(updateStripeFields);
        } else {
            clearTimeout(frameRequest);
            frameRequest = setTimeout(updateStripeFields, 64);
        }
    }
    
    function updateStripeFields() {
        stripeFields.each(function() {
            var inputWrap = $(this);
            
            // if this Stripe field is still in the DOM, update it (otherwise, remove it from the list)
            if (jQuery.contains(document, inputWrap[0])) {
                var stripeElement = inputWrap.find('.ginput_container_creditcard .StripeElement');
                var validationMessage = inputWrap.find('.ginput_container_creditcard .validation_message');
                var validationClasses = stripeElement.attr('data-validation-classes');
                
                // add text/color classes to validation message, and remove this Stripe field from the list
                if (stripeElement.length > 0 && validationMessage.length > 0) {
                    if (validationClasses) {
                        validationMessage.addClass(validationClasses);
                        stripeElement.removeAttr('data-validation-classes');
                    }
                    
                    stripeFields = stripeFields.not(inputWrap);
                }
            } else {
                // remove this Stripe field from the list
                stripeFields = stripeFields.not(inputWrap);
            }
        });
        
        // if there are still active Stripe fields, request the next frame
        if (stripeFields.length > 0) {
            requestStripeFieldFrame();
        }
    }
    
    function setupStripeFields() {
        stripeFields = stripeFields.add($('.gfield .ginput_container_creditcard').parents('.gfield').not(stripeFields)); // add any new Stripe fields
        
        // start updating
        if (stripeFields.length > 0) {
            requestStripeFieldFrame();
        }
    }
    
    
    setupStripeFields();
    $(document).on('gform_post_render', setupStripeFields);
}



/* Gravity Forms Submit Enhancements */

function initGformEnhanceSubmit() {
    var rafSupported = (typeof window.requestAnimationFrame === 'function' && typeof window.cancelAnimationFrame === 'function');
    
    var gravityForms = $('.gform_wrapper');
    var frameRequest = null;
    
    
    function requestGformSubmitFrame() {
        if (rafSupported) {
            window.cancelAnimationFrame(frameRequest);
            frameRequest = window.requestAnimationFrame(updateGformSubmit);
        } else {
            clearTimeout(frameRequest);
            frameRequest = setTimeout(updateGformSubmit, 64);
        }
    }
    
    function updateGformSubmit() {
        gravityForms.each(function() {
            var wrapper = $(this);
            
            if (wrapper.find('.gform_ajax_spinner').length > 0 && !wrapper.hasClass('loading')) {
                wrapper.addClass('loading');
            } else if (wrapper.find('.gform_ajax_spinner').length == 0 && wrapper.hasClass('loading')) {
                wrapper.removeClass('loading');
            }
        });
        
        // if there are still active forms, request the next frame
        if (gravityForms.length > 0) {
            requestGformSubmitFrame();
        }
    }
    
    
    // start updating
    if (gravityForms.length > 0) {
        requestGformSubmitFrame();
    }
    
    $(document).on('gform_confirmation_loaded', function(event, formID){
        gravityForms = gravityForms.not('#gform_wrapper_' + formID); // remove the form from the list once it has been submitted successfully
    });
}



/* Gravity Forms Misc Enhancements */

function initGformEnhanceMisc() {
    function telMaskFix() {
        // force cursor to beginning of phone input on focus if field is empty
        $('input[type="tel"]').each(function() {
            var input = $(this);
            
            if (!input.data('tel-mask-fix')) {
                input.data('tel-mask-fix', true);
                
                input.click(function() {
                    var inputEl = $(this)[0];

                    if (input.val() == '(___) ___-____') {
                        if (inputEl.createTextRange) {
                            var range = inputEl.createTextRange();

                            range.move('character', 0);
                            range.select();
                        } else {
                            if (inputEl.selectionStart) {
                                inputEl.focus();
                                inputEl.setSelectionRange(0, 0);
                            }
                        }
                    }
                });
            }
        });
    }
    
    // update datepicker options
    if (typeof gform === 'object') {
        gform.addFilter('gform_datepicker_options_pre_init', function(optionsObj, formId, fieldId) {
            // reset day/month names to default
            delete optionsObj['dayNamesMin'];
            delete optionsObj['monthNamesShort'];

            return optionsObj;
        });
    }
    
    telMaskFix();
    $(document).on('gform_post_render', telMaskFix);
}



/* Google Maps */

function initGoogleMaps() {
    $('.g-map').each(function() {
        var wrap = $(this);
        var canvas = wrap.find('.g-map__canvas');
        var mapData = wrap.find('.g-map__data');
        var markers = mapData.find('.g-map__marker');
        
        var centerLoc = markers.first().attr('data-loc').split(',');
        
        var latLng = {
            lat: parseFloat(centerLoc[0]),
            lng: parseFloat(centerLoc[1])
        };
        var zoom = parseInt(mapData.attr('data-zoom'));
        
        var map = new google.maps.Map(canvas[0], {
            center: latLng,
            zoom: zoom,
            disableDefaultUI: true,
            scrollwheel: false
        });
        
        var bounds = new google.maps.LatLngBounds();
        
        
        markers.each(function() {
            var markerEl = $(this);
            var markerLoc = markerEl.attr('data-loc').split(',');
            var markerLatLng = {
                lat: parseFloat(markerLoc[0]),
                lng: parseFloat(markerLoc[1])
            };
            
            var marker = new google.maps.Marker({
                map: map,
                title: markerEl.text(),
                position: markerLatLng
            });
            
            bounds.extend(marker.getPosition());
            
            if ($(this)[0].hasAttribute('data-link')) {
                var link = $(this).attr('data-link');
                
                marker.addListener('click', function(){
                    window.open(link);
                });
            }
        });
        
        if (mapData.attr('data-fit') == 'true' && markers.length > 1) {
            map.fitBounds(bounds);
        }
    });
}



/* Mobile Menu */

function initMobileMenu() {
    var body = $('body');

    var siteHeader = $('.site-header');
    var siteContent = $('.site-content');
    var siteFooter = $('.site-footer');

    var openButton = $('.hamburger-button');
    var menu = $('.mobile-menu');
    var menuMain = menu.find('.mobile-menu__main');
    var closeButton = menu.find('.mobile-menu__close');
    var menuFocusableContent = menu.find('input, select, textarea, button, object, a[href], iframe, [tabindex="0"]').not('[tabindex="-1"]');

    var isOpen = false;

    var openAnimationTimeout;


    function toggleMenu(open) {
        open = (typeof open === 'boolean') ? open : !isOpen; // default to opposite of current state if no state is specified
        
        if (open == isOpen) return; // exit if the menu is already in the requested state

        clearTimeout(openAnimationTimeout); // clear timeout

        // show/hide menu
        if (open) {
            body.addClass('no-scroll'); // disable scrolling
            menuMain.scrollTop(0); // ensure that menu is scrolled to top before opening

            menu.addClass('mobile-menu_open'); // add class

            // update aria-expanded
            openButton.attr('aria-expanded', true);
            closeButton.attr('aria-expanded', true);

            // allow screen readers and keyboard navigation to access content
            menu.removeAttr('aria-hidden');
            menuFocusableContent.attr('tabindex', '0');

            // switch focus from open button to close button
            if (openButton.is(document.activeElement)) {
                closeButton.focus();
            }

            // wait until animation is complete
            openAnimationTimeout = setTimeout(function() {
                body.addClass('no-scroll-fixed'); // add additional class to disable scrolling (for iOS devices, just the no-scroll class doesn't cut it)
                
                // prevent screen readers and keyboard navigation from accessing page content while menu is open
                siteHeader.addClass('site-header_hidden');
                siteContent.addClass('site-content_hidden');
                siteFooter.addClass('site-footer_hidden');
            }, 410);
        } else {
            // allow screen readers and keyboard navigation to access page content
            siteHeader.removeClass('site-header_hidden');
            siteContent.removeClass('site-content_hidden');
            siteFooter.removeClass('site-footer_hidden');

            // re-enable scrolling
            body.removeClass('no-scroll');
            body.removeClass('no-scroll-fixed');

            menu.removeClass('mobile-menu_open'); // remove class

            // update aria-expanded
            openButton.attr('aria-expanded', false);
            closeButton.attr('aria-expanded', false);

            // switch focus from close button to open button
            if (closeButton.is(document.activeElement)) {
                openButton.focus();
            }

            // prevent screen readers and keyboard navigation from accessing content during close animation
            menu.attr('aria-hidden', true);
            menuFocusableContent.attr('tabindex', '-1');
        }

         isOpen = open; // update stored state

         $(window).trigger('resize'); // trigger window resize (since scroll bar may be removed/added)
    }


    // open/close menu on button click
    openButton.click(function() {
        toggleMenu();
    });
    closeButton.click(function() {
        toggleMenu();
    });

    // close mobile menu if esc key is pressed
    $(document).keydown(function(e) {
        if (e.which === 27 && isOpen) {
            toggleMenu(false);
        }
    });
}



/* Smooth Scrolling */

function initSmoothScrolling() {
    $('html').addClass('smooth-scroll'); // enable smooth scrolling after page load (to prevent scrolling on initial page load)
}



/* General */

$(function() {
    initMasonryGrid();
    initFitVids();
    initImageSlider();
    initTestimonialSlider();
    initLastItemFlexRow();
    initEnhanceMouseFocus();
    initGformEnhanceFileInput();
    initGformEnhanceList();
    initGformEnhanceStripe();
    initGformEnhanceSubmit();
    initGformEnhanceMisc();
    initMobileMenu();
    initDialogBoxes();
    initAjaxGridLoad();
    initSmoothScrolling();
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

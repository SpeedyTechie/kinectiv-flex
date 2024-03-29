/*jshint browser:true */
/*!
* FitVids 1.2
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
*/

;(function( $ ){

  'use strict';

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null,
      ignore: null
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement("div");
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        'iframe[src*="player.vimeo.com"]',
        'iframe[src*="youtube.com"]',
        'iframe[src*="youtube-nocookie.com"]',
        'iframe[src*="kickstarter.com"][src*="video.html"]',
        'object',
        'embed'
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var ignoreList = '.fitvidsignore';

      if(settings.ignore) {
        ignoreList = ignoreList + ', ' + settings.ignore;
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
      $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

      $allVideos.each(function(){
        var $this = $(this);
        if($this.parents(ignoreList).length > 0) {
          return; // Disable FitVids on this video.
        }
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
        {
          $this.attr('height', 9);
          $this.attr('width', 16);
        }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('name')){
          var videoName = 'fitvid' + $.fn.fitVids._count;
          $this.attr('name', videoName);
          $.fn.fitVids._count++;
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };
  
  // Internal counter for unique video names.
  $.fn.fitVids._count = 0;
  
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );

/*
	jQuery flexImages v1.0.1
    Copyright (c) 2014 Simon Steinberger / Pixabay
    GitHub: https://github.com/Pixabay/jQuery-flexImages
	License: http://www.opensource.org/licenses/mit-license.php
*/

(function($){
    $.fn.flexImages = function(options){
        var o = $.extend({ container: '.item', object: 'img', rowHeight: 180, maxRows: 0, truncate: false }, options);
        return this.each(function(){
            var $this = $(this), $items = $(o.container, $this), items = [], i = $items.eq(0), t = new Date().getTime();
            o.margin = i.outerWidth(true) - i.innerWidth();
            $items.each(function(){
                var w = parseInt($(this).data('w')),
                    h = parseInt($(this).data('h')),
                    norm_w = w*(o.rowHeight/h), // normalized width
                    obj = $(this).find(o.object);
                items.push([$(this), w, h, norm_w, obj, obj.data('src')]);
            });
            makeGrid($this, items, o);
            $(window).off('resize.flexImages'+$this.data('flex-t'));
            $(window).on('resize.flexImages'+t, function(){ makeGrid($this, items, o); });
            $this.data('flex-t', t)
        });
    }

    function makeGrid(container, items, o, noresize){
        var x, new_w, ratio = 1, rows = 1, max_w = container.width(), row = [], row_width = 0, row_h = o.rowHeight;

        // define inside makeGrid to access variables in scope
        function _helper(lastRow){
            if (o.maxRows && rows > o.maxRows || o.truncate && lastRow) row[x][0].hide();
            else {
                if (row[x][5]) { row[x][4].attr('src', row[x][5]); row[x][5] = ''; }
                row[x][0].css({ width: new_w, height: row_h }).show();
            }
        }

        for (i=0; i<items.length; i++) {
            row.push(items[i]);
            row_width += items[i][3] + o.margin;
            if (row_width >= max_w) {
                ratio = max_w / row_width, row_h = Math.ceil(o.rowHeight*ratio), exact_w = 0, new_w;
                for (x=0; x<row.length; x++) {
                    new_w = Math.ceil(row[x][3]*ratio);
                    exact_w += new_w + o.margin;
                    if (exact_w > max_w) new_w -= exact_w - max_w + 1;
                    _helper();
                }
                // reset for next row
                row = [], row_width = 0;
                rows++;
            }
        }
        // layout last row - match height of last row to previous row
        for (x=0; x<row.length; x++) {
            new_w = Math.floor(row[x][3]*ratio), h = Math.floor(o.rowHeight*ratio);
            _helper(true);
        }

        // scroll bars added or removed during rendering new layout?
        if (!noresize && max_w != container.width()) makeGrid(container, items, o, true);
    }
}(jQuery));

/*
     _ _      _       _
 ___| (_) ___| | __  (_)___
/ __| | |/ __| |/ /  | / __|
\__ \ | | (__|   < _ | \__ \
|___/_|_|\___|_|\_(_)/ |___/
                   |__/

 Version: 1.8.0
  Author: Ken Wheeler
 Website: http://kenwheeler.github.io
    Docs: http://kenwheeler.github.io/slick
    Repo: http://github.com/kenwheeler/slick
  Issues: http://github.com/kenwheeler/slick/issues

 */
/* global window, document, define, jQuery, setInterval, clearInterval */
;(function(factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports !== 'undefined') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery);
    }

}(function($) {
    'use strict';
    var Slick = window.Slick || {};

    Slick = (function() {

        var instanceUid = 0;

        function Slick(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
                accessibility: true,
                adaptiveHeight: false,
                appendArrows: $(element),
                appendDots: $(element),
                arrows: true,
                asNavFor: null,
                prevArrow: '<button class="slick-prev" aria-label="Previous" type="button">Previous</button>',
                nextArrow: '<button class="slick-next" aria-label="Next" type="button">Next</button>',
                autoplay: false,
                autoplaySpeed: 3000,
                centerMode: false,
                centerPadding: '50px',
                cssEase: 'ease',
                customPaging: function(slider, i) {
                    return $('<button type="button" />').text(i + 1);
                },
                dots: false,
                dotsClass: 'slick-dots',
                draggable: true,
                easing: 'linear',
                edgeFriction: 0.35,
                fade: false,
                focusOnSelect: false,
                focusOnChange: false,
                infinite: true,
                initialSlide: 0,
                lazyLoad: 'ondemand',
                mobileFirst: false,
                pauseOnHover: true,
                pauseOnFocus: true,
                pauseOnDotsHover: false,
                respondTo: 'window',
                responsive: null,
                rows: 1,
                rtl: false,
                slide: '',
                slidesPerRow: 1,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 500,
                swipe: true,
                swipeToSlide: false,
                touchMove: true,
                touchThreshold: 5,
                useCSS: true,
                useTransform: true,
                variableWidth: false,
                vertical: false,
                verticalSwiping: false,
                waitForAnimate: true,
                zIndex: 1000
            };

            _.initials = {
                animating: false,
                dragging: false,
                autoPlayTimer: null,
                currentDirection: 0,
                currentLeft: null,
                currentSlide: 0,
                direction: 1,
                $dots: null,
                listWidth: null,
                listHeight: null,
                loadIndex: 0,
                $nextArrow: null,
                $prevArrow: null,
                scrolling: false,
                slideCount: null,
                slideWidth: null,
                $slideTrack: null,
                $slides: null,
                sliding: false,
                slideOffset: 0,
                swipeLeft: null,
                swiping: false,
                $list: null,
                touchObject: {},
                transformsEnabled: false,
                unslicked: false
            };

            $.extend(_, _.initials);

            _.activeBreakpoint = null;
            _.animType = null;
            _.animProp = null;
            _.breakpoints = [];
            _.breakpointSettings = [];
            _.cssTransitions = false;
            _.focussed = false;
            _.interrupted = false;
            _.hidden = 'hidden';
            _.paused = true;
            _.positionProp = null;
            _.respondTo = null;
            _.rowCount = 1;
            _.shouldClick = true;
            _.$slider = $(element);
            _.$slidesCache = null;
            _.transformType = null;
            _.transitionType = null;
            _.visibilityChange = 'visibilitychange';
            _.windowWidth = 0;
            _.windowTimer = null;

            dataSettings = $(element).data('slick') || {};

            _.options = $.extend({}, _.defaults, settings, dataSettings);

            _.currentSlide = _.options.initialSlide;

            _.originalSettings = _.options;

            if (typeof document.mozHidden !== 'undefined') {
                _.hidden = 'mozHidden';
                _.visibilityChange = 'mozvisibilitychange';
            } else if (typeof document.webkitHidden !== 'undefined') {
                _.hidden = 'webkitHidden';
                _.visibilityChange = 'webkitvisibilitychange';
            }

            _.autoPlay = $.proxy(_.autoPlay, _);
            _.autoPlayClear = $.proxy(_.autoPlayClear, _);
            _.autoPlayIterator = $.proxy(_.autoPlayIterator, _);
            _.changeSlide = $.proxy(_.changeSlide, _);
            _.clickHandler = $.proxy(_.clickHandler, _);
            _.selectHandler = $.proxy(_.selectHandler, _);
            _.setPosition = $.proxy(_.setPosition, _);
            _.swipeHandler = $.proxy(_.swipeHandler, _);
            _.dragHandler = $.proxy(_.dragHandler, _);
            _.keyHandler = $.proxy(_.keyHandler, _);

            _.instanceUid = instanceUid++;

            // A simple way to check for HTML strings
            // Strict HTML recognition (must start with <)
            // Extracted from jQuery v1.11 source
            _.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/;


            _.registerBreakpoints();
            _.init(true);

        }

        return Slick;

    }());

    Slick.prototype.activateADA = function() {
        var _ = this;

        _.$slideTrack.find('.slick-active').attr({
            'aria-hidden': 'false'
        }).find('a, input, button, select').attr({
            'tabindex': '0'
        });

    };

    Slick.prototype.addSlide = Slick.prototype.slickAdd = function(markup, index, addBefore) {

        var _ = this;

        if (typeof(index) === 'boolean') {
            addBefore = index;
            index = null;
        } else if (index < 0 || (index >= _.slideCount)) {
            return false;
        }

        _.unload();

        if (typeof(index) === 'number') {
            if (index === 0 && _.$slides.length === 0) {
                $(markup).appendTo(_.$slideTrack);
            } else if (addBefore) {
                $(markup).insertBefore(_.$slides.eq(index));
            } else {
                $(markup).insertAfter(_.$slides.eq(index));
            }
        } else {
            if (addBefore === true) {
                $(markup).prependTo(_.$slideTrack);
            } else {
                $(markup).appendTo(_.$slideTrack);
            }
        }

        _.$slides = _.$slideTrack.children(this.options.slide);

        _.$slideTrack.children(this.options.slide).detach();

        _.$slideTrack.append(_.$slides);

        _.$slides.each(function(index, element) {
            $(element).attr('data-slick-index', index);
        });

        _.$slidesCache = _.$slides;

        _.reinit();

    };

    Slick.prototype.animateHeight = function() {
        var _ = this;
        if (_.options.slidesToShow === 1 && _.options.adaptiveHeight === true && _.options.vertical === false) {
            var targetHeight = _.$slides.eq(_.currentSlide).outerHeight(true);
            _.$list.animate({
                height: targetHeight
            }, _.options.speed);
        }
    };

    Slick.prototype.animateSlide = function(targetLeft, callback) {

        var animProps = {},
            _ = this;

        _.animateHeight();

        if (_.options.rtl === true && _.options.vertical === false) {
            targetLeft = -targetLeft;
        }
        if (_.transformsEnabled === false) {
            if (_.options.vertical === false) {
                _.$slideTrack.animate({
                    left: targetLeft
                }, _.options.speed, _.options.easing, callback);
            } else {
                _.$slideTrack.animate({
                    top: targetLeft
                }, _.options.speed, _.options.easing, callback);
            }

        } else {

            if (_.cssTransitions === false) {
                if (_.options.rtl === true) {
                    _.currentLeft = -(_.currentLeft);
                }
                $({
                    animStart: _.currentLeft
                }).animate({
                    animStart: targetLeft
                }, {
                    duration: _.options.speed,
                    easing: _.options.easing,
                    step: function(now) {
                        now = Math.ceil(now);
                        if (_.options.vertical === false) {
                            animProps[_.animType] = 'translate(' +
                                now + 'px, 0px)';
                            _.$slideTrack.css(animProps);
                        } else {
                            animProps[_.animType] = 'translate(0px,' +
                                now + 'px)';
                            _.$slideTrack.css(animProps);
                        }
                    },
                    complete: function() {
                        if (callback) {
                            callback.call();
                        }
                    }
                });

            } else {

                _.applyTransition();
                targetLeft = Math.ceil(targetLeft);

                if (_.options.vertical === false) {
                    animProps[_.animType] = 'translate3d(' + targetLeft + 'px, 0px, 0px)';
                } else {
                    animProps[_.animType] = 'translate3d(0px,' + targetLeft + 'px, 0px)';
                }
                _.$slideTrack.css(animProps);

                if (callback) {
                    setTimeout(function() {

                        _.disableTransition();

                        callback.call();
                    }, _.options.speed);
                }

            }

        }

    };

    Slick.prototype.getNavTarget = function() {

        var _ = this,
            asNavFor = _.options.asNavFor;

        if ( asNavFor && asNavFor !== null ) {
            asNavFor = $(asNavFor).not(_.$slider);
        }

        return asNavFor;

    };

    Slick.prototype.asNavFor = function(index) {

        var _ = this,
            asNavFor = _.getNavTarget();

        if ( asNavFor !== null && typeof asNavFor === 'object' ) {
            asNavFor.each(function() {
                var target = $(this).slick('getSlick');
                if(!target.unslicked) {
                    target.slideHandler(index, true);
                }
            });
        }

    };

    Slick.prototype.applyTransition = function(slide) {

        var _ = this,
            transition = {};

        if (_.options.fade === false) {
            transition[_.transitionType] = _.transformType + ' ' + _.options.speed + 'ms ' + _.options.cssEase;
        } else {
            transition[_.transitionType] = 'opacity ' + _.options.speed + 'ms ' + _.options.cssEase;
        }

        if (_.options.fade === false) {
            _.$slideTrack.css(transition);
        } else {
            _.$slides.eq(slide).css(transition);
        }

    };

    Slick.prototype.autoPlay = function() {

        var _ = this;

        _.autoPlayClear();

        if ( _.slideCount > _.options.slidesToShow ) {
            _.autoPlayTimer = setInterval( _.autoPlayIterator, _.options.autoplaySpeed );
        }

    };

    Slick.prototype.autoPlayClear = function() {

        var _ = this;

        if (_.autoPlayTimer) {
            clearInterval(_.autoPlayTimer);
        }

    };

    Slick.prototype.autoPlayIterator = function() {

        var _ = this,
            slideTo = _.currentSlide + _.options.slidesToScroll;

        if ( !_.paused && !_.interrupted && !_.focussed ) {

            if ( _.options.infinite === false ) {

                if ( _.direction === 1 && ( _.currentSlide + 1 ) === ( _.slideCount - 1 )) {
                    _.direction = 0;
                }

                else if ( _.direction === 0 ) {

                    slideTo = _.currentSlide - _.options.slidesToScroll;

                    if ( _.currentSlide - 1 === 0 ) {
                        _.direction = 1;
                    }

                }

            }

            _.slideHandler( slideTo );

        }

    };

    Slick.prototype.buildArrows = function() {

        var _ = this;

        if (_.options.arrows === true ) {

            _.$prevArrow = $(_.options.prevArrow).addClass('slick-arrow');
            _.$nextArrow = $(_.options.nextArrow).addClass('slick-arrow');

            if( _.slideCount > _.options.slidesToShow ) {

                _.$prevArrow.removeClass('slick-hidden').removeAttr('aria-hidden tabindex');
                _.$nextArrow.removeClass('slick-hidden').removeAttr('aria-hidden tabindex');

                if (_.htmlExpr.test(_.options.prevArrow)) {
                    _.$prevArrow.prependTo(_.options.appendArrows);
                }

                if (_.htmlExpr.test(_.options.nextArrow)) {
                    _.$nextArrow.appendTo(_.options.appendArrows);
                }

                if (_.options.infinite !== true) {
                    _.$prevArrow
                        .addClass('slick-disabled')
                        .attr('aria-disabled', 'true');
                }

            } else {

                _.$prevArrow.add( _.$nextArrow )

                    .addClass('slick-hidden')
                    .attr({
                        'aria-disabled': 'true',
                        'tabindex': '-1'
                    });

            }

        }

    };

    Slick.prototype.buildDots = function() {

        var _ = this,
            i, dot;

        if (_.options.dots === true) {

            _.$slider.addClass('slick-dotted');

            dot = $('<ul />').addClass(_.options.dotsClass);

            for (i = 0; i <= _.getDotCount(); i += 1) {
                dot.append($('<li />').append(_.options.customPaging.call(this, _, i)));
            }

            _.$dots = dot.appendTo(_.options.appendDots);

            _.$dots.find('li').first().addClass('slick-active');

        }

    };

    Slick.prototype.buildOut = function() {

        var _ = this;

        _.$slides =
            _.$slider
                .children( _.options.slide + ':not(.slick-cloned)')
                .addClass('slick-slide');

        _.slideCount = _.$slides.length;

        _.$slides.each(function(index, element) {
            $(element)
                .attr('data-slick-index', index)
                .data('originalStyling', $(element).attr('style') || '');
        });

        _.$slider.addClass('slick-slider');

        _.$slideTrack = (_.slideCount === 0) ?
            $('<div class="slick-track"/>').appendTo(_.$slider) :
            _.$slides.wrapAll('<div class="slick-track"/>').parent();

        _.$list = _.$slideTrack.wrap(
            '<div class="slick-list"/>').parent();
        _.$slideTrack.css('opacity', 0);

        if (_.options.centerMode === true || _.options.swipeToSlide === true) {
            _.options.slidesToScroll = 1;
        }

        $('img[data-lazy]', _.$slider).not('[src]').addClass('slick-loading');

        _.setupInfinite();

        _.buildArrows();

        _.buildDots();

        _.updateDots();


        _.setSlideClasses(typeof _.currentSlide === 'number' ? _.currentSlide : 0);

        if (_.options.draggable === true) {
            _.$list.addClass('draggable');
        }

    };

    Slick.prototype.buildRows = function() {

        var _ = this, a, b, c, newSlides, numOfSlides, originalSlides,slidesPerSection;

        newSlides = document.createDocumentFragment();
        originalSlides = _.$slider.children();

        if(_.options.rows > 1) {

            slidesPerSection = _.options.slidesPerRow * _.options.rows;
            numOfSlides = Math.ceil(
                originalSlides.length / slidesPerSection
            );

            for(a = 0; a < numOfSlides; a++){
                var slide = document.createElement('div');
                for(b = 0; b < _.options.rows; b++) {
                    var row = document.createElement('div');
                    for(c = 0; c < _.options.slidesPerRow; c++) {
                        var target = (a * slidesPerSection + ((b * _.options.slidesPerRow) + c));
                        if (originalSlides.get(target)) {
                            row.appendChild(originalSlides.get(target));
                        }
                    }
                    slide.appendChild(row);
                }
                newSlides.appendChild(slide);
            }

            _.$slider.empty().append(newSlides);
            _.$slider.children().children().children()
                .css({
                    'width':(100 / _.options.slidesPerRow) + '%',
                    'display': 'inline-block'
                });

        }

    };

    Slick.prototype.checkResponsive = function(initial, forceUpdate) {

        var _ = this,
            breakpoint, targetBreakpoint, respondToWidth, triggerBreakpoint = false;
        var sliderWidth = _.$slider.width();
        var windowWidth = window.innerWidth || $(window).width();

        if (_.respondTo === 'window') {
            respondToWidth = windowWidth;
        } else if (_.respondTo === 'slider') {
            respondToWidth = sliderWidth;
        } else if (_.respondTo === 'min') {
            respondToWidth = Math.min(windowWidth, sliderWidth);
        }

        if ( _.options.responsive &&
            _.options.responsive.length &&
            _.options.responsive !== null) {

            targetBreakpoint = null;

            for (breakpoint in _.breakpoints) {
                if (_.breakpoints.hasOwnProperty(breakpoint)) {
                    if (_.originalSettings.mobileFirst === false) {
                        if (respondToWidth < _.breakpoints[breakpoint]) {
                            targetBreakpoint = _.breakpoints[breakpoint];
                        }
                    } else {
                        if (respondToWidth > _.breakpoints[breakpoint]) {
                            targetBreakpoint = _.breakpoints[breakpoint];
                        }
                    }
                }
            }

            if (targetBreakpoint !== null) {
                if (_.activeBreakpoint !== null) {
                    if (targetBreakpoint !== _.activeBreakpoint || forceUpdate) {
                        _.activeBreakpoint =
                            targetBreakpoint;
                        if (_.breakpointSettings[targetBreakpoint] === 'unslick') {
                            _.unslick(targetBreakpoint);
                        } else {
                            _.options = $.extend({}, _.originalSettings,
                                _.breakpointSettings[
                                    targetBreakpoint]);
                            if (initial === true) {
                                _.currentSlide = _.options.initialSlide;
                            }
                            _.refresh(initial);
                        }
                        triggerBreakpoint = targetBreakpoint;
                    }
                } else {
                    _.activeBreakpoint = targetBreakpoint;
                    if (_.breakpointSettings[targetBreakpoint] === 'unslick') {
                        _.unslick(targetBreakpoint);
                    } else {
                        _.options = $.extend({}, _.originalSettings,
                            _.breakpointSettings[
                                targetBreakpoint]);
                        if (initial === true) {
                            _.currentSlide = _.options.initialSlide;
                        }
                        _.refresh(initial);
                    }
                    triggerBreakpoint = targetBreakpoint;
                }
            } else {
                if (_.activeBreakpoint !== null) {
                    _.activeBreakpoint = null;
                    _.options = _.originalSettings;
                    if (initial === true) {
                        _.currentSlide = _.options.initialSlide;
                    }
                    _.refresh(initial);
                    triggerBreakpoint = targetBreakpoint;
                }
            }

            // only trigger breakpoints during an actual break. not on initialize.
            if( !initial && triggerBreakpoint !== false ) {
                _.$slider.trigger('breakpoint', [_, triggerBreakpoint]);
            }
        }

    };

    Slick.prototype.changeSlide = function(event, dontAnimate) {

        var _ = this,
            $target = $(event.currentTarget),
            indexOffset, slideOffset, unevenOffset;

        // If target is a link, prevent default action.
        if($target.is('a')) {
            event.preventDefault();
        }

        // If target is not the <li> element (ie: a child), find the <li>.
        if(!$target.is('li')) {
            $target = $target.closest('li');
        }

        unevenOffset = (_.slideCount % _.options.slidesToScroll !== 0);
        indexOffset = unevenOffset ? 0 : (_.slideCount - _.currentSlide) % _.options.slidesToScroll;

        switch (event.data.message) {

            case 'previous':
                slideOffset = indexOffset === 0 ? _.options.slidesToScroll : _.options.slidesToShow - indexOffset;
                if (_.slideCount > _.options.slidesToShow) {
                    _.slideHandler(_.currentSlide - slideOffset, false, dontAnimate);
                }
                break;

            case 'next':
                slideOffset = indexOffset === 0 ? _.options.slidesToScroll : indexOffset;
                if (_.slideCount > _.options.slidesToShow) {
                    _.slideHandler(_.currentSlide + slideOffset, false, dontAnimate);
                }
                break;

            case 'index':
                var index = event.data.index === 0 ? 0 :
                    event.data.index || $target.index() * _.options.slidesToScroll;

                _.slideHandler(_.checkNavigable(index), false, dontAnimate);
                $target.children().trigger('focus');
                break;

            default:
                return;
        }

    };

    Slick.prototype.checkNavigable = function(index) {

        var _ = this,
            navigables, prevNavigable;

        navigables = _.getNavigableIndexes();
        prevNavigable = 0;
        if (index > navigables[navigables.length - 1]) {
            index = navigables[navigables.length - 1];
        } else {
            for (var n in navigables) {
                if (index < navigables[n]) {
                    index = prevNavigable;
                    break;
                }
                prevNavigable = navigables[n];
            }
        }

        return index;
    };

    Slick.prototype.cleanUpEvents = function() {

        var _ = this;

        if (_.options.dots && _.$dots !== null) {

            $('li', _.$dots)
                .off('click.slick', _.changeSlide)
                .off('mouseenter.slick', $.proxy(_.interrupt, _, true))
                .off('mouseleave.slick', $.proxy(_.interrupt, _, false));

            if (_.options.accessibility === true) {
                _.$dots.off('keydown.slick', _.keyHandler);
            }
        }

        _.$slider.off('focus.slick blur.slick');

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {
            _.$prevArrow && _.$prevArrow.off('click.slick', _.changeSlide);
            _.$nextArrow && _.$nextArrow.off('click.slick', _.changeSlide);

            if (_.options.accessibility === true) {
                _.$prevArrow && _.$prevArrow.off('keydown.slick', _.keyHandler);
                _.$nextArrow && _.$nextArrow.off('keydown.slick', _.keyHandler);
            }
        }

        _.$list.off('touchstart.slick mousedown.slick', _.swipeHandler);
        _.$list.off('touchmove.slick mousemove.slick', _.swipeHandler);
        _.$list.off('touchend.slick mouseup.slick', _.swipeHandler);
        _.$list.off('touchcancel.slick mouseleave.slick', _.swipeHandler);

        _.$list.off('click.slick', _.clickHandler);

        $(document).off(_.visibilityChange, _.visibility);

        _.cleanUpSlideEvents();

        if (_.options.accessibility === true) {
            _.$list.off('keydown.slick', _.keyHandler);
        }

        if (_.options.focusOnSelect === true) {
            $(_.$slideTrack).children().off('click.slick', _.selectHandler);
        }

        $(window).off('orientationchange.slick.slick-' + _.instanceUid, _.orientationChange);

        $(window).off('resize.slick.slick-' + _.instanceUid, _.resize);

        $('[draggable!=true]', _.$slideTrack).off('dragstart', _.preventDefault);

        $(window).off('load.slick.slick-' + _.instanceUid, _.setPosition);

    };

    Slick.prototype.cleanUpSlideEvents = function() {

        var _ = this;

        _.$list.off('mouseenter.slick', $.proxy(_.interrupt, _, true));
        _.$list.off('mouseleave.slick', $.proxy(_.interrupt, _, false));

    };

    Slick.prototype.cleanUpRows = function() {

        var _ = this, originalSlides;

        if(_.options.rows > 1) {
            originalSlides = _.$slides.children().children();
            originalSlides.removeAttr('style');
            _.$slider.empty().append(originalSlides);
        }

    };

    Slick.prototype.clickHandler = function(event) {

        var _ = this;

        if (_.shouldClick === false) {
            event.stopImmediatePropagation();
            event.stopPropagation();
            event.preventDefault();
        }

    };

    Slick.prototype.destroy = function(refresh) {

        var _ = this;

        _.autoPlayClear();

        _.touchObject = {};

        _.cleanUpEvents();

        $('.slick-cloned', _.$slider).detach();

        if (_.$dots) {
            _.$dots.remove();
        }

        if ( _.$prevArrow && _.$prevArrow.length ) {

            _.$prevArrow
                .removeClass('slick-disabled slick-arrow slick-hidden')
                .removeAttr('aria-hidden aria-disabled tabindex')
                .css('display','');

            if ( _.htmlExpr.test( _.options.prevArrow )) {
                _.$prevArrow.remove();
            }
        }

        if ( _.$nextArrow && _.$nextArrow.length ) {

            _.$nextArrow
                .removeClass('slick-disabled slick-arrow slick-hidden')
                .removeAttr('aria-hidden aria-disabled tabindex')
                .css('display','');

            if ( _.htmlExpr.test( _.options.nextArrow )) {
                _.$nextArrow.remove();
            }
        }


        if (_.$slides) {

            _.$slides
                .removeClass('slick-slide slick-active slick-center slick-visible slick-current')
                .removeAttr('aria-hidden')
                .removeAttr('data-slick-index')
                .each(function(){
                    $(this).attr('style', $(this).data('originalStyling'));
                });

            _.$slideTrack.children(this.options.slide).detach();

            _.$slideTrack.detach();

            _.$list.detach();

            _.$slider.append(_.$slides);
        }

        _.cleanUpRows();

        _.$slider.removeClass('slick-slider');
        _.$slider.removeClass('slick-initialized');
        _.$slider.removeClass('slick-dotted');

        _.unslicked = true;

        if(!refresh) {
            _.$slider.trigger('destroy', [_]);
        }

    };

    Slick.prototype.disableTransition = function(slide) {

        var _ = this,
            transition = {};

        transition[_.transitionType] = '';

        if (_.options.fade === false) {
            _.$slideTrack.css(transition);
        } else {
            _.$slides.eq(slide).css(transition);
        }

    };

    Slick.prototype.fadeSlide = function(slideIndex, callback) {

        var _ = this;

        if (_.cssTransitions === false) {

            _.$slides.eq(slideIndex).css({
                zIndex: _.options.zIndex
            });

            _.$slides.eq(slideIndex).animate({
                opacity: 1
            }, _.options.speed, _.options.easing, callback);

        } else {

            _.applyTransition(slideIndex);

            _.$slides.eq(slideIndex).css({
                opacity: 1,
                zIndex: _.options.zIndex
            });

            if (callback) {
                setTimeout(function() {

                    _.disableTransition(slideIndex);

                    callback.call();
                }, _.options.speed);
            }

        }

    };

    Slick.prototype.fadeSlideOut = function(slideIndex) {

        var _ = this;

        if (_.cssTransitions === false) {

            _.$slides.eq(slideIndex).animate({
                opacity: 0,
                zIndex: _.options.zIndex - 2
            }, _.options.speed, _.options.easing);

        } else {

            _.applyTransition(slideIndex);

            _.$slides.eq(slideIndex).css({
                opacity: 0,
                zIndex: _.options.zIndex - 2
            });

        }

    };

    Slick.prototype.filterSlides = Slick.prototype.slickFilter = function(filter) {

        var _ = this;

        if (filter !== null) {

            _.$slidesCache = _.$slides;

            _.unload();

            _.$slideTrack.children(this.options.slide).detach();

            _.$slidesCache.filter(filter).appendTo(_.$slideTrack);

            _.reinit();

        }

    };

    Slick.prototype.focusHandler = function() {

        var _ = this;

        _.$slider
            .off('focus.slick blur.slick')
            .on('focus.slick blur.slick', '*', function(event) {

            event.stopImmediatePropagation();
            var $sf = $(this);

            setTimeout(function() {

                if( _.options.pauseOnFocus ) {
                    _.focussed = $sf.is(':focus');
                    _.autoPlay();
                }

            }, 0);

        });
    };

    Slick.prototype.getCurrent = Slick.prototype.slickCurrentSlide = function() {

        var _ = this;
        return _.currentSlide;

    };

    Slick.prototype.getDotCount = function() {

        var _ = this;

        var breakPoint = 0;
        var counter = 0;
        var pagerQty = 0;

        if (_.options.infinite === true) {
            if (_.slideCount <= _.options.slidesToShow) {
                 ++pagerQty;
            } else {
                while (breakPoint < _.slideCount) {
                    ++pagerQty;
                    breakPoint = counter + _.options.slidesToScroll;
                    counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
                }
            }
        } else if (_.options.centerMode === true) {
            pagerQty = _.slideCount;
        } else if(!_.options.asNavFor) {
            pagerQty = 1 + Math.ceil((_.slideCount - _.options.slidesToShow) / _.options.slidesToScroll);
        }else {
            while (breakPoint < _.slideCount) {
                ++pagerQty;
                breakPoint = counter + _.options.slidesToScroll;
                counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
            }
        }

        return pagerQty - 1;

    };

    Slick.prototype.getLeft = function(slideIndex) {

        var _ = this,
            targetLeft,
            verticalHeight,
            verticalOffset = 0,
            targetSlide,
            coef;

        _.slideOffset = 0;
        verticalHeight = _.$slides.first().outerHeight(true);

        if (_.options.infinite === true) {
            if (_.slideCount > _.options.slidesToShow) {
                _.slideOffset = (_.slideWidth * _.options.slidesToShow) * -1;
                coef = -1

                if (_.options.vertical === true && _.options.centerMode === true) {
                    if (_.options.slidesToShow === 2) {
                        coef = -1.5;
                    } else if (_.options.slidesToShow === 1) {
                        coef = -2
                    }
                }
                verticalOffset = (verticalHeight * _.options.slidesToShow) * coef;
            }
            if (_.slideCount % _.options.slidesToScroll !== 0) {
                if (slideIndex + _.options.slidesToScroll > _.slideCount && _.slideCount > _.options.slidesToShow) {
                    if (slideIndex > _.slideCount) {
                        _.slideOffset = ((_.options.slidesToShow - (slideIndex - _.slideCount)) * _.slideWidth) * -1;
                        verticalOffset = ((_.options.slidesToShow - (slideIndex - _.slideCount)) * verticalHeight) * -1;
                    } else {
                        _.slideOffset = ((_.slideCount % _.options.slidesToScroll) * _.slideWidth) * -1;
                        verticalOffset = ((_.slideCount % _.options.slidesToScroll) * verticalHeight) * -1;
                    }
                }
            }
        } else {
            if (slideIndex + _.options.slidesToShow > _.slideCount) {
                _.slideOffset = ((slideIndex + _.options.slidesToShow) - _.slideCount) * _.slideWidth;
                verticalOffset = ((slideIndex + _.options.slidesToShow) - _.slideCount) * verticalHeight;
            }
        }

        if (_.slideCount <= _.options.slidesToShow) {
            _.slideOffset = 0;
            verticalOffset = 0;
        }

        if (_.options.centerMode === true && _.slideCount <= _.options.slidesToShow) {
            _.slideOffset = ((_.slideWidth * Math.floor(_.options.slidesToShow)) / 2) - ((_.slideWidth * _.slideCount) / 2);
        } else if (_.options.centerMode === true && _.options.infinite === true) {
            _.slideOffset += _.slideWidth * Math.floor(_.options.slidesToShow / 2) - _.slideWidth;
        } else if (_.options.centerMode === true) {
            _.slideOffset = 0;
            _.slideOffset += _.slideWidth * Math.floor(_.options.slidesToShow / 2);
        }

        if (_.options.vertical === false) {
            targetLeft = ((slideIndex * _.slideWidth) * -1) + _.slideOffset;
        } else {
            targetLeft = ((slideIndex * verticalHeight) * -1) + verticalOffset;
        }

        if (_.options.variableWidth === true) {

            if (_.slideCount <= _.options.slidesToShow || _.options.infinite === false) {
                targetSlide = _.$slideTrack.children('.slick-slide').eq(slideIndex);
            } else {
                targetSlide = _.$slideTrack.children('.slick-slide').eq(slideIndex + _.options.slidesToShow);
            }

            if (_.options.rtl === true) {
                if (targetSlide[0]) {
                    targetLeft = (_.$slideTrack.width() - targetSlide[0].offsetLeft - targetSlide.width()) * -1;
                } else {
                    targetLeft =  0;
                }
            } else {
                targetLeft = targetSlide[0] ? targetSlide[0].offsetLeft * -1 : 0;
            }

            if (_.options.centerMode === true) {
                if (_.slideCount <= _.options.slidesToShow || _.options.infinite === false) {
                    targetSlide = _.$slideTrack.children('.slick-slide').eq(slideIndex);
                } else {
                    targetSlide = _.$slideTrack.children('.slick-slide').eq(slideIndex + _.options.slidesToShow + 1);
                }

                if (_.options.rtl === true) {
                    if (targetSlide[0]) {
                        targetLeft = (_.$slideTrack.width() - targetSlide[0].offsetLeft - targetSlide.width()) * -1;
                    } else {
                        targetLeft =  0;
                    }
                } else {
                    targetLeft = targetSlide[0] ? targetSlide[0].offsetLeft * -1 : 0;
                }

                targetLeft += (_.$list.width() - targetSlide.outerWidth()) / 2;
            }
        }

        return targetLeft;

    };

    Slick.prototype.getOption = Slick.prototype.slickGetOption = function(option) {

        var _ = this;

        return _.options[option];

    };

    Slick.prototype.getNavigableIndexes = function() {

        var _ = this,
            breakPoint = 0,
            counter = 0,
            indexes = [],
            max;

        if (_.options.infinite === false) {
            max = _.slideCount;
        } else {
            breakPoint = _.options.slidesToScroll * -1;
            counter = _.options.slidesToScroll * -1;
            max = _.slideCount * 2;
        }

        while (breakPoint < max) {
            indexes.push(breakPoint);
            breakPoint = counter + _.options.slidesToScroll;
            counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
        }

        return indexes;

    };

    Slick.prototype.getSlick = function() {

        return this;

    };

    Slick.prototype.getSlideCount = function() {

        var _ = this,
            slidesTraversed, swipedSlide, centerOffset;

        centerOffset = _.options.centerMode === true ? _.slideWidth * Math.floor(_.options.slidesToShow / 2) : 0;

        if (_.options.swipeToSlide === true) {
            _.$slideTrack.find('.slick-slide').each(function(index, slide) {
                if (slide.offsetLeft - centerOffset + ($(slide).outerWidth() / 2) > (_.swipeLeft * -1)) {
                    swipedSlide = slide;
                    return false;
                }
            });

            slidesTraversed = Math.abs($(swipedSlide).attr('data-slick-index') - _.currentSlide) || 1;

            return slidesTraversed;

        } else {
            return _.options.slidesToScroll;
        }

    };

    Slick.prototype.goTo = Slick.prototype.slickGoTo = function(slide, dontAnimate) {

        var _ = this;

        _.changeSlide({
            data: {
                message: 'index',
                index: parseInt(slide)
            }
        }, dontAnimate);

    };

    Slick.prototype.init = function(creation) {

        var _ = this;

        if (!$(_.$slider).hasClass('slick-initialized')) {

            $(_.$slider).addClass('slick-initialized');

            _.buildRows();
            _.buildOut();
            _.setProps();
            _.startLoad();
            _.loadSlider();
            _.initializeEvents();
            _.updateArrows();
            _.updateDots();
            _.checkResponsive(true);
            _.focusHandler();

        }

        if (creation) {
            _.$slider.trigger('init', [_]);
        }

        if (_.options.accessibility === true) {
            _.initADA();
        }

        if ( _.options.autoplay ) {

            _.paused = false;
            _.autoPlay();

        }

    };

    Slick.prototype.initADA = function() {
        var _ = this,
                numDotGroups = Math.ceil(_.slideCount / _.options.slidesToShow),
                tabControlIndexes = _.getNavigableIndexes().filter(function(val) {
                    return (val >= 0) && (val < _.slideCount);
                });

        _.$slides.add(_.$slideTrack.find('.slick-cloned')).attr({
            'aria-hidden': 'true',
            'tabindex': '-1'
        }).find('a, input, button, select').attr({
            'tabindex': '-1'
        });

        if (_.$dots !== null) {
            _.$slides.not(_.$slideTrack.find('.slick-cloned')).each(function(i) {
                var slideControlIndex = tabControlIndexes.indexOf(i);

                $(this).attr({
                    'role': 'tabpanel',
                    'id': 'slick-slide' + _.instanceUid + i,
                    'tabindex': -1
                });

                if (slideControlIndex !== -1) {
                    $(this).attr({
                        'aria-describedby': 'slick-slide-control' + _.instanceUid + slideControlIndex
                    });
                }
            });

            _.$dots.attr('role', 'tablist').find('li').each(function(i) {
                var mappedSlideIndex = tabControlIndexes[i];

                $(this).attr({
                    'role': 'presentation'
                });

                $(this).find('button').first().attr({
                    'role': 'tab',
                    'id': 'slick-slide-control' + _.instanceUid + i,
                    'aria-controls': 'slick-slide' + _.instanceUid + mappedSlideIndex,
                    'aria-label': (i + 1) + ' of ' + numDotGroups,
                    'aria-selected': null,
                    'tabindex': '-1'
                });

            }).eq(_.currentSlide).find('button').attr({
                'aria-selected': 'true',
                'tabindex': '0'
            }).end();
        }

        for (var i=_.currentSlide, max=i+_.options.slidesToShow; i < max; i++) {
            _.$slides.eq(i).attr('tabindex', 0);
        }

        _.activateADA();

    };

    Slick.prototype.initArrowEvents = function() {

        var _ = this;

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {
            _.$prevArrow
               .off('click.slick')
               .on('click.slick', {
                    message: 'previous'
               }, _.changeSlide);
            _.$nextArrow
               .off('click.slick')
               .on('click.slick', {
                    message: 'next'
               }, _.changeSlide);

            if (_.options.accessibility === true) {
                _.$prevArrow.on('keydown.slick', _.keyHandler);
                _.$nextArrow.on('keydown.slick', _.keyHandler);
            }
        }

    };

    Slick.prototype.initDotEvents = function() {

        var _ = this;

        if (_.options.dots === true) {
            $('li', _.$dots).on('click.slick', {
                message: 'index'
            }, _.changeSlide);

            if (_.options.accessibility === true) {
                _.$dots.on('keydown.slick', _.keyHandler);
            }
        }

        if ( _.options.dots === true && _.options.pauseOnDotsHover === true ) {

            $('li', _.$dots)
                .on('mouseenter.slick', $.proxy(_.interrupt, _, true))
                .on('mouseleave.slick', $.proxy(_.interrupt, _, false));

        }

    };

    Slick.prototype.initSlideEvents = function() {

        var _ = this;

        if ( _.options.pauseOnHover ) {

            _.$list.on('mouseenter.slick', $.proxy(_.interrupt, _, true));
            _.$list.on('mouseleave.slick', $.proxy(_.interrupt, _, false));

        }

    };

    Slick.prototype.initializeEvents = function() {

        var _ = this;

        _.initArrowEvents();

        _.initDotEvents();
        _.initSlideEvents();

        _.$list.on('touchstart.slick mousedown.slick', {
            action: 'start'
        }, _.swipeHandler);
        _.$list.on('touchmove.slick mousemove.slick', {
            action: 'move'
        }, _.swipeHandler);
        _.$list.on('touchend.slick mouseup.slick', {
            action: 'end'
        }, _.swipeHandler);
        _.$list.on('touchcancel.slick mouseleave.slick', {
            action: 'end'
        }, _.swipeHandler);

        _.$list.on('click.slick', _.clickHandler);

        $(document).on(_.visibilityChange, $.proxy(_.visibility, _));

        if (_.options.accessibility === true) {
            _.$list.on('keydown.slick', _.keyHandler);
        }

        if (_.options.focusOnSelect === true) {
            $(_.$slideTrack).children().on('click.slick', _.selectHandler);
        }

        $(window).on('orientationchange.slick.slick-' + _.instanceUid, $.proxy(_.orientationChange, _));

        $(window).on('resize.slick.slick-' + _.instanceUid, $.proxy(_.resize, _));

        $('[draggable!=true]', _.$slideTrack).on('dragstart', _.preventDefault);

        $(window).on('load.slick.slick-' + _.instanceUid, _.setPosition);
        $(_.setPosition);

    };

    Slick.prototype.initUI = function() {

        var _ = this;

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {

            _.$prevArrow.show();
            _.$nextArrow.show();

        }

        if (_.options.dots === true && _.slideCount > _.options.slidesToShow) {

            _.$dots.show();

        }

    };

    Slick.prototype.keyHandler = function(event) {

        var _ = this;
         //Dont slide if the cursor is inside the form fields and arrow keys are pressed
        if(!event.target.tagName.match('TEXTAREA|INPUT|SELECT')) {
            if (event.keyCode === 37 && _.options.accessibility === true) {
                _.changeSlide({
                    data: {
                        message: _.options.rtl === true ? 'next' :  'previous'
                    }
                });
            } else if (event.keyCode === 39 && _.options.accessibility === true) {
                _.changeSlide({
                    data: {
                        message: _.options.rtl === true ? 'previous' : 'next'
                    }
                });
            }
        }

    };

    Slick.prototype.lazyLoad = function() {

        var _ = this,
            loadRange, cloneRange, rangeStart, rangeEnd;

        function loadImages(imagesScope) {

            $('img[data-lazy]', imagesScope).each(function() {

                var image = $(this),
                    imageSource = $(this).attr('data-lazy'),
                    imageSrcSet = $(this).attr('data-srcset'),
                    imageSizes  = $(this).attr('data-sizes') || _.$slider.attr('data-sizes'),
                    imageToLoad = document.createElement('img');

                imageToLoad.onload = function() {

                    image
                        .animate({ opacity: 0 }, 100, function() {

                            if (imageSrcSet) {
                                image
                                    .attr('srcset', imageSrcSet );

                                if (imageSizes) {
                                    image
                                        .attr('sizes', imageSizes );
                                }
                            }

                            image
                                .attr('src', imageSource)
                                .animate({ opacity: 1 }, 200, function() {
                                    image
                                        .removeAttr('data-lazy data-srcset data-sizes')
                                        .removeClass('slick-loading');
                                });
                            _.$slider.trigger('lazyLoaded', [_, image, imageSource]);
                        });

                };

                imageToLoad.onerror = function() {

                    image
                        .removeAttr( 'data-lazy' )
                        .removeClass( 'slick-loading' )
                        .addClass( 'slick-lazyload-error' );

                    _.$slider.trigger('lazyLoadError', [ _, image, imageSource ]);

                };

                imageToLoad.src = imageSource;

            });

        }

        if (_.options.centerMode === true) {
            if (_.options.infinite === true) {
                rangeStart = _.currentSlide + (_.options.slidesToShow / 2 + 1);
                rangeEnd = rangeStart + _.options.slidesToShow + 2;
            } else {
                rangeStart = Math.max(0, _.currentSlide - (_.options.slidesToShow / 2 + 1));
                rangeEnd = 2 + (_.options.slidesToShow / 2 + 1) + _.currentSlide;
            }
        } else {
            rangeStart = _.options.infinite ? _.options.slidesToShow + _.currentSlide : _.currentSlide;
            rangeEnd = Math.ceil(rangeStart + _.options.slidesToShow);
            if (_.options.fade === true) {
                if (rangeStart > 0) rangeStart--;
                if (rangeEnd <= _.slideCount) rangeEnd++;
            }
        }

        loadRange = _.$slider.find('.slick-slide').slice(rangeStart, rangeEnd);

        if (_.options.lazyLoad === 'anticipated') {
            var prevSlide = rangeStart - 1,
                nextSlide = rangeEnd,
                $slides = _.$slider.find('.slick-slide');

            for (var i = 0; i < _.options.slidesToScroll; i++) {
                if (prevSlide < 0) prevSlide = _.slideCount - 1;
                loadRange = loadRange.add($slides.eq(prevSlide));
                loadRange = loadRange.add($slides.eq(nextSlide));
                prevSlide--;
                nextSlide++;
            }
        }

        loadImages(loadRange);

        if (_.slideCount <= _.options.slidesToShow) {
            cloneRange = _.$slider.find('.slick-slide');
            loadImages(cloneRange);
        } else
        if (_.currentSlide >= _.slideCount - _.options.slidesToShow) {
            cloneRange = _.$slider.find('.slick-cloned').slice(0, _.options.slidesToShow);
            loadImages(cloneRange);
        } else if (_.currentSlide === 0) {
            cloneRange = _.$slider.find('.slick-cloned').slice(_.options.slidesToShow * -1);
            loadImages(cloneRange);
        }

    };

    Slick.prototype.loadSlider = function() {

        var _ = this;

        _.setPosition();

        _.$slideTrack.css({
            opacity: 1
        });

        _.$slider.removeClass('slick-loading');

        _.initUI();

        if (_.options.lazyLoad === 'progressive') {
            _.progressiveLazyLoad();
        }

    };

    Slick.prototype.next = Slick.prototype.slickNext = function() {

        var _ = this;

        _.changeSlide({
            data: {
                message: 'next'
            }
        });

    };

    Slick.prototype.orientationChange = function() {

        var _ = this;

        _.checkResponsive();
        _.setPosition();

    };

    Slick.prototype.pause = Slick.prototype.slickPause = function() {

        var _ = this;

        _.autoPlayClear();
        _.paused = true;

    };

    Slick.prototype.play = Slick.prototype.slickPlay = function() {

        var _ = this;

        _.autoPlay();
        _.options.autoplay = true;
        _.paused = false;
        _.focussed = false;
        _.interrupted = false;

    };

    Slick.prototype.postSlide = function(index) {

        var _ = this;

        if( !_.unslicked ) {

            _.$slider.trigger('afterChange', [_, index]);

            _.animating = false;

            if (_.slideCount > _.options.slidesToShow) {
                _.setPosition();
            }

            _.swipeLeft = null;

            if ( _.options.autoplay ) {
                _.autoPlay();
            }

            if (_.options.accessibility === true) {
                _.initADA();
                
                if (_.options.focusOnChange) {
                    var $currentSlide = $(_.$slides.get(_.currentSlide));
                    $currentSlide.attr('tabindex', 0).focus();
                }
            }

        }

    };

    Slick.prototype.prev = Slick.prototype.slickPrev = function() {

        var _ = this;

        _.changeSlide({
            data: {
                message: 'previous'
            }
        });

    };

    Slick.prototype.preventDefault = function(event) {

        event.preventDefault();

    };

    Slick.prototype.progressiveLazyLoad = function( tryCount ) {

        tryCount = tryCount || 1;

        var _ = this,
            $imgsToLoad = $( 'img[data-lazy]', _.$slider ),
            image,
            imageSource,
            imageSrcSet,
            imageSizes,
            imageToLoad;

        if ( $imgsToLoad.length ) {

            image = $imgsToLoad.first();
            imageSource = image.attr('data-lazy');
            imageSrcSet = image.attr('data-srcset');
            imageSizes  = image.attr('data-sizes') || _.$slider.attr('data-sizes');
            imageToLoad = document.createElement('img');

            imageToLoad.onload = function() {

                if (imageSrcSet) {
                    image
                        .attr('srcset', imageSrcSet );

                    if (imageSizes) {
                        image
                            .attr('sizes', imageSizes );
                    }
                }

                image
                    .attr( 'src', imageSource )
                    .removeAttr('data-lazy data-srcset data-sizes')
                    .removeClass('slick-loading');

                if ( _.options.adaptiveHeight === true ) {
                    _.setPosition();
                }

                _.$slider.trigger('lazyLoaded', [ _, image, imageSource ]);
                _.progressiveLazyLoad();

            };

            imageToLoad.onerror = function() {

                if ( tryCount < 3 ) {

                    /**
                     * try to load the image 3 times,
                     * leave a slight delay so we don't get
                     * servers blocking the request.
                     */
                    setTimeout( function() {
                        _.progressiveLazyLoad( tryCount + 1 );
                    }, 500 );

                } else {

                    image
                        .removeAttr( 'data-lazy' )
                        .removeClass( 'slick-loading' )
                        .addClass( 'slick-lazyload-error' );

                    _.$slider.trigger('lazyLoadError', [ _, image, imageSource ]);

                    _.progressiveLazyLoad();

                }

            };

            imageToLoad.src = imageSource;

        } else {

            _.$slider.trigger('allImagesLoaded', [ _ ]);

        }

    };

    Slick.prototype.refresh = function( initializing ) {

        var _ = this, currentSlide, lastVisibleIndex;

        lastVisibleIndex = _.slideCount - _.options.slidesToShow;

        // in non-infinite sliders, we don't want to go past the
        // last visible index.
        if( !_.options.infinite && ( _.currentSlide > lastVisibleIndex )) {
            _.currentSlide = lastVisibleIndex;
        }

        // if less slides than to show, go to start.
        if ( _.slideCount <= _.options.slidesToShow ) {
            _.currentSlide = 0;

        }

        currentSlide = _.currentSlide;

        _.destroy(true);

        $.extend(_, _.initials, { currentSlide: currentSlide });

        _.init();

        if( !initializing ) {

            _.changeSlide({
                data: {
                    message: 'index',
                    index: currentSlide
                }
            }, false);

        }

    };

    Slick.prototype.registerBreakpoints = function() {

        var _ = this, breakpoint, currentBreakpoint, l,
            responsiveSettings = _.options.responsive || null;

        if ( $.type(responsiveSettings) === 'array' && responsiveSettings.length ) {

            _.respondTo = _.options.respondTo || 'window';

            for ( breakpoint in responsiveSettings ) {

                l = _.breakpoints.length-1;

                if (responsiveSettings.hasOwnProperty(breakpoint)) {
                    currentBreakpoint = responsiveSettings[breakpoint].breakpoint;

                    // loop through the breakpoints and cut out any existing
                    // ones with the same breakpoint number, we don't want dupes.
                    while( l >= 0 ) {
                        if( _.breakpoints[l] && _.breakpoints[l] === currentBreakpoint ) {
                            _.breakpoints.splice(l,1);
                        }
                        l--;
                    }

                    _.breakpoints.push(currentBreakpoint);
                    _.breakpointSettings[currentBreakpoint] = responsiveSettings[breakpoint].settings;

                }

            }

            _.breakpoints.sort(function(a, b) {
                return ( _.options.mobileFirst ) ? a-b : b-a;
            });

        }

    };

    Slick.prototype.reinit = function() {

        var _ = this;

        _.$slides =
            _.$slideTrack
                .children(_.options.slide)
                .addClass('slick-slide');

        _.slideCount = _.$slides.length;

        if (_.currentSlide >= _.slideCount && _.currentSlide !== 0) {
            _.currentSlide = _.currentSlide - _.options.slidesToScroll;
        }

        if (_.slideCount <= _.options.slidesToShow) {
            _.currentSlide = 0;
        }

        _.registerBreakpoints();

        _.setProps();
        _.setupInfinite();
        _.buildArrows();
        _.updateArrows();
        _.initArrowEvents();
        _.buildDots();
        _.updateDots();
        _.initDotEvents();
        _.cleanUpSlideEvents();
        _.initSlideEvents();

        _.checkResponsive(false, true);

        if (_.options.focusOnSelect === true) {
            $(_.$slideTrack).children().on('click.slick', _.selectHandler);
        }

        _.setSlideClasses(typeof _.currentSlide === 'number' ? _.currentSlide : 0);

        _.setPosition();
        _.focusHandler();

        _.paused = !_.options.autoplay;
        _.autoPlay();

        _.$slider.trigger('reInit', [_]);

    };

    Slick.prototype.resize = function() {

        var _ = this;

        if ($(window).width() !== _.windowWidth) {
            clearTimeout(_.windowDelay);
            _.windowDelay = window.setTimeout(function() {
                _.windowWidth = $(window).width();
                _.checkResponsive();
                if( !_.unslicked ) { _.setPosition(); }
            }, 50);
        }
    };

    Slick.prototype.removeSlide = Slick.prototype.slickRemove = function(index, removeBefore, removeAll) {

        var _ = this;

        if (typeof(index) === 'boolean') {
            removeBefore = index;
            index = removeBefore === true ? 0 : _.slideCount - 1;
        } else {
            index = removeBefore === true ? --index : index;
        }

        if (_.slideCount < 1 || index < 0 || index > _.slideCount - 1) {
            return false;
        }

        _.unload();

        if (removeAll === true) {
            _.$slideTrack.children().remove();
        } else {
            _.$slideTrack.children(this.options.slide).eq(index).remove();
        }

        _.$slides = _.$slideTrack.children(this.options.slide);

        _.$slideTrack.children(this.options.slide).detach();

        _.$slideTrack.append(_.$slides);

        _.$slidesCache = _.$slides;

        _.reinit();

    };

    Slick.prototype.setCSS = function(position) {

        var _ = this,
            positionProps = {},
            x, y;

        if (_.options.rtl === true) {
            position = -position;
        }
        x = _.positionProp == 'left' ? Math.ceil(position) + 'px' : '0px';
        y = _.positionProp == 'top' ? Math.ceil(position) + 'px' : '0px';

        positionProps[_.positionProp] = position;

        if (_.transformsEnabled === false) {
            _.$slideTrack.css(positionProps);
        } else {
            positionProps = {};
            if (_.cssTransitions === false) {
                positionProps[_.animType] = 'translate(' + x + ', ' + y + ')';
                _.$slideTrack.css(positionProps);
            } else {
                positionProps[_.animType] = 'translate3d(' + x + ', ' + y + ', 0px)';
                _.$slideTrack.css(positionProps);
            }
        }

    };

    Slick.prototype.setDimensions = function() {

        var _ = this;

        if (_.options.vertical === false) {
            if (_.options.centerMode === true) {
                _.$list.css({
                    padding: ('0px ' + _.options.centerPadding)
                });
            }
        } else {
            _.$list.height(_.$slides.first().outerHeight(true) * _.options.slidesToShow);
            if (_.options.centerMode === true) {
                _.$list.css({
                    padding: (_.options.centerPadding + ' 0px')
                });
            }
        }

        _.listWidth = _.$list.width();
        _.listHeight = _.$list.height();


        if (_.options.vertical === false && _.options.variableWidth === false) {
            _.slideWidth = Math.ceil(_.listWidth / _.options.slidesToShow);
            _.$slideTrack.width(Math.ceil((_.slideWidth * _.$slideTrack.children('.slick-slide').length)));

        } else if (_.options.variableWidth === true) {
            _.$slideTrack.width(5000 * _.slideCount);
        } else {
            _.slideWidth = Math.ceil(_.listWidth);
            _.$slideTrack.height(Math.ceil((_.$slides.first().outerHeight(true) * _.$slideTrack.children('.slick-slide').length)));
        }

        var offset = _.$slides.first().outerWidth(true) - _.$slides.first().width();
        if (_.options.variableWidth === false) _.$slideTrack.children('.slick-slide').width(_.slideWidth - offset);

    };

    Slick.prototype.setFade = function() {

        var _ = this,
            targetLeft;

        _.$slides.each(function(index, element) {
            targetLeft = (_.slideWidth * index) * -1;
            if (_.options.rtl === true) {
                $(element).css({
                    position: 'relative',
                    right: targetLeft,
                    top: 0,
                    zIndex: _.options.zIndex - 2,
                    opacity: 0
                });
            } else {
                $(element).css({
                    position: 'relative',
                    left: targetLeft,
                    top: 0,
                    zIndex: _.options.zIndex - 2,
                    opacity: 0
                });
            }
        });

        _.$slides.eq(_.currentSlide).css({
            zIndex: _.options.zIndex - 1,
            opacity: 1
        });

    };

    Slick.prototype.setHeight = function() {

        var _ = this;

        if (_.options.slidesToShow === 1 && _.options.adaptiveHeight === true && _.options.vertical === false) {
            var targetHeight = _.$slides.eq(_.currentSlide).outerHeight(true);
            _.$list.css('height', targetHeight);
        }

    };

    Slick.prototype.setOption =
    Slick.prototype.slickSetOption = function() {

        /**
         * accepts arguments in format of:
         *
         *  - for changing a single option's value:
         *     .slick("setOption", option, value, refresh )
         *
         *  - for changing a set of responsive options:
         *     .slick("setOption", 'responsive', [{}, ...], refresh )
         *
         *  - for updating multiple values at once (not responsive)
         *     .slick("setOption", { 'option': value, ... }, refresh )
         */

        var _ = this, l, item, option, value, refresh = false, type;

        if( $.type( arguments[0] ) === 'object' ) {

            option =  arguments[0];
            refresh = arguments[1];
            type = 'multiple';

        } else if ( $.type( arguments[0] ) === 'string' ) {

            option =  arguments[0];
            value = arguments[1];
            refresh = arguments[2];

            if ( arguments[0] === 'responsive' && $.type( arguments[1] ) === 'array' ) {

                type = 'responsive';

            } else if ( typeof arguments[1] !== 'undefined' ) {

                type = 'single';

            }

        }

        if ( type === 'single' ) {

            _.options[option] = value;


        } else if ( type === 'multiple' ) {

            $.each( option , function( opt, val ) {

                _.options[opt] = val;

            });


        } else if ( type === 'responsive' ) {

            for ( item in value ) {

                if( $.type( _.options.responsive ) !== 'array' ) {

                    _.options.responsive = [ value[item] ];

                } else {

                    l = _.options.responsive.length-1;

                    // loop through the responsive object and splice out duplicates.
                    while( l >= 0 ) {

                        if( _.options.responsive[l].breakpoint === value[item].breakpoint ) {

                            _.options.responsive.splice(l,1);

                        }

                        l--;

                    }

                    _.options.responsive.push( value[item] );

                }

            }

        }

        if ( refresh ) {

            _.unload();
            _.reinit();

        }

    };

    Slick.prototype.setPosition = function() {

        var _ = this;

        _.setDimensions();

        _.setHeight();

        if (_.options.fade === false) {
            _.setCSS(_.getLeft(_.currentSlide));
        } else {
            _.setFade();
        }

        _.$slider.trigger('setPosition', [_]);

    };

    Slick.prototype.setProps = function() {

        var _ = this,
            bodyStyle = document.body.style;

        _.positionProp = _.options.vertical === true ? 'top' : 'left';

        if (_.positionProp === 'top') {
            _.$slider.addClass('slick-vertical');
        } else {
            _.$slider.removeClass('slick-vertical');
        }

        if (bodyStyle.WebkitTransition !== undefined ||
            bodyStyle.MozTransition !== undefined ||
            bodyStyle.msTransition !== undefined) {
            if (_.options.useCSS === true) {
                _.cssTransitions = true;
            }
        }

        if ( _.options.fade ) {
            if ( typeof _.options.zIndex === 'number' ) {
                if( _.options.zIndex < 3 ) {
                    _.options.zIndex = 3;
                }
            } else {
                _.options.zIndex = _.defaults.zIndex;
            }
        }

        if (bodyStyle.OTransform !== undefined) {
            _.animType = 'OTransform';
            _.transformType = '-o-transform';
            _.transitionType = 'OTransition';
            if (bodyStyle.perspectiveProperty === undefined && bodyStyle.webkitPerspective === undefined) _.animType = false;
        }
        if (bodyStyle.MozTransform !== undefined) {
            _.animType = 'MozTransform';
            _.transformType = '-moz-transform';
            _.transitionType = 'MozTransition';
            if (bodyStyle.perspectiveProperty === undefined && bodyStyle.MozPerspective === undefined) _.animType = false;
        }
        if (bodyStyle.webkitTransform !== undefined) {
            _.animType = 'webkitTransform';
            _.transformType = '-webkit-transform';
            _.transitionType = 'webkitTransition';
            if (bodyStyle.perspectiveProperty === undefined && bodyStyle.webkitPerspective === undefined) _.animType = false;
        }
        if (bodyStyle.msTransform !== undefined) {
            _.animType = 'msTransform';
            _.transformType = '-ms-transform';
            _.transitionType = 'msTransition';
            if (bodyStyle.msTransform === undefined) _.animType = false;
        }
        if (bodyStyle.transform !== undefined && _.animType !== false) {
            _.animType = 'transform';
            _.transformType = 'transform';
            _.transitionType = 'transition';
        }
        _.transformsEnabled = _.options.useTransform && (_.animType !== null && _.animType !== false);
    };


    Slick.prototype.setSlideClasses = function(index) {

        var _ = this,
            centerOffset, allSlides, indexOffset, remainder;

        allSlides = _.$slider
            .find('.slick-slide')
            .removeClass('slick-active slick-center slick-current')
            .attr('aria-hidden', 'true');

        _.$slides
            .eq(index)
            .addClass('slick-current');

        if (_.options.centerMode === true) {

            var evenCoef = _.options.slidesToShow % 2 === 0 ? 1 : 0;

            centerOffset = Math.floor(_.options.slidesToShow / 2);

            if (_.options.infinite === true) {

                if (index >= centerOffset && index <= (_.slideCount - 1) - centerOffset) {
                    _.$slides
                        .slice(index - centerOffset + evenCoef, index + centerOffset + 1)
                        .addClass('slick-active')
                        .attr('aria-hidden', 'false');

                } else {

                    indexOffset = _.options.slidesToShow + index;
                    allSlides
                        .slice(indexOffset - centerOffset + 1 + evenCoef, indexOffset + centerOffset + 2)
                        .addClass('slick-active')
                        .attr('aria-hidden', 'false');

                }

                if (index === 0) {

                    allSlides
                        .eq(allSlides.length - 1 - _.options.slidesToShow)
                        .addClass('slick-center');

                } else if (index === _.slideCount - 1) {

                    allSlides
                        .eq(_.options.slidesToShow)
                        .addClass('slick-center');

                }

            }

            _.$slides
                .eq(index)
                .addClass('slick-center');

        } else {

            if (index >= 0 && index <= (_.slideCount - _.options.slidesToShow)) {

                _.$slides
                    .slice(index, index + _.options.slidesToShow)
                    .addClass('slick-active')
                    .attr('aria-hidden', 'false');

            } else if (allSlides.length <= _.options.slidesToShow) {

                allSlides
                    .addClass('slick-active')
                    .attr('aria-hidden', 'false');

            } else {

                remainder = _.slideCount % _.options.slidesToShow;
                indexOffset = _.options.infinite === true ? _.options.slidesToShow + index : index;

                if (_.options.slidesToShow == _.options.slidesToScroll && (_.slideCount - index) < _.options.slidesToShow) {

                    allSlides
                        .slice(indexOffset - (_.options.slidesToShow - remainder), indexOffset + remainder)
                        .addClass('slick-active')
                        .attr('aria-hidden', 'false');

                } else {

                    allSlides
                        .slice(indexOffset, indexOffset + _.options.slidesToShow)
                        .addClass('slick-active')
                        .attr('aria-hidden', 'false');

                }

            }

        }

        if (_.options.lazyLoad === 'ondemand' || _.options.lazyLoad === 'anticipated') {
            _.lazyLoad();
        }
    };

    Slick.prototype.setupInfinite = function() {

        var _ = this,
            i, slideIndex, infiniteCount;

        if (_.options.fade === true) {
            _.options.centerMode = false;
        }

        if (_.options.infinite === true && _.options.fade === false) {

            slideIndex = null;

            if (_.slideCount > _.options.slidesToShow) {

                if (_.options.centerMode === true) {
                    infiniteCount = _.options.slidesToShow + 1;
                } else {
                    infiniteCount = _.options.slidesToShow;
                }

                for (i = _.slideCount; i > (_.slideCount -
                        infiniteCount); i -= 1) {
                    slideIndex = i - 1;
                    $(_.$slides[slideIndex]).clone(true).attr('id', '')
                        .attr('data-slick-index', slideIndex - _.slideCount)
                        .prependTo(_.$slideTrack).addClass('slick-cloned');
                }
                for (i = 0; i < infiniteCount  + _.slideCount; i += 1) {
                    slideIndex = i;
                    $(_.$slides[slideIndex]).clone(true).attr('id', '')
                        .attr('data-slick-index', slideIndex + _.slideCount)
                        .appendTo(_.$slideTrack).addClass('slick-cloned');
                }
                _.$slideTrack.find('.slick-cloned').find('[id]').each(function() {
                    $(this).attr('id', '');
                });

            }

        }

    };

    Slick.prototype.interrupt = function( toggle ) {

        var _ = this;

        if( !toggle ) {
            _.autoPlay();
        }
        _.interrupted = toggle;

    };

    Slick.prototype.selectHandler = function(event) {

        var _ = this;

        var targetElement =
            $(event.target).is('.slick-slide') ?
                $(event.target) :
                $(event.target).parents('.slick-slide');

        var index = parseInt(targetElement.attr('data-slick-index'));

        if (!index) index = 0;

        if (_.slideCount <= _.options.slidesToShow) {

            _.slideHandler(index, false, true);
            return;

        }

        _.slideHandler(index);

    };

    Slick.prototype.slideHandler = function(index, sync, dontAnimate) {

        var targetSlide, animSlide, oldSlide, slideLeft, targetLeft = null,
            _ = this, navTarget;

        sync = sync || false;

        if (_.animating === true && _.options.waitForAnimate === true) {
            return;
        }

        if (_.options.fade === true && _.currentSlide === index) {
            return;
        }

        if (sync === false) {
            _.asNavFor(index);
        }

        targetSlide = index;
        targetLeft = _.getLeft(targetSlide);
        slideLeft = _.getLeft(_.currentSlide);

        _.currentLeft = _.swipeLeft === null ? slideLeft : _.swipeLeft;

        if (_.options.infinite === false && _.options.centerMode === false && (index < 0 || index > _.getDotCount() * _.options.slidesToScroll)) {
            if (_.options.fade === false) {
                targetSlide = _.currentSlide;
                if (dontAnimate !== true) {
                    _.animateSlide(slideLeft, function() {
                        _.postSlide(targetSlide);
                    });
                } else {
                    _.postSlide(targetSlide);
                }
            }
            return;
        } else if (_.options.infinite === false && _.options.centerMode === true && (index < 0 || index > (_.slideCount - _.options.slidesToScroll))) {
            if (_.options.fade === false) {
                targetSlide = _.currentSlide;
                if (dontAnimate !== true) {
                    _.animateSlide(slideLeft, function() {
                        _.postSlide(targetSlide);
                    });
                } else {
                    _.postSlide(targetSlide);
                }
            }
            return;
        }

        if ( _.options.autoplay ) {
            clearInterval(_.autoPlayTimer);
        }

        if (targetSlide < 0) {
            if (_.slideCount % _.options.slidesToScroll !== 0) {
                animSlide = _.slideCount - (_.slideCount % _.options.slidesToScroll);
            } else {
                animSlide = _.slideCount + targetSlide;
            }
        } else if (targetSlide >= _.slideCount) {
            if (_.slideCount % _.options.slidesToScroll !== 0) {
                animSlide = 0;
            } else {
                animSlide = targetSlide - _.slideCount;
            }
        } else {
            animSlide = targetSlide;
        }

        _.animating = true;

        _.$slider.trigger('beforeChange', [_, _.currentSlide, animSlide]);

        oldSlide = _.currentSlide;
        _.currentSlide = animSlide;

        _.setSlideClasses(_.currentSlide);

        if ( _.options.asNavFor ) {

            navTarget = _.getNavTarget();
            navTarget = navTarget.slick('getSlick');

            if ( navTarget.slideCount <= navTarget.options.slidesToShow ) {
                navTarget.setSlideClasses(_.currentSlide);
            }

        }

        _.updateDots();
        _.updateArrows();

        if (_.options.fade === true) {
            if (dontAnimate !== true) {

                _.fadeSlideOut(oldSlide);

                _.fadeSlide(animSlide, function() {
                    _.postSlide(animSlide);
                });

            } else {
                _.postSlide(animSlide);
            }
            _.animateHeight();
            return;
        }

        if (dontAnimate !== true) {
            _.animateSlide(targetLeft, function() {
                _.postSlide(animSlide);
            });
        } else {
            _.postSlide(animSlide);
        }

    };

    Slick.prototype.startLoad = function() {

        var _ = this;

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {

            _.$prevArrow.hide();
            _.$nextArrow.hide();

        }

        if (_.options.dots === true && _.slideCount > _.options.slidesToShow) {

            _.$dots.hide();

        }

        _.$slider.addClass('slick-loading');

    };

    Slick.prototype.swipeDirection = function() {

        var xDist, yDist, r, swipeAngle, _ = this;

        xDist = _.touchObject.startX - _.touchObject.curX;
        yDist = _.touchObject.startY - _.touchObject.curY;
        r = Math.atan2(yDist, xDist);

        swipeAngle = Math.round(r * 180 / Math.PI);
        if (swipeAngle < 0) {
            swipeAngle = 360 - Math.abs(swipeAngle);
        }

        if ((swipeAngle <= 45) && (swipeAngle >= 0)) {
            return (_.options.rtl === false ? 'left' : 'right');
        }
        if ((swipeAngle <= 360) && (swipeAngle >= 315)) {
            return (_.options.rtl === false ? 'left' : 'right');
        }
        if ((swipeAngle >= 135) && (swipeAngle <= 225)) {
            return (_.options.rtl === false ? 'right' : 'left');
        }
        if (_.options.verticalSwiping === true) {
            if ((swipeAngle >= 35) && (swipeAngle <= 135)) {
                return 'down';
            } else {
                return 'up';
            }
        }

        return 'vertical';

    };

    Slick.prototype.swipeEnd = function(event) {

        var _ = this,
            slideCount,
            direction;

        _.dragging = false;
        _.swiping = false;

        if (_.scrolling) {
            _.scrolling = false;
            return false;
        }

        _.interrupted = false;
        _.shouldClick = ( _.touchObject.swipeLength > 10 ) ? false : true;

        if ( _.touchObject.curX === undefined ) {
            return false;
        }

        if ( _.touchObject.edgeHit === true ) {
            _.$slider.trigger('edge', [_, _.swipeDirection() ]);
        }

        if ( _.touchObject.swipeLength >= _.touchObject.minSwipe ) {

            direction = _.swipeDirection();

            switch ( direction ) {

                case 'left':
                case 'down':

                    slideCount =
                        _.options.swipeToSlide ?
                            _.checkNavigable( _.currentSlide + _.getSlideCount() ) :
                            _.currentSlide + _.getSlideCount();

                    _.currentDirection = 0;

                    break;

                case 'right':
                case 'up':

                    slideCount =
                        _.options.swipeToSlide ?
                            _.checkNavigable( _.currentSlide - _.getSlideCount() ) :
                            _.currentSlide - _.getSlideCount();

                    _.currentDirection = 1;

                    break;

                default:


            }

            if( direction != 'vertical' ) {

                _.slideHandler( slideCount );
                _.touchObject = {};
                _.$slider.trigger('swipe', [_, direction ]);

            }

        } else {

            if ( _.touchObject.startX !== _.touchObject.curX ) {

                _.slideHandler( _.currentSlide );
                _.touchObject = {};

            }

        }

    };

    Slick.prototype.swipeHandler = function(event) {

        var _ = this;

        if ((_.options.swipe === false) || ('ontouchend' in document && _.options.swipe === false)) {
            return;
        } else if (_.options.draggable === false && event.type.indexOf('mouse') !== -1) {
            return;
        }

        _.touchObject.fingerCount = event.originalEvent && event.originalEvent.touches !== undefined ?
            event.originalEvent.touches.length : 1;

        _.touchObject.minSwipe = _.listWidth / _.options
            .touchThreshold;

        if (_.options.verticalSwiping === true) {
            _.touchObject.minSwipe = _.listHeight / _.options
                .touchThreshold;
        }

        switch (event.data.action) {

            case 'start':
                _.swipeStart(event);
                break;

            case 'move':
                _.swipeMove(event);
                break;

            case 'end':
                _.swipeEnd(event);
                break;

        }

    };

    Slick.prototype.swipeMove = function(event) {

        var _ = this,
            edgeWasHit = false,
            curLeft, swipeDirection, swipeLength, positionOffset, touches, verticalSwipeLength;

        touches = event.originalEvent !== undefined ? event.originalEvent.touches : null;

        if (!_.dragging || _.scrolling || touches && touches.length !== 1) {
            return false;
        }

        curLeft = _.getLeft(_.currentSlide);

        _.touchObject.curX = touches !== undefined ? touches[0].pageX : event.clientX;
        _.touchObject.curY = touches !== undefined ? touches[0].pageY : event.clientY;

        _.touchObject.swipeLength = Math.round(Math.sqrt(
            Math.pow(_.touchObject.curX - _.touchObject.startX, 2)));

        verticalSwipeLength = Math.round(Math.sqrt(
            Math.pow(_.touchObject.curY - _.touchObject.startY, 2)));

        if (!_.options.verticalSwiping && !_.swiping && verticalSwipeLength > 4) {
            _.scrolling = true;
            return false;
        }

        if (_.options.verticalSwiping === true) {
            _.touchObject.swipeLength = verticalSwipeLength;
        }

        swipeDirection = _.swipeDirection();

        if (event.originalEvent !== undefined && _.touchObject.swipeLength > 4) {
            _.swiping = true;
            event.preventDefault();
        }

        positionOffset = (_.options.rtl === false ? 1 : -1) * (_.touchObject.curX > _.touchObject.startX ? 1 : -1);
        if (_.options.verticalSwiping === true) {
            positionOffset = _.touchObject.curY > _.touchObject.startY ? 1 : -1;
        }


        swipeLength = _.touchObject.swipeLength;

        _.touchObject.edgeHit = false;

        if (_.options.infinite === false) {
            if ((_.currentSlide === 0 && swipeDirection === 'right') || (_.currentSlide >= _.getDotCount() && swipeDirection === 'left')) {
                swipeLength = _.touchObject.swipeLength * _.options.edgeFriction;
                _.touchObject.edgeHit = true;
            }
        }

        if (_.options.vertical === false) {
            _.swipeLeft = curLeft + swipeLength * positionOffset;
        } else {
            _.swipeLeft = curLeft + (swipeLength * (_.$list.height() / _.listWidth)) * positionOffset;
        }
        if (_.options.verticalSwiping === true) {
            _.swipeLeft = curLeft + swipeLength * positionOffset;
        }

        if (_.options.fade === true || _.options.touchMove === false) {
            return false;
        }

        if (_.animating === true) {
            _.swipeLeft = null;
            return false;
        }

        _.setCSS(_.swipeLeft);

    };

    Slick.prototype.swipeStart = function(event) {

        var _ = this,
            touches;

        _.interrupted = true;

        if (_.touchObject.fingerCount !== 1 || _.slideCount <= _.options.slidesToShow) {
            _.touchObject = {};
            return false;
        }

        if (event.originalEvent !== undefined && event.originalEvent.touches !== undefined) {
            touches = event.originalEvent.touches[0];
        }

        _.touchObject.startX = _.touchObject.curX = touches !== undefined ? touches.pageX : event.clientX;
        _.touchObject.startY = _.touchObject.curY = touches !== undefined ? touches.pageY : event.clientY;

        _.dragging = true;

    };

    Slick.prototype.unfilterSlides = Slick.prototype.slickUnfilter = function() {

        var _ = this;

        if (_.$slidesCache !== null) {

            _.unload();

            _.$slideTrack.children(this.options.slide).detach();

            _.$slidesCache.appendTo(_.$slideTrack);

            _.reinit();

        }

    };

    Slick.prototype.unload = function() {

        var _ = this;

        $('.slick-cloned', _.$slider).remove();

        if (_.$dots) {
            _.$dots.remove();
        }

        if (_.$prevArrow && _.htmlExpr.test(_.options.prevArrow)) {
            _.$prevArrow.remove();
        }

        if (_.$nextArrow && _.htmlExpr.test(_.options.nextArrow)) {
            _.$nextArrow.remove();
        }

        _.$slides
            .removeClass('slick-slide slick-active slick-visible slick-current')
            .attr('aria-hidden', 'true')
            .css('width', '');

    };

    Slick.prototype.unslick = function(fromBreakpoint) {

        var _ = this;
        _.$slider.trigger('unslick', [_, fromBreakpoint]);
        _.destroy();

    };

    Slick.prototype.updateArrows = function() {

        var _ = this,
            centerOffset;

        centerOffset = Math.floor(_.options.slidesToShow / 2);

        if ( _.options.arrows === true &&
            _.slideCount > _.options.slidesToShow &&
            !_.options.infinite ) {

            _.$prevArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');
            _.$nextArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');

            if (_.currentSlide === 0) {

                _.$prevArrow.addClass('slick-disabled').attr('aria-disabled', 'true');
                _.$nextArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');

            } else if (_.currentSlide >= _.slideCount - _.options.slidesToShow && _.options.centerMode === false) {

                _.$nextArrow.addClass('slick-disabled').attr('aria-disabled', 'true');
                _.$prevArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');

            } else if (_.currentSlide >= _.slideCount - 1 && _.options.centerMode === true) {

                _.$nextArrow.addClass('slick-disabled').attr('aria-disabled', 'true');
                _.$prevArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');

            }

        }

    };

    Slick.prototype.updateDots = function() {

        var _ = this;

        if (_.$dots !== null) {

            _.$dots
                .find('li')
                    .removeClass('slick-active')
                    .end();

            _.$dots
                .find('li')
                .eq(Math.floor(_.currentSlide / _.options.slidesToScroll))
                .addClass('slick-active');

        }

    };

    Slick.prototype.visibility = function() {

        var _ = this;

        if ( _.options.autoplay ) {

            if ( document[_.hidden] ) {

                _.interrupted = true;

            } else {

                _.interrupted = false;

            }

        }

    };

    $.fn.slick = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i,
            ret;
        for (i = 0; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].slick = new Slick(_[i], opt);
            else
                ret = _[i].slick[opt].apply(_[i].slick, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));

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
        var progressSource = sourcePreview.find('.gfield_fileupload_progress');
        var boxEl = customPreview.find('.kfgf-file-previews__box');
        var statusEl = customPreview.find('.kfgf-file-previews__status');
        var progressEl = customPreview.find('.kfgf-file-previews__progress');
        var cancelEl = customPreview.find('.kfgf-file-previews__cancel');
        
        // if this is a new preview, perform initial setup
        if (customPreview.hasClass('kfgf-file-previews__item_new')) {
            var name = '';
            
            // set name text
            name = sourcePreview.find('.gfield_fileupload_filename').text();
            customPreview.find('.kfgf-file-previews__name').text(name);
            customPreview.find('.kfgf-file-previews__box').attr('title', name);
            
            // add cancel event handler
            cancelEl.click(function() {
                sourcePreview.find('.gform_delete_file').trigger('click');
                sourcePreview.find('.gfield_fileupload_cancel').trigger('click');
                
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
        } else if (progressSource.text().trim() == '100%') {
            status = 'complete';
        } else if (sourcePreview.find('.gfield_fileupload_cancel').length == 0 && sourcePreview.find('.gform_delete_file').length == 0 && progressSource.text().trim() != '100%') {
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
                var previews = inputWrap.find('.ginput_preview_list .ginput_preview');
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



/* Autoplay Video Check */

function initAutoplayCheck() {
    function isAutoplaySupported(callback) {
        var video = document.createElement('video');
        video.autoplay = true;
        video.setAttribute('playsinline', '');
        video.muted = true;
        video.src = 'data:video/mp4;base64,AAAAIGZ0eXBtcDQyAAAAAG1wNDJtcDQxaXNvbWF2YzEAAATKbW9vdgAAAGxtdmhkAAAAANLEP5XSxD+VAAB1MAAAdU4AAQAAAQAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAACFpb2RzAAAAABCAgIAQAE////9//w6AgIAEAAAAAQAABDV0cmFrAAAAXHRraGQAAAAH0sQ/ldLEP5UAAAABAAAAAAAAdU4AAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAABAAAAAAoAAAAFoAAAAAAAkZWR0cwAAABxlbHN0AAAAAAAAAAEAAHVOAAAH0gABAAAAAAOtbWRpYQAAACBtZGhkAAAAANLEP5XSxD+VAAB1MAAAdU5VxAAAAAAANmhkbHIAAAAAAAAAAHZpZGUAAAAAAAAAAAAAAABMLVNNQVNIIFZpZGVvIEhhbmRsZXIAAAADT21pbmYAAAAUdm1oZAAAAAEAAAAAAAAAAAAAACRkaW5mAAAAHGRyZWYAAAAAAAAAAQAAAAx1cmwgAAAAAQAAAw9zdGJsAAAAwXN0c2QAAAAAAAAAAQAAALFhdmMxAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAoABaABIAAAASAAAAAAAAAABCkFWQyBDb2RpbmcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP//AAAAOGF2Y0MBZAAf/+EAHGdkAB+s2UCgL/lwFqCgoKgAAB9IAAdTAHjBjLABAAVo6+yyLP34+AAAAAATY29scm5jbHgABQAFAAUAAAAAEHBhc3AAAAABAAAAAQAAABhzdHRzAAAAAAAAAAEAAAAeAAAD6QAAAQBjdHRzAAAAAAAAAB4AAAABAAAH0gAAAAEAABONAAAAAQAAB9IAAAABAAAAAAAAAAEAAAPpAAAAAQAAE40AAAABAAAH0gAAAAEAAAAAAAAAAQAAA+kAAAABAAATjQAAAAEAAAfSAAAAAQAAAAAAAAABAAAD6QAAAAEAABONAAAAAQAAB9IAAAABAAAAAAAAAAEAAAPpAAAAAQAAE40AAAABAAAH0gAAAAEAAAAAAAAAAQAAA+kAAAABAAATjQAAAAEAAAfSAAAAAQAAAAAAAAABAAAD6QAAAAEAABONAAAAAQAAB9IAAAABAAAAAAAAAAEAAAPpAAAAAQAAB9IAAAAUc3RzcwAAAAAAAAABAAAAAQAAACpzZHRwAAAAAKaWlpqalpaampaWmpqWlpqalpaampaWmpqWlpqalgAAABxzdHNjAAAAAAAAAAEAAAABAAAAHgAAAAEAAACMc3RzegAAAAAAAAAAAAAAHgAAA5YAAAAVAAAAEwAAABMAAAATAAAAGwAAABUAAAATAAAAEwAAABsAAAAVAAAAEwAAABMAAAAbAAAAFQAAABMAAAATAAAAGwAAABUAAAATAAAAEwAAABsAAAAVAAAAEwAAABMAAAAbAAAAFQAAABMAAAATAAAAGwAAABRzdGNvAAAAAAAAAAEAAAT6AAAAGHNncGQBAAAAcm9sbAAAAAIAAAAAAAAAHHNiZ3AAAAAAcm9sbAAAAAEAAAAeAAAAAAAAAAhmcmVlAAAGC21kYXQAAAMfBgX///8b3EXpvebZSLeWLNgg2SPu73gyNjQgLSBjb3JlIDE0OCByMTEgNzU5OTIxMCAtIEguMjY0L01QRUctNCBBVkMgY29kZWMgLSBDb3B5bGVmdCAyMDAzLTIwMTUgLSBodHRwOi8vd3d3LnZpZGVvbGFuLm9yZy94MjY0Lmh0bWwgLSBvcHRpb25zOiBjYWJhYz0xIHJlZj0zIGRlYmxvY2s9MTowOjAgYW5hbHlzZT0weDM6MHgxMTMgbWU9aGV4IHN1Ym1lPTcgcHN5PTEgcHN5X3JkPTEuMDA6MC4wMCBtaXhlZF9yZWY9MSBtZV9yYW5nZT0xNiBjaHJvbWFfbWU9MSB0cmVsbGlzPTEgOHg4ZGN0PTEgY3FtPTAgZGVhZHpvbmU9MjEsMTEgZmFzdF9wc2tpcD0xIGNocm9tYV9xcF9vZmZzZXQ9LTIgdGhyZWFkcz0xMSBsb29rYWhlYWRfdGhyZWFkcz0xIHNsaWNlZF90aHJlYWRzPTAgbnI9MCBkZWNpbWF0ZT0xIGludGVybGFjZWQ9MCBibHVyYXlfY29tcGF0PTAgc3RpdGNoYWJsZT0xIGNvbnN0cmFpbmVkX2ludHJhPTAgYmZyYW1lcz0zIGJfcHlyYW1pZD0yIGJfYWRhcHQ9MSBiX2JpYXM9MCBkaXJlY3Q9MSB3ZWlnaHRiPTEgb3Blbl9nb3A9MCB3ZWlnaHRwPTIga2V5aW50PWluZmluaXRlIGtleWludF9taW49Mjkgc2NlbmVjdXQ9NDAgaW50cmFfcmVmcmVzaD0wIHJjX2xvb2thaGVhZD00MCByYz0ycGFzcyBtYnRyZWU9MSBiaXRyYXRlPTExMiByYXRldG9sPTEuMCBxY29tcD0wLjYwIHFwbWluPTUgcXBtYXg9NjkgcXBzdGVwPTQgY3BseGJsdXI9MjAuMCBxYmx1cj0wLjUgdmJ2X21heHJhdGU9ODI1IHZidl9idWZzaXplPTkwMCBuYWxfaHJkPW5vbmUgZmlsbGVyPTAgaXBfcmF0aW89MS40MCBhcT0xOjEuMDAAgAAAAG9liIQAFf/+963fgU3DKzVrulc4tMurlDQ9UfaUpni2SAAAAwAAAwAAD/DNvp9RFdeXpgAAAwB+ABHAWYLWHUFwGoHeKCOoUwgBAAADAAADAAADAAADAAAHgvugkks0lyOD2SZ76WaUEkznLgAAFFEAAAARQZokbEFf/rUqgAAAAwAAHVAAAAAPQZ5CeIK/AAADAAADAA6ZAAAADwGeYXRBXwAAAwAAAwAOmAAAAA8BnmNqQV8AAAMAAAMADpkAAAAXQZpoSahBaJlMCCv//rUqgAAAAwAAHVEAAAARQZ6GRREsFf8AAAMAAAMADpkAAAAPAZ6ldEFfAAADAAADAA6ZAAAADwGep2pBXwAAAwAAAwAOmAAAABdBmqxJqEFsmUwIK//+tSqAAAADAAAdUAAAABFBnspFFSwV/wAAAwAAAwAOmQAAAA8Bnul0QV8AAAMAAAMADpgAAAAPAZ7rakFfAAADAAADAA6YAAAAF0Ga8EmoQWyZTAgr//61KoAAAAMAAB1RAAAAEUGfDkUVLBX/AAADAAADAA6ZAAAADwGfLXRBXwAAAwAAAwAOmQAAAA8Bny9qQV8AAAMAAAMADpgAAAAXQZs0SahBbJlMCCv//rUqgAAAAwAAHVAAAAARQZ9SRRUsFf8AAAMAAAMADpkAAAAPAZ9xdEFfAAADAAADAA6YAAAADwGfc2pBXwAAAwAAAwAOmAAAABdBm3hJqEFsmUwIK//+tSqAAAADAAAdUQAAABFBn5ZFFSwV/wAAAwAAAwAOmAAAAA8Bn7V0QV8AAAMAAAMADpkAAAAPAZ+3akFfAAADAAADAA6ZAAAAF0GbvEmoQWyZTAgr//61KoAAAAMAAB1QAAAAEUGf2kUVLBX/AAADAAADAA6ZAAAADwGf+XRBXwAAAwAAAwAOmAAAAA8Bn/tqQV8AAAMAAAMADpkAAAAXQZv9SahBbJlMCCv//rUqgAAAAwAAHVE=';
        video.load();
        video.style.display = 'none';
        video.playing = false;
        video.testDone = false;
        try {
            video.play();
        } catch (e) {
            callback(false);
            video.testDone = true;
        }
        video.onplay = function() {
            this.playing = true;
        };
        video.oncanplay = function() {
            if (!video.testDone) {
                if (video.playing) {
                    callback(true);
                } else {
                    callback(false);
                }
                video.testDone = true;
            }
        };

        setTimeout(function() {
            if (!video.testDone) {
                if (video.playing) {
                    callback(true);
                } else {
                    callback(false);
                }
                video.testDone = true;
            }
        }, 500);
    }
    
    
    isAutoplaySupported(function(supported) {
        if (!supported) {
            // enable fallback images and disable videos
            $('.autoplay-video__image').removeClass('autoplay-video__image_disabled');
            $('.autoplay-video__video').addClass('autoplay-video__video_disabled');
        }
    });
}



/* General */

$(function() {
    initMasonryGrid();
    initFitVids();
    initImageSlider();
    initTestimonialSlider();
    initLastItemFlexRow();
    initAutoplayCheck();
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

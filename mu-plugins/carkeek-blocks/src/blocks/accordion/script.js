import jQuery from 'jquery';
import "./style.scss";

( function( $ ) {
    $(document).ready(function(){

        $('.wp-block-carkeek-blocks-expand-collapse.is-range-style').each(function(){
            const count = $(this).find(".wp-block-carkeek-blocks-expand-collapse-section:not(.expand-collapse-default)").length;
            const $this = $(this);
            const $sliderEl = $('<div></div>')
            $(this).find(".range-slider-element").append($sliderEl);
            $sliderEl.slider({
                value: 0,
                min: 0,
                step: 1,
                max: 100,
                slide: function( event, ui ) {
                    const val = Math.round( ui.value / (100/(count+1)) );
                    $this.find( '.wp-block-carkeek-blocks-expand-section__content' ).hide();
                    $sliderEl.find( 'label' ).removeClass('selected');
                    let $section = $this.find( '.wp-block-carkeek-blocks-expand-collapse-section.expand-collapse-default');
                    if (val !== 0 & val !== count+1) {
                        $section = $this.find( '.wp-block-carkeek-blocks-expand-collapse-section').eq( val );
                    }
                    $section.find( '.wp-block-carkeek-blocks-expand-section__content' ).show();
                    $sliderEl.find('label.label-' + (val-1) ).addClass('selected');
                }
              });


              $(this).find(".wp-block-carkeek-blocks-expand-collapse-section:not(.expand-collapse-default)").each(function( index ){
                  var label = $(this).find('.wp-block-carkeek-blocks-expand-section__header').text();
                  var spacing = 100/(count + 1);
                  var el = $('<label class="label-' + index + '">' + label+ '</label>').css( 'left' , ( ((index+1) * spacing) + '%'));
                  $sliderEl.append(el);
              });
        });


        $('.wp-block-carkeek-blocks-expand-section__header').click(function(){
            let openMe = true;
            const accordion = $(this).parents('.wp-block-carkeek-blocks-expand-collapse').attr('data-accordion');
            //if accordion we only open one at a time
            if ('true' == accordion) {
                if ($(this).parent().hasClass('open')) {
                    openMe = false;
                }
                $('.wp-block-carkeek-blocks-expand-collapse-section').removeClass('open');
                $('.wp-block-carkeek-blocks-expand-section__content').slideUp().attr('aria-expanded', false);
            }
            if (openMe) {
                $(this).parent().toggleClass('open');
                if ($(this).parent().hasClass('open')) {
                    $(this).siblings().slideDown().attr('aria-expanded', true);
                } else {
                    $(this).siblings().slideUp().attr('aria-expanded', false);
                }
            }
        });

    });
}( jQuery ) );





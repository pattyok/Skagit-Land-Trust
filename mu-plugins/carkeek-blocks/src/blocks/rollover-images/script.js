import jQuery from 'jquery';
import "./style.scss";

( function( $ ) {
    $(document).ready(function(){

        //set up the venn diagram zones
        $('.wp-block-carkeek-blocks-rollover-images.venn-diagram').each(function(){
            var innerblocks = $(this).find('.rollover-images__inner');
            var zones = ['venn-top', 'venn-bottom-left', 'venn-bottom-right'];
            var zonesEl = zones.map(function(val){
                var el = '<div class="venn-zone zone-' + val + '" data-target="' + val + '" tabindex="0"></div>';
                return el;
            });
            innerblocks.prepend(zonesEl);
        })

        //make the elements focusable
        $('.wp-block-carkeek-blocks-rollover-image').each(function(){
            $(this).attr('tabindex', '0');

        });

        $('.wp-block-carkeek-blocks-rollover-image').on("mouseenter", function(){
            $(this).focus();
        })

        //$('.wp-block-carkeek-blocks-rollover-image').on("mouseenter focus touchstart", function(){
        $('.wp-block-carkeek-blocks-rollover-image').on("focus", function(){
            if ($(this).hasClass('venn-default')) {
                return;
            }
            const content = $(this).find('.image-rollover__hover_text').html();
            const $parent = $(this).parents('.wp-block-carkeek-blocks-rollover-images')
            $parent.find('.rollover-images__default-content').hide();
            $parent.find('.rollover-images__hover-content').html(content).show();
        });

        $(document).on("mouseenter", '.wp-block-carkeek-blocks-rollover-images.venn-diagram .venn-zone', function(){
            $(this).focus();
        })

        $(document).on("focus", '.venn-zone', function(){
            var target=$(this).data('target');
            const content = $('.' + target).find('.image-rollover__hover_text').html();
            const $parent = $('.' + target).parents('.wp-block-carkeek-blocks-rollover-images');
            $parent.find('.wp-block-carkeek-blocks-rollover-image').removeClass('current');
            $('.' + target).addClass('current');
            $parent.find('.rollover-images__default-content').hide();
            $parent.find('.rollover-images__hover-content').html(content).show();
        });
    });
}( jQuery ) );

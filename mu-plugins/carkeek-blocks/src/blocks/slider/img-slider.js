import "./style.editor.scss";

//import "./img-slide";
import edit from "./img-slider-edit";
import save from "./img-slider-save";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks, InspectorControls, RichText } from "@wordpress/block-editor";
import { Path, SVG, PanelRow, PanelBody, RangeControl, ToggleControl, TextareaControl, RadioControl, TextControl } from "@wordpress/components";
/* we us the same resources as block slider just different output and some styles so keeping in same folder */
const attributes = {
    transitionType: {
        type: 'string',
        default: 'slide',
    },
    autoPlay: {
        type: 'boolean',
        default: false,
    },
    autoPlaySpeed: {
        type: 'number',
        default: 3000,
    },
    transitionSpeed: {
        type: 'number',
        default: 3000,
    },
    showDots: {
        type: 'boolean',
        default: false,
    },
    sliderType: {
        type: 'string',
        default: 'single'
    },
    slidesToShow: {
        type: 'number',
        default: 3
    },
    slidesToScroll: {
        type: 'number',
        default: 3
    },
    carousel: {
        type: 'boolean',
        default: false
    },
    fixHeight: {
        type: 'boolean',
        default: false
    },
    minHeight: {
        type: 'number',
        default: 300
    },
    maxHeight: {
        type: 'number',
        default: 600
    },
    textOverlay: {
        type: 'boolean',
        default: false
    },
    headerText: {
        type: 'string',
        source: 'html',
        selector: 'h1'
    },
    desktopText: {
        type: 'string',
        source: 'text',
        selector: '.desktop-text'
    }
}

registerBlockType("carkeek-blocks/image-slider", {
    title: __("Image Slider", "carkeek-blocks"),

    description: __(
        "Make a slideshow of images",
        "carkeek-blocks"
    ),

    icon: (<SVG
        xmlns="http://www.w3.org/2000/svg"
        height="24"
        viewBox="0 0 24 24"
        width="24">
            <Path d="M0 0h24v24H0V0z" fill="none"/>
            <Path d="M10 8v8l5-4-5-4zm9-5H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
        </SVG>),

    category: "widgets",

    supports: {
        html: false,
        align: ["wide", "full"]
    },
    attributes,

    keywords: [
        __("slider", "carkeek-blocks"),
        __("slide", "carkeek-blocks"),
        __("gallery", "carkeek-blocks")
    ],

    edit,
    save({ attributes, className } ) {
        const{ autoPlay, autoPlaySpeed, images, transitionSpeed, showDots, slidesToShow, headerText, desktopText, sliderType, slidesToScroll, fixHeight, minHeight, maxHeight, transitionType } = attributes;
        let style;
        if (fixHeight) {
            style = {
                minHeight: minHeight,
                maxHeight: maxHeight
            }
        }
        return (
                <div>
                    { (headerText || desktopText) &&
                    <div className="wp-block-carkeek-blocks-slider__slide-overlay">
                        <h1>{headerText}</h1> <p className="desktop-text">{desktopText}</p>
                    </div>
                    }
                    <div className={`wp-block-carkeek-blocks-slider__slide-wrapper fix-height-${fixHeight}`} style={style} data-minheight={minHeight} data-maxheight={maxHeight} data-showdots={showDots} data-autoplay={autoPlay} data-speed={autoPlaySpeed} data-type={sliderType} data-slides={slidesToShow} data-scroll={slidesToScroll} data-transition={transitionType} data-transitionspd={transitionSpeed}>

                    </div>
                </div>
        );
    }
});

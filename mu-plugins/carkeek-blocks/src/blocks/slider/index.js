import "./style.editor.scss";
import "./img-slider.js";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { Path, SVG, PanelBody, RangeControl, ToggleControl, RadioControl } from "@wordpress/components";

const attributes = {
    autoPlay: {
        type: 'boolean',
        default: true
    },
    autoPlaySpeed: {
        type: 'number',
        default: 3000,
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
    }
}

registerBlockType("carkeek-blocks/slider", {
    title: __("Block Slider", "carkeek-blocks"),

    description: __(
        "Make a slideshow of inner blocks",
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

    edit({ attributes, className, setAttributes }) {
        const { autoPlay, autoPlaySpeed, sliderType, slidesToScroll, slidesToShow, carousel } = attributes;
        return (
            <div className={`${className}`}>
                <InspectorControls>
                    <PanelBody>
                        <ToggleControl
                            label="Auto Play Slider"
                            checked={ autoPlay }
                            onChange={value =>
                                setAttributes({ autoPlay: value })
                            }
                        />
                        {autoPlay &&
                        <RangeControl
                            label={__("Speed in ms", "carkeek-blocks")}
                            value={autoPlaySpeed}
                            onChange={value =>
                                setAttributes({ autoPlaySpeed: value })
                            }
                            min={1000}
                            max={10000}
                        />
                        }
                        {/* code is ready, but need to do more styling if carousel is desired - its set to false by default so option is not displayed */}
                        {carousel &&
                        <RadioControl
                            label="Slider type"
                            selected={ sliderType }
                            options={ [
                                { label: 'Single Slides', value: 'single' },
                                { label: 'Carousel', value: 'carousel' },
                            ] }
                            onChange={ ( sliderType ) => { setAttributes( { sliderType } ) } }
                        />
                        }
                        {sliderType == 'carousel' &&
                            <RangeControl
                            label={__("Slides to Show", "carkeek-blocks")}
                            value={slidesToShow}
                            onChange={ ( slidesToShow ) => { setAttributes( { slidesToShow } ) } }
                            min={2}
                            max={6}
                        />
                        }
                        {sliderType == 'carousel' &&
                            <RangeControl
                            label={__("Slides to Scroll", "carkeek-blocks")}
                            value={slidesToScroll}
                            onChange={ ( slidesToScroll ) => { setAttributes( { slidesToScroll } ) } }
                            min={1}
                            max={6}
                        />
                        }
                    </PanelBody>
                </InspectorControls>
                <InnerBlocks
                    allowedBlocks={["core/group", "core/media-text", "core/cover"]}
                />
            </div>
        );
    },

    save({ attributes } ) {
        const{ autoPlay, autoPlaySpeed, slidesToShow, sliderType, slidesToScroll } = attributes;
        return (
            <div>
                <div className="wp-block-carkeek-blocks-slider__slide-wrapper" data-autoplay={autoPlay} data-speed={autoPlaySpeed} data-type={sliderType} data-slides={slidesToShow} data-scroll={slidesToScroll}>
                <InnerBlocks.Content />
                </div>
            </div>
        );
    }
});
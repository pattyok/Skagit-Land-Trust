import "./img-slide-edit";
import "./img-slide";

import { Component } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import {
    PanelRow,
    PanelBody,
    TextControl,
    RangeControl,
    ToggleControl,
    TextareaControl,
    RadioControl,
} from "@wordpress/components";
import { InnerBlocks,
    InspectorControls,
    RichText } from "@wordpress/block-editor";


class ImgSliderEdit extends Component {å

    render(){
        const {
            className,
            attributes,
            setAttributes,
        } = this.props;
        const {
            autoPlay,
            autoPlaySpeed,
            sliderType,
            textOverlay,
            transitionSpeed,
            showDots,
            headerText,
            desktopText,
            slidesToScroll,
            transitionType,
            slidesToShow,
            carousel,
            fixHeight,
            minHeight,
            maxHeight } = attributes;

        return(
        <>
            <InspectorControls>
        <PanelBody title={__("Slider Settings", "carkeek-blocks")}>
                <ToggleControl
                    label="Auto Play Slider"
                    checked={ autoPlay }
                    onChange={value =>
                        setAttributes({ autoPlay: value })
                    }
                />
                <RadioControl
                    label="Transition"
                    selected={ transitionType }
                    options={ [
                        { label: 'Slide', value: 'slide' },
                        { label: 'Fade', value: 'fade' },
                    ] }
                    onChange={ ( transitionType ) => { setAttributes( { transitionType } ) } }
                />
                {autoPlay &&
                <>
                <RangeControl
                    label={__("Time on each slide in ms", "carkeek-blocks")}
                    value={autoPlaySpeed}
                    onChange={value =>
                        setAttributes({ autoPlaySpeed: value })
                    }
                    min={1000}
                    max={10000}
                />

                </>
                }
                <RangeControl
                    label={__("Transition speed", "carkeek-blocks")}
                    value={transitionSpeed}
                    onChange={value =>
                        setAttributes({ transitionSpeed: value })
                    }
                    min={1000}
                    max={10000}
                />
                <ToggleControl
                    label="Show Dot Navigation"
                    checked={ showDots }
                    onChange={value =>
                        setAttributes({ showDots: value })
                    }
                />
                <ToggleControl
                    label="Fix Height"
                    help={__("If set to false, image sizes determine height", "carkeek-blocks")}
                    checked={ fixHeight }
                    onChange={value =>
                        setAttributes({ fixHeight: value })
                    }
                />
                {fixHeight &&
                <>
                <RangeControl
                    label={__("Minimum Height", "carkeek-blocks")}
                    value={minHeight}
                    onChange={value =>
                        setAttributes({ minHeight: value })
                    }
                    min={100}
                    max={1000}
                />
                <RangeControl
                    label={__("Max Height", "carkeek-blocks")}
                    value={maxHeight}
                    onChange={value =>
                        setAttributes({ maxHeight: value })
                    }
                    min={100}
                    max={1000}
                />
                </>
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

            <PanelBody title="Text Overlay settings" initialOpen={false}>
                <PanelRow>
                    Set a text overlay over all slides (to place different text over each slide use the Block Slider block).
                    </PanelRow>
                    <ToggleControl
                    label="Show Text Overlay"
                    checked={ textOverlay }
                    onChange={value =>
                        setAttributes({ textOverlay: value })
                    }
                />
                {textOverlay &&
                <>
                    <PanelRow>
                        <TextControl
                        label="Header"
                        value={ headerText }
                        onChange={ ( headerText ) => { setAttributes( { headerText } ) } }
                    />
                </PanelRow>
                <PanelRow>
                <TextareaControl
                        label="Content (Desktop)"
                        help="Enter some text"
                        value={ desktopText }
                        onChange={ ( desktopText ) => { setAttributes( { desktopText } ) } }
                    />
                </PanelRow>
                </>
}
            </PanelBody>

        </InspectorControls>

        {textOverlay &&
        <div className="wp-block-carkeek-blocks-slider__slide-overlay">
                <RichText
                tagName="h1" // The tag here is the element output and editable in the admin
                className={ className }
                value={ headerText} // Any existing content, either from the database or an attribute default
                formattingControls={ [] } // Allow the content to be made bold or italic, but do not allow other formatting options
                onChange={ ( headerText ) => setAttributes( { headerText } ) } // Store updated content as a block attribute
                placeholder={ __( 'Heading...' ) } // Display this text before any content has been added by the user
            />
            <RichText
                tagName="p" // The tag here is the element output and editable in the admin
                className={ className }
                value={ desktopText} // Any existing content, either from the database or an attribute default
                formattingControls={ [] } // Allow the content to be made bold or italic, but do not allow other formatting options
                onChange={ ( desktopText ) => setAttributes( { desktopText } ) } // Store updated content as a block attribute
                placeholder={ __( 'Text...' ) } // Display this text before any content has been added by the user
            />
            </div>
        }
        <InnerBlocks
            allowedBlocks={["carkeek-blocks/image-slide"]}
        />
        </>
        )
    }
}
export default ImgSliderEdit;
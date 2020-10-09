import "./style.editor.scss";
import icons from './icons';
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, RadioControl, CheckboxControl } from "@wordpress/components";

const attributes = {
    headerStyle: {
        type: 'string',
        default: 'h3'
    },
    accordionStyle: {
        type: 'boolean',
        default: false
    },
    rangeSliderStyle: {
        type: 'boolean',
        default: false
    }
}

registerBlockType("carkeek-blocks/expand-collapse", {
    title: __("Expand/Colllapse", "carkeek-blocks"),

    description: __(
        "Make an accordion of inner blocks",
        "carkeek-blocks"
    ),

    icon: {
        src: icons.accordion,
    },

    category: "widgets",

    supports: {
        html: false,
        align: ["wide", "full"]
    },
    attributes,

    keywords: [
        __("accordion", "carkeek-blocks"),
        __("expand", "carkeek-blocks"),
        __("collapse", "carkeek-blocks")
    ],

    edit({ attributes, className, setAttributes }) {
        const { headerStyle, accordionStyle, rangeSliderStyle } = attributes;

        return (
            <div className={`${className}`}>
                <InspectorControls>
                    <PanelBody>
                        <CheckboxControl
                            className="carkeek-accordion-style-label"
                            label="Use Accordion Style"
                            checked={ accordionStyle }
                            onChange={value =>
                                setAttributes({ accordionStyle: value })
                            }
                            help={
                                accordionStyle
                                    ? __(
                                        "Only one section will be expanded at a time",
                                        "carkeek-blocks"
                                    )
                                    : __(
                                        "Multiple sections can be open at a time",
                                        "carkeek-blocks"
                                    )
                            }
                        />
                        <CheckboxControl
                            className="carkeek-accordion-range-style-label"
                            label="Display as a Range Slider"
                            checked={ rangeSliderStyle }
                            onChange={value =>
                                setAttributes({ rangeSliderStyle: value })
                            }
                            help={
                                accordionStyle
                                    ? __(
                                        "Each section will display as a point on a range scale",
                                        "carkeek-blocks"
                                    )
                                    : __(
                                        "",
                                        "carkeek-blocks"
                                    )
                            }
                        />
                        <RadioControl
                            label="Section Header Style"
                            selected = {headerStyle}
                            options = { [
                                { label: 'h2', value: 'h2' },
                                { label: 'h3', value: 'h3' },
                                { label: 'h4', value: 'h4' },
                                { label: 'h5', value: 'h5' },
                                { label: 'h6', value: 'h6' },
                            ]}
                            onChange={value =>
                                setAttributes({ headerStyle: value })
                            }
                        />
                    </PanelBody>
                </InspectorControls>
                <InnerBlocks
                    allowedBlocks={["carkeek-blocks/expand-collapse-section"]}

                />
            </div>
        );
    },

    save({ attributes } ) {
        const{ accordionStyle, headerStyle, rangeSliderStyle} = attributes;
        const rangeStyle = rangeSliderStyle ? ' is-range-style' : '';
        const blockStyle = 'innerblock-headline-style-' + headerStyle + rangeStyle;
        return (
            <div data-accordion={accordionStyle} className={ blockStyle }>
                {rangeSliderStyle && (
                    <div className={'range-slider-element'}></div>
                )}
                <InnerBlocks.Content />

            </div>
        );
    }
});
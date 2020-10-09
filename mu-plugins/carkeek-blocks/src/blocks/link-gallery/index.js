import "./style.editor.scss";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, RangeControl } from "@wordpress/components";


const attributes = {
    columns: {
        type: "number",
        default: 3,
    },
};

registerBlockType("carkeek-blocks/link-gallery", {
    title: __("Link Gallery", "carkeek-blocks"),

    description: __(
        "Block showing a collection of linked images",
        "carkeek-blocks"
    ),

    icon: "grid-view",

    category: "widgets",

    supports: {
        html: false,
        align: ["wide", "full"]
    },

    keywords: [
        __("tiles", "carkeek-blocks"),
        __("columns", "carkeek-blocks"),
        __("links", "carkeek-blocks"),
        __("logos", "carkeek-blocks"),
        __("gallery", "carkeek-blocks")
    ],

    attributes,

    edit({ className, attributes, setAttributes }) {
        const { columns } = attributes;
        const updateColumns = ( value ) => {
            setAttributes( {
                columns: value,
            });
        }
        return (
            <div className={`${className} has-${columns}-columns`}>
                <InspectorControls>
                    <PanelBody>
                        <RangeControl
                            label={__("Columns", "carkeek-blocks")}
                            value={columns}
                            onChange={ updateColumns }
                            min={1}
                            max={6}
                        />
                    </PanelBody>
                </InspectorControls>
                <InnerBlocks
                    allowedBlocks={["core/image"]}
                    orientation="horizontal"
                />
            </div>
        );
    },

    save( { attributes } ) {
        const { columns } = attributes;
        return (
            <div className={`has-${columns}-columns`}>
                <InnerBlocks.Content />
            </div>
        );
    }
});
import "./style.editor.scss";
import "./parent";
import icons from './icons';
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import edit from "./edit";
import { InnerBlocks, RichText } from "@wordpress/block-editor";

const attributes = {
    rolloverText: {
        type: "string"
    },
    rolloverHeadline: {
        type: "string"
    },
    inheritedHeaderStyle: {
        type: "string"
    }
};

registerBlockType("carkeek-blocks/rollover-image", {
    title: __("Rollover Image", "carkeek-blocks"),

    description: __(
        " Block that displays a link with an image background and hover state. ",
        "carkeek-blocks"
    ),

    icon: {
        src: icons.swap,
    },

    parent: ["carkeek-blocks/rollover-images"],

    supports: {
        reusable: false,
        html: false
    },

    category: "widgets",

    keywords: [__("image", "carkeek-blocks"), __("rollover", "carkeek-blocks")],

    attributes,

    save: ({ attributes }) => {
        const {
            rolloverText,
            rolloverHeadline
        } = attributes;
        return (
            <div>
                <InnerBlocks.Content />
                <div className="image-rollover__hover_text">
                    <RichText.Content tagName={ 'div' } className={ 'inner-block-headline' } value={ rolloverHeadline } />
                    <RichText.Content tagName={ 'p' } className={ 'inner-block-content' } value={ rolloverText } />
                </div>
            </div>
        );
    },

    edit
});

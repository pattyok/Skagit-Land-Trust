import "./style.editor.scss";
import "./parent";
import icons from './icons';
import edit from "./edit";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks } from "@wordpress/block-editor";

const attributes = {
    title: {
        type: "string"
    },
    inheritedHeaderStyle: {
        type: "string"
    }
}

registerBlockType("carkeek-blocks/expand-collapse-section", {
    title: __("Expand Collapse Section", "carkeek-blocks"),

    description: __(
        "Build an Accordion with inner blocks",
        "carkeek-blocks"
    ),

    icon: {
        src: icons.dropdown,
    },

    category: "widgets",

    attributes,

    parent: ["carkeek-blocks/expand-collapse"],

    keywords: [
        __("accordion", "carkeek-blocks"),
        __("expand", "carkeek-blocks"),
        __("collapse", "carkeek-blocks")
    ],

    edit,

    save({ attributes } ) {
        const{ title } = attributes;
        return (
            <div>
                <div className={`wp-block-carkeek-blocks-expand-section__header inner-block-headline`}>{title}</div>
                <div className="wp-block-carkeek-blocks-expand-section__content" aria-expanded="false">
                    <InnerBlocks.Content />
                </div>
            </div>
        );
    }
});
import "./styles.editor.scss";
import edit from "./edit";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

const attributes = {
    numberOfPosts: {
        type: "number",
        default: 3
    },
    postTypeSelected: {
        type: "string"
    },
    displayPostExcerpt: {
        type: "boolean",
        default: true,
    },
    postLayout: {
        type: "string",
        default: "grid"
    },
    excerptLength: {
        type: "number",
        default: 25
    },
    filterByTaxonomy: {
        type: "boolean",
        default: false
    },
    taxonomySelected: {
        type: "string"
    },
    taxTermsSelected: {
        type: "string"
    },
    hideIfEmpty: {
        type: "boolean",
        default: true
    },
    emptyMessage: {
        type: "string"
    },
    headline: {
        type: "string"
    },
    headlineLevel: {
        type: "number",
        default: '2'
    }
};

registerBlockType("carkeek-blocks/custom-archive", {
    title: __("Custom Post Type Archive", "carkeek-blocks"),
    description: __(
        "Block showing the latest items by post type.",
        "carkeek-blocks"
    ),
    icon: "book-alt",
    category: "widgets",
    edit: edit,
    attributes: attributes,
    supports: {
        align: ["wide", "full"]
    },
    save() {
        return null;
    }
});

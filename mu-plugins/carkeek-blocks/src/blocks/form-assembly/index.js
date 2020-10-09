import "./styles.editor.scss";
import edit from "./edit";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

const attributes = {
    formId: {
        type: "string",
    },
};

registerBlockType("carkeek-blocks/form-assembly", {
    title: __("Form Assembly Block", "carkeek-blocks"),
    description: __(
        "Display a Form Assembly Form",
        "carkeek-blocks"
    ),
    icon: "feedback",
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

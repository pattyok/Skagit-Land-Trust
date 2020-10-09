/**
 * Internal dependencies
 */
import "./styles.editor.scss";
import PageHeaderSettings from "./settings";

/**
 * WordPress dependencies
 */
import { registerPlugin } from "@wordpress/plugins";
import { registerBlockCollection } from "@wordpress/blocks";

registerPlugin("carkeek-blocks-pageheader", {
    icon: false,
    render: PageHeaderSettings
});



registerBlockCollection("carkeek-blocks", {
    title: "Carkeek Blocks",
    icon: "wordpress"
});



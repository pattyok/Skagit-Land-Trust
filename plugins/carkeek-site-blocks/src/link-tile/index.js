import "./editor.scss";
import "./style.scss";

import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import edit from "./edit";
import save from "./save";

import metadata from "./block.json";

registerBlockType(metadata.name, {
    edit,
    save
});

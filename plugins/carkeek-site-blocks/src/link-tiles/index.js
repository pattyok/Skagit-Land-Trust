import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";
import metadata from './block.json';
import "./style.scss";
import "./editor.scss";
import deprecated from "./deprecated";
import edit from "./edit";

registerBlockType(metadata.name, {

    edit,
	deprecated,

    save(props) {
		const { attributes } = props;
		const { imageAspectRatio } = attributes;
		const classes = `ck-link-tiles--${imageAspectRatio} columns-${props.attributes.columns} columns-tablet-${props.attributes.columnsTablet} columns-mobile-${props.attributes.columnsMobile}`;
        return (
            <div { ...useBlockProps.save({className: classes}) }>
                <InnerBlocks.Content />
            </div>
        );
    }
});

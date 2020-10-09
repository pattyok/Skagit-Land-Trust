import { Path, SVG } from '@wordpress/components';
import { registerBlockType, createBlock } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, RangeControl, RadioControl } from "@wordpress/components";

const attributes = {
    columns: {
        type: "number",
        default: 3
    },
    layout: {
        type: "string",
        default: "grid"
    }
};

registerBlockType("carkeek-blocks/team-members", {
    title: __("Team Members", "carkeek-blocks"),

    description: __("Block showing a Team Members.", "carkeek-blocks"),

    icon: (
        <SVG xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="black"
            width="48px"
            height="48px">
                <Path
                d="M0 0h24v24H0V0z"
                fill="none"/>
                <Path
                d="M12 12c1.65 0 3-1.35 3-3s-1.35-3-3-3-3 1.35-3 3 1.35 3 3 3zm0-4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm6 8.58c0-2.5-3.97-3.58-6-3.58s-6 1.08-6 3.58V18h12v-1.42zM8.48 16c.74-.51 2.23-1 3.52-1s2.78.49 3.52 1H8.48zM19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"
                />
        </SVG>
    ),

    category: "widgets",

    supports: {
        html: false,
        align: ["wide", "full"]
    },

    transforms: {
        from: [
            {
                type: "block",
                blocks: ["core/gallery"],
                transform: ({ columns, images }) => {
                    let inner = images.map(({ alt, id, url }) =>
                        createBlock("carkeek-blocks/team-member", {
                            alt,
                            id,
                            url
                        })
                    );
                    return createBlock(
                        "carkeek-blocks/team-members",
                        {
                            columns: columns
                        },
                        inner
                    );
                }
            },
            {
                type: "block",
                blocks: ["core/image"],
                isMultiBlock: true,
                transform: attributes => {
                    let inner = attributes.map(({ alt, id, url }) =>
                        createBlock("carkeek-blocks/team-member", {
                            alt,
                            id,
                            url
                        })
                    );
                    return createBlock(
                        "carkeek-blocks/team-members",
                        {
                            columns: 3
                        },
                        inner
                    );
                }
            }
        ]
    },

    keywords: [
        __("team", "carkeek-blocks"),
        __("member", "carkeek-blocks"),
        __("person", "carkeek-blocks")
    ],

    attributes,

    edit({ className, attributes, setAttributes }) {
        const { columns, layout } = attributes;
        return (
            <div className={`${className} has-${columns}-columns is-${layout}-style`}>
                <InspectorControls>
                    <PanelBody>
                        <RangeControl
                            label={__("Columns", "carkeek-blocks")}
                            help={__("With list style layout, this determines the width of the column.", "carkeek-blocks")}
                            value={columns}
                            onChange={columns => setAttributes({ columns })}
                            min={1}
                            max={6}
                        />
                        <RadioControl
                        label="Layout"
                        selected={layout}
                        options={[
                                {label: "Grid", value:'grid'},
                                {label: "List", value:'list'},
                            ]}
                        onChange={layout => setAttributes({ layout })}
                        />
                    </PanelBody>
                </InspectorControls>
                <InnerBlocks
                    orientation = {layout == 'grid' ? 'horizontal' : 'vertical'}
                    allowedBlocks={["carkeek-blocks/team-member"]}
                />
            </div>
        );
    },

    save({ attributes }) {
        const { columns, layout } = attributes;
        return (
            <div className={`has-${columns}-columns is-${layout}-style`} data-layout={layout}>
                <InnerBlocks.Content />
            </div>
        );
    }
});

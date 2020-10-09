import "./style.editor.scss";

import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import edit from "./img-slide-edit";
import classnames from "classnames";
import icons from './icons';

const attributes = {
    imgId: {
        type: "number"
    },
    imgUrl: {
        type: "string"
    },
    focalPoint: {
        type: "object"
    }

};

registerBlockType("carkeek-blocks/image-slide", {
    title: __("Link Tile", "carkeek-blocks"),

    description: __(
        " Img slide for the image slider, offers focal point option ",
        "carkeek-blocks"
    ),

    icon: icons.img,

    parent: ["carkeek-blocks/image-slider"],

    supports: {
        reusable: false,
        html: false
    },

    category: "widgets",

    keywords: [__("image", "carkeek-blocks"), __("slide", "carkeek-blocks")],

    attributes,

    save: ({ attributes }) => {
        const {
            imgUrl,
            focalPoint
        } = attributes;
        const imageStyle = {
            backgroundImage: `url( ${imgUrl} )`
        };
        if (focalPoint) {
            imageStyle.backgroundPosition = `${focalPoint.x *
                100}% ${focalPoint.y * 100}%`;
        }
        return (
            <div>
                {imgUrl && (
                        <div
                            style={imageStyle}
                            className={
                                "wp-block-carkeek-blocks-image-slide__img"
                            }
                        >
                        </div>
                )}
            </div>
        );
    },

    edit
});

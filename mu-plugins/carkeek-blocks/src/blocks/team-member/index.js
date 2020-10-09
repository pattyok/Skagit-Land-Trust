import "./style.editor.scss";
import "./parent";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import edit from "./edit";
import { RichText } from "@wordpress/editor";


const attributes = {
    name: {
        type: "string",
        source: "html",
        selector: ".wp-block-carkeek-blocks-team-member__name"
    },
    title: {
        type: "string",
        source: "html",
        selector: ".wp-block-carkeek-blocks-team-member__title"
    },
    details: {
        type: "string",
        source: "html",
        selector: ".wp-block-carkeek-blocks-team-member__details"
    },
    id: {
        type: "number"
    },
    alt: {
        type: "string",
        source: "attribute",
        selector: "img",
        attribute: "alt",
        default: ""
    },
    url: {
        type: "string",
        source: "attribute",
        selector: "img",
        attribute: "src"
    },
    email: {
        type: "string",
    },
    emailLabel: {
        type: "string",
        default: "Send an email"
    }
};

registerBlockType("carkeek-blocks/team-member", {
    title: __("Team Member", "carkeek-blocks"),

    description: __(" Block showing a Team Member. ", "carkeek-blocks"),

    icon: "admin-users",

    parent: ["carkeek-blocks/team-members"],

    supports: {
        reusable: false,
        html: false
    },

    category: "widgets",

    keywords: [
        __("team", "carkeek-blocks"),
        __("member", "carkeek-blocks"),
        __("person", "carkeek-blocks"),
        __("staff", "carkeek-blocks")
    ],

    attributes,

    deprecated: [{
        attributes,
        save: ({ attributes }) => {
            const { title, name, url, alt, id, details, email, emailLabel } = attributes;
            return (
                <div>
                    <div className="wp-block-carkeek-blocks-team-member__initial">
                        {url && (
                            <div className="wp-block-carkeek-blocks-team-member__image">
                                <img
                                    src={url}
                                    alt={alt}
                                    className={id ? `wp-image-${id}` : null}
                                />
                            </div>
                        )}
                        {name && (
                            <RichText.Content
                                className={"wp-block-carkeek-blocks-team-member__name"}
                                tagName="div"
                                value={name}
                            />
                        )}
                        {title && (
                            <RichText.Content
                                className={"wp-block-carkeek-blocks-team-member__title"}
                                tagName="p"
                                value={title}
                            />
                        )}
                     </div>
                     <div className="wp-block-carkeek-blocks-team-member__additional">
                        {details && (
                            <RichText.Content
                                className={"wp-block-carkeek-blocks-team-member__details"}
                                tagName="p"
                                value={details}
                            />
                        )}
                        {email &&(
                            <a className="{button is-style-cta}" href="mailto:{email}">{emailLabel}</a>
                        )}
                    </div>
                </div>
            );
        },
    }],

    save: ({ attributes }) => {
        const { title, name, url, alt, id, details, email, emailLabel } = attributes;
        return (
            <div>
                <div className="wp-block-carkeek-blocks-team-member__initial">
                    {url && (
                        <div className="wp-block-carkeek-blocks-team-member__image">
                            <img
                                src={url}
                                alt={alt}
                                className={id ? `skip-lazy wp-image-${id}` : 'skip-lazy'}
                            />
                        </div>
                    )}

                    <RichText.Content
                        className={"wp-block-carkeek-blocks-team-member__name"}
                        tagName="div"
                        value={name}
                    />

                    {title && (
                        <RichText.Content
                            className={"wp-block-carkeek-blocks-team-member__title"}
                            tagName="p"
                            value={title}
                        />
                    )}
                 </div>
                 <div className="wp-block-carkeek-blocks-team-member__additional">
                    {details && (
                        <RichText.Content
                            className={"wp-block-carkeek-blocks-team-member__details"}
                            tagName="p"
                            value={details}
                        />
                    )}
                    {email &&(
                        <a className={"button is-style-cta"} href={`mailto:${email}`}>{emailLabel}</a>
                    )}
                </div>
            </div>
        );
    },

    edit
});

import { Component } from "@wordpress/element";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { __ } from "@wordpress/i18n";
import { CheckboxControl, TextControl, ColorPalette } from "@wordpress/components";
import { select, withSelect, withDispatch } from "@wordpress/data";
import { compose } from "@wordpress/compose";
import { getColorObjectByColorValue, getColorObjectByAttributeValues } from "@wordpress/block-editor";


class PageHeaderSettings extends Component {
    constructor() {
        super(...arguments);

        this.initialize = this.initialize.bind(this);
    }

    componentDidMount() {
        this.initialize();
    }

    componentDidUpdate() {
        this.initialize();
    }

    initialize() {
        const { postmeta } = this.props;

        const titleBlock = document.querySelector(".editor-post-title__block");

        if (titleBlock) {
			const isTitleHidden = typeof postmeta !== 'undefined' && typeof postmeta._carkeekblocks_title_hidden !== 'undefined' ? postmeta._carkeekblocks_title_hidden : false;


            const bodyClass = isTitleHidden
                ? "carkeek-blocks-title-hidden"
                : "carkeek-blocks-title-visible";

            //remove existing class
            if (isTitleHidden) {
                document.body.classList.remove("carkeek-blocks-title-visible");
            } else {
                document.body.classList.remove("carkeek-blocks-title-hidden");
            }

            document.body.classList.add(bodyClass);
        }
    }
    render() {
        const {
			onToggleTitle,
            onToggleImage,
            onbylineChange,
            onColorChange,
			postmeta,
            posttype,
            featuredImage,
            colors
        } = this.props;
		if ( [ 'wp_block' ].includes( posttype ) ) {
			return false;
        }
        let byLineField;
        if ( [ 'post' ].includes( posttype ) && typeof postmeta !== 'undefined' ) {
            const byline = typeof postmeta.byline !== 'undefined' ? postmeta.byline : '';
			byLineField = <TextControl
                            value={byline}
                            label={__("Post Byline", "carkeek-blocks")}
                            onChange={value => onbylineChange(value)}
                        />;
        }
		const isTitleHidden = typeof postmeta !== 'undefined' && typeof postmeta._carkeekblocks_title_hidden !== 'undefined' ? postmeta._carkeekblocks_title_hidden : false;
		const isImageHidden = typeof postmeta !== 'undefined' && typeof postmeta._carkeekblocks_featuredimage_hidden !== 'undefined' ? postmeta._carkeekblocks_featuredimage_hidden : false;
		let hideImageCheckbox;
        if (featuredImage) {
            hideImageCheckbox = (
                <CheckboxControl
                    className="carkeek-hide-featured-image-label"
                    label="Hide Featured Image"
                    checked={ isImageHidden }
                    onChange={ onToggleImage }
                    help={
                        isImageHidden
                            ? __(
                                  "The Featured Image is hidden on the rendered page.",
                                  "carkeek-blocks"
                              )
                            : __(
                                  "The Featured Image is visible on the rendered page.",
                                  "carkeek-blocks"
                              )
                    }
                />
            );
        }
        let colorField;
        if ( [ 'protected_farms', 'tribe_events' ].includes( posttype ) && typeof postmeta !== 'undefined' ) {
            const selectedColor = typeof postmeta._carkeekblocks_archive_background_color !== 'undefined' ? getColorObjectByAttributeValues(colors, postmeta._carkeekblocks_archive_background_color): '';
            colorField = (
                <PluginDocumentSettingPanel
                    name="bgcolor-panel"
                    title="Background Color"
                    className="bgcolor-panel"
                >
                { __( 'Set a background color that will be used in certain views of this item.' ) }
                <ColorPalette
                    title={__("Color Settings", "carkeek-blocks")}
                    colors= { colors }
                    value= { selectedColor.color }
                    onChange= { onColorChange }
                    disableCustomColors= {true}
                ></ColorPalette>
                </PluginDocumentSettingPanel>
            );
        }

        return (
            <>
            <PluginDocumentSettingPanel
                name="page-header-settings-panel"
                title="Page Header Settings"
                className="page-header-settings-panel"
            >
                <CheckboxControl
                    className="carkeek-hide-title-label"
                    label={__("Hide " + posttype + " Title", "carkeek-blocks")}
                    checked={isTitleHidden}
                    onChange={ onToggleTitle }
                    help={
                        isTitleHidden
                            ? __(
                                  "Title is hidden on the rendered page.",
                                  "carkeek-blocks"
                              )
                            : null
                    }
                />
                {hideImageCheckbox}
                {byLineField}

            </PluginDocumentSettingPanel>
            {colorField}
            </>
        );
    }
}

export default compose(
    withSelect( () => {
        return {
            postmeta: select("core/editor").getEditedPostAttribute("meta"),
            featuredImage: select("core/editor").getEditedPostAttribute(
                "featured_media"
            ),
            posttype: select("core/editor").getEditedPostAttribute("type"),
            colors: select("core/block-editor").getSettings().colors,
        };
    }),
    withDispatch( ( dispatch, ownProps ) => {
		let hideTitle;
		if ( typeof ownProps.postmeta !== 'undefined' && typeof ownProps.postmeta._carkeekblocks_title_hidden !== 'undefined' ) {
			hideTitle = ownProps.postmeta._carkeekblocks_title_hidden ;
		}
		let hideImage;
		if ( typeof ownProps.postmeta !== 'undefined' && typeof ownProps.postmeta._carkeekblocks_featuredimage_hidden !== 'undefined' ) {
			hideImage = ownProps.postmeta._carkeekblocks_featuredimage_hidden ;
        }

        return {
            onToggleTitle() {
                dispatch("core/editor").editPost({
                    meta: { _carkeekblocks_title_hidden: ! hideTitle }
				});
            },
            onToggleImage() {
                dispatch("core/editor").editPost({
                    meta: {
                        _carkeekblocks_featuredimage_hidden: ! hideImage
                    }
                });
            },
            onColorChange(bgcolor) {
                const selected = getColorObjectByColorValue(ownProps.colors, bgcolor);
                dispatch("core/editor").editPost({
                    meta: {
                        _carkeekblocks_archive_background_color: selected.slug
                    }
                });
            },
            onbylineChange(byline) {
                dispatch("core/editor").editPost({
                    meta: {
                        byline: byline
                    }
                });
            }
        };
    })
)(PageHeaderSettings);

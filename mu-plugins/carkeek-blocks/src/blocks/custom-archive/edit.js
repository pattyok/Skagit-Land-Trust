import classnames from "classnames";
import { get, invoke } from "lodash";

import { Component, RawHTML } from "@wordpress/element";
import { withSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import {
    RangeControl,
    PanelBody,
    ToggleControl,
    RadioControl,
    Spinner,
    Placeholder,
    SelectControl,
    TextareaControl
} from "@wordpress/components";
import { InspectorControls, RichText } from "@wordpress/block-editor";

class CustomArchiveEdit extends Component {
    onChangeNumberOfPosts = numberOfPosts => {
        this.props.setAttributes({ numberOfPosts });
    };

    onChangePostType = postTypeSelected => {
        this.props.setAttributes({ postTypeSelected });
    };

    onChangeTaxonomy = taxonomySelected => {
        console.log("taxonomy changed");
        this.props.setAttributes({ taxonomySelected });
    };

    onChangeTaxFilter = value => {
        this.props.setAttributes({ filterByTaxonomy: value });
        console.log(this.props.taxonomies);
        if (Array.isArray(this.props.taxonomies) && this.props.taxonomies.length == 1) {
            this.props.setAttributes({ taxonomySelected : this.props.taxonomies[0].slug});
        }
    }


    onSelectTerms = terms => {
        this.props.setAttributes({ taxTermsSelected : terms.join(",") });
    };

    render() {

        const {
            posts,
            taxonomies,
            taxTerms,
            postTypes,
            className,
            attributes,
            setAttributes,
            isSelected,
            taxSelected,
        } = this.props;
        const {
            numberOfPosts,
            displayPostExcerpt,
            excerptLength,
            postLayout,
            postsToShow,
            postTypeSelected,
            filterByTaxonomy,
            taxTermsSelected,
            taxonomySelected,
            hideIfEmpty,
            emptyMessage,
            headline,
            headlineLevel,
        } = attributes;
        const headlineStyle = 'h' + headlineLevel;
        const icons = {
            pin: (
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="black"
                    width="18px"
                    height="18px"
                >
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M22 13h-8v-2h8v2zm0-6h-8v2h8V7zm-8 10h8v-2h-8v2zm-2-8v6c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V9c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2zm-1.5 6l-2.25-3-1.75 2.26-1.25-1.51L3.5 15h7z" />
                </svg>
            ),
        };
        const postTypeSelect = (
            <SelectControl
                label={__("Post Type", "carkeek-blocks")}
                onChange={this.onChangePostType}
                options={
                    postTypes &&
                    postTypes.map(type => ({
                        value: type.slug,
                        label: type.name
                    }))
                }
                value={postTypeSelected}
            />
        );

        const taxonomySelect = (
            <>
                <ToggleControl
                    label={__("Filter by Taxonomy")}
                    checked={filterByTaxonomy}
                    onChange={this.onChangeTaxFilter}
                />
                {filterByTaxonomy && (
                    <>
                        <SelectControl
                            label={__("Select Taxonomy", "carkeek-blocks")}
                            onChange={this.onChangeTaxonomy}
                            options={
                                taxonomies &&
                                taxonomies.map(type => ({
                                    value: type.slug,
                                    label: type.name
                                }))
                            }
                            value={taxonomySelected}
                        />
                        {taxonomySelected && (
                            <SelectControl
                                multiple
                                label={__("Select Terms", "carkeek-blocks")}
                                onChange={this.onSelectTerms}
                                options={
                                    taxTerms &&
                                    taxTerms.map(type => ({
                                        value: type.id,
                                        label: type.name
                                    }))
                                }
                                value={taxTermsSelected && taxTermsSelected.split(',')}
                            />
                        )}
                    </>
                )}
            </>
        );
        const inspectorControls = (
            <InspectorControls>
                <PanelBody title={__("Posts Settings", "carkeek-blocks")}>
                    {postTypeSelect}
                    {postTypeSelected && (
                       <> {taxonomySelect} </>
                    )}
                    <RangeControl
                        label={__("Number of Posts", "carkeek-blocks")}
                        value={numberOfPosts}
                        help={__("Select -1 to show all")}
                        onChange={this.onChangeNumberOfPosts}
                        min={-1}
                        max={21}
                    />

                </PanelBody>
                <PanelBody title={__("Layout", "carkeek-blocks")}>
                <RadioControl
                    label={__("Layout Style")}
                    selected={postLayout}
                    options={[
                        { label: __("Grid"), value: "grid" },
                        { label: __("List"), value: "list"},
                        { label: __("Link Tile"), value: "link-tile"}
                    ]}
                    onChange={value =>
                        setAttributes({
                            postLayout: value
                        })
                    }
                />
                <RangeControl
                        label={__("Heading Size", "carkeek-blocks")}
                        value={headlineLevel}
                        onChange={value =>
                            setAttributes({ headlineLevel: value })
                        }
                        min={2}
                        max={6}
                    />

                    <ToggleControl
                        label={__("Include Exerpt")}
                        checked={displayPostExcerpt}
                        onChange={value =>
                            setAttributes({ displayPostExcerpt: value })
                        }
                    />


                    {displayPostExcerpt && (
                            <RangeControl
                                label={__("Max number of words in excerpt")}
                                value={excerptLength}
                                onChange={value =>
                                    setAttributes({ excerptLength: value })
                                }
                                min={10}
                                max={75}
                            />
                        )}

                    <ToggleControl
                        label={__("Hide Block if Empty")}
                        checked={hideIfEmpty}
                        onChange={value =>
                            setAttributes({ hideIfEmpty: value })
                        }
                    />
                    { !hideIfEmpty&& (
                        <TextareaControl
                            label={__("Text to Display if Empty")}
                            value={emptyMessage}
                            onChange={value =>
                                setAttributes({ emptyMessage: value })
                            }
                        />
                    )}

                </PanelBody>
            </InspectorControls>
        );

        const hasPosts = Array.isArray(posts) && posts.length;

        if (!hasPosts) {
            const message = hideIfEmpty ? __("No Posts Found: Block will not display") : emptyMessage;
            const noPostMessage =
                typeof postTypeSelected !== "undefined"
                    ? message
                    : __("Select a Post Type from the Block Settings");
            const showHeadline = isSelected || (headline && ! hideIfEmpty) ? true : false;
            return (
                <>
                    {inspectorControls}
                    { showHeadline && (
                    <RichText
                        tagName={ headlineStyle }
                        value={ headline }
                        onChange={ ( headline ) => setAttributes( { headline } ) }
                        placeholder={ __('Heading...')}
                        formattingControls={ [ ] }
                    />
                   ) }

                    <Placeholder icon={icons.pin} label={ headline ? headline : __("Latest Posts")}>
                        {!Array.isArray(posts) ? <Spinner /> : noPostMessage}
                    </Placeholder>
                </>
            );
        }

        //removing posts should be instant
        const displayPosts =
            posts.length > postsToShow ? posts.slice(0, postsToShow) : posts;


        return (
            <>
                {inspectorControls}
                <div
                    className={classnames(className, {
                        "wp-block-carkeek-blocks-custom-archive": true,
                        "is-grid": postLayout === "grid",
                        "is-list": postLayout === "list",
                        "is-link-tile": postLayout === "link-tile"
                    })}
                >
                   { (isSelected || headline) && (
                    <RichText
                        tagName={ headlineStyle }
                        value={ headline }
                        onChange={ ( headline ) => setAttributes( { headline } ) }
                        placeholder={ __('Heading...')}
                        formattingControls={ [ ] }
                    />
                   ) }
                    <div className="wp-block-carkeek-blocks-custom-archive__list">
                        {displayPosts.map(post => {
                            const titleTrimmed = invoke(post, [
                                "title",
                                "rendered",
                                "trim"
                            ]);
                            let excerpt = post.excerpt ? post.excerpt.rendered : '';

                            const excerptElement = document.createElement(
                                "div"
                            );
                            excerptElement.innerHTML = excerpt;

                            excerpt =
                                excerptElement.textContent ||
                                excerptElement.innerText ||
                                "";
                            const imageSourceUrl = post.featuredImageSourceUrl;

                            const imageClasses = classnames({
                                "wp-block-carkeek-blocks-custom-archive__featured-image": true
                            });

                            const postExcerpt = (
                                <>
                                    {excerpt
                                        .trim()
                                        .split(" ", excerptLength)
                                        .join(" ")}
                                    {/* translators: excerpt truncation character, default …  */}
                                </>
                            );

                            return (
                                <div key={post.id}>

                                        <div className={imageClasses}>
                                            {imageSourceUrl && (
                                                <img
                                                    src={imageSourceUrl}
                                                    alt=""
                                                />
                                            )}
                                        </div>
                                        <div className="wp-block-carkeek-blocks-custom-archive__content">
                                        <div className="wp-block-carkeek-blocks-custom-archive__title">
                                            {titleTrimmed ? (
                                                <RawHTML>
                                                    {titleTrimmed}
                                                </RawHTML>
                                            ) : (
                                                __("(no title)")
                                            )}
                                       </div>

                                    {displayPostExcerpt &&(
                                        <div className="wp-block-carkeek-blocks-custom-archive__post-excerpt">
                                            {postExcerpt}
                                        </div>
                                    )}
                                    </div>
                                </div>
                            )
                        })}
                    </div>
                    <p style={{textAlign: 'center', fontSize: '10px'}}>(Showing Recent {postTypeSelected}: Posts rendered here may differ than the actual query.)</p>

                </div>
            </>
        );
    }
}

export default withSelect((select, props) => {

    const { attributes } = props;
    const { numberOfPosts, postTypeSelected, taxonomySelected, taxTermsSelected, filterByTaxonomy } = attributes;
    const { getEntityRecords, getMedia, getPostTypes, getTaxonomies } = select("core");
    const taxTerms = getEntityRecords('taxonomy', taxonomySelected, { per_page: -1 } );
    let query = { per_page: numberOfPosts };
    if (filterByTaxonomy && taxonomySelected && taxTermsSelected) {
        query[taxonomySelected] = taxTermsSelected;
    }
    const latestPosts = getEntityRecords("postType", postTypeSelected, query);
    let taxonomies = getTaxonomies();
    taxonomies = !Array.isArray(taxonomies)
            ? taxonomies
            : taxonomies.filter(tax => tax.types.includes(postTypeSelected));


    return {
        postTypes: getPostTypes(),
        taxonomies: taxonomies,
        taxSelected:  Array.isArray(taxonomies) && taxonomies.length == 1 ? taxonomies[0] : taxonomySelected,
        taxTerms: taxTerms,
        posts: !Array.isArray(latestPosts)
            ? latestPosts
            : latestPosts.map(post => {
                  if (post.featured_media) {
                      const image = getMedia(post.featured_media);
                      let url = get(
                          image,
                          ["media_details", "sizes", "large", "sourc_url"],
                          null
                      );
                      if (!url) {
                          url = get(image, "source_url", null);
                      }
                      return { ...post, featuredImageSourceUrl: url };
                  }
                  return post;
              })
    };
})(CustomArchiveEdit);

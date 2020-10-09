import { Component } from "@wordpress/element";
import {
    MediaPlaceholder,
    BlockControls,
    MediaUpload,
    MediaUploadCheck,
    InspectorControls,
    URLInput,
    PanelColorSettings,
    withColors
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { isBlobURL } from "@wordpress/blob";
import {
    Spinner,
    withNotices,
    Toolbar,
    IconButton,
    PanelBody,
    SelectControl,
    TextControl,
    FocalPointPicker
} from "@wordpress/components";
import { withSelect } from "@wordpress/data";
import { compose } from "@wordpress/compose";
import classnames from "classnames";

class ImgSlideEdit extends Component {
    state = {
        selectedLink: null
    };

    componentDidMount() {
        const { attributes, setAttributes } = this.props;
        const { imgUrl, imgId } = attributes;
        if (imgUrl && isBlobURL(imgUrl) && !imgId) {
            setAttributes({
                imgUrl: "",
                alt: ""
            });
        }
    }
    componentDidUpdate(prevProps) {
        if (prevProps.isSelected && !this.props.isSelected) {
            this.setState({
                selectedLink: null
            });
        }
    }

    onSelectImage = ({ id, url }) => {
        this.props.setAttributes({
            imgId: id,
            imgUrl: url
        });
    };
    onUploadError = message => {
        const { noticeOperations } = this.props;
        noticeOperations.createErrorNotice(message);
    };
    removeImage = () => {
        this.props.setAttributes({
            imgId: null,
            imgUrl: ""
        });
    };
    onImageSizeChange = imgUrl => {
        this.props.setAttributes({
            imgUrl
        });
    };
    getImageSizes() {
        const { image, imageSizes } = this.props;
        if (!image) return [];
        let options = [];
        const sizes = image.media_details.sizes;
        for (const key in sizes) {
            const size = sizes[key];
            const imageSize = imageSizes.find(size => size.slug === key);
            if (imageSize) {
                options.push({
                    label: imageSize.name,
                    value: size.source_url
                });
            }
        }
        return options;
    }

    render() {
        //console.log(this.props);
        const {
            className,
            attributes,
            images,
            noticeUI,
            isSelected,
            setAttributes,
        } = this.props;
        const {
            imgUrl,
            imgId,
            focalPoint
        } = attributes;

        const imageStyle = {
            backgroundImage: `url( ${imgUrl} )`
        };
        if (focalPoint) {
            imageStyle.backgroundPosition = `${focalPoint.x *
                100}% ${focalPoint.y * 100}%`;
        }

        const hasImages = !! images.length;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__("Image Settings", "carkeek-blocks")}>
                        {imgId && (
                            <SelectControl
                                label={__("Image Size", "carkeek-blocks")}
                                options={this.getImageSizes()}
                                onChange={this.onImageSizeChange}
                                value={imgUrl}
                            />
                        )}
                        {imgId && (
                            <FocalPointPicker
                                label={__("Focal Point", "carkeek-blocks")}
                                url={imgUrl}
                                onChange={newFocalPoint =>
                                    setAttributes({
                                        focalPoint: newFocalPoint
                                    })
                                }
                                value={focalPoint}
                            />
                        )}
                    </PanelBody>
                </InspectorControls>
                <BlockControls>
                    {imgUrl && (
                        <Toolbar>
                            {imgId && (
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={this.onSelectImage}
                                        allowedTypes={["image"]}
                                        value={imgId}
                                        render={({ open }) => {
                                            return (
                                                <IconButton
                                                    className="components-icon-button components-toolbar__control"
                                                    label={__(
                                                        "Edit Image",
                                                        "carkeek-blocks"
                                                    )}
                                                    onClick={open}
                                                    icon="edit"
                                                />
                                            );
                                        }}
                                    />
                                </MediaUploadCheck>
                            )}
                            <IconButton
                                className="components-icon-button components-toolbar__control"
                                label={__("Remove Image", "carkeek-blocks")}
                                onClick={this.removeImage}
                                icon="trash"
                            />
                        </Toolbar>
                    )}
                </BlockControls>
                <div className={className}>
                    {imgUrl ? (
                        <>
                            <div
                                className={
                                    "wp-block-carkeek-blocks-link-tile__img"
                                }
                                style={imageStyle}
                            >
                                {isBlobURL(imgUrl) && <Spinner />}

                            </div>
                        </>
                    ) : (
                        <MediaPlaceholder
                            addToGallery={ hasImages }
                            isAppender={ hasImages }
                            className={ className }
                            disableMediaButtons={ hasImages && ! isSelected }
                            icon="format-image"
                            onSelect={this.onSelectImage}
                            onError={this.onUploadError}
                            //accept="image/*"
                            allowedTypes={["image"]}
                            notices={noticeUI}
                            labels={{
                                title: "Images",
                                instructions:
                                    "Select images from your media library."
                            }}
                            multiple
                            value={ images }
                            //onFocus={ this.props.onFocus }
                        />
                    )}
                </div>
            </>
        );
    }
}

export default compose([
    withSelect((select, props) => {
        const imgId = props.attributes.imgId;
        return {
            image: imgId ? select("core").getMedia(imgId) : null,
            imageSizes: select("core/editor").getEditorSettings().imageSizes
        };
    }),
    withNotices
])(ImgSlideEdit);

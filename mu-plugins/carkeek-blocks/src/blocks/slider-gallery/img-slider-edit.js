/**
 * External dependencies
 */
import {
	every,
	filter,
	find,
	forEach,
	get,
	isEmpty,
	map,
	reduce,
	some,
    toString,
    pick
} from 'lodash';

import { Component } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { isBlobURL } from "@wordpress/blob";
import {
    Path,
    SVG,
    PanelRow,
    Spinner,
    withNotices,
    Toolbar,
    IconButton,
    PanelBody,
    SelectControl,
    TextControl,
    FocalPointPicker,
    RangeControl,
    ToggleControl,
    TextareaControl,
    RadioControl,
    Button
} from "@wordpress/components";
import { withSelect } from "@wordpress/data";
import { compose } from "@wordpress/compose";
import classnames from "classnames";
import { InnerBlocks,
    MediaPlaceholder,
    MediaUpload,
    MediaUploadCheck,
    InspectorControls,
    RichText } from "@wordpress/block-editor";

import icons from './icons';
import { pickRelevantMediaFiles } from './shared';


import Gallery from './gallery';



class ImgSliderEdit extends Component {

    constructor() {
        super( ...arguments );

        this.onSelectImage = this.onSelectImage.bind( this );
        this.onSelectImages = this.onSelectImages.bind( this );
        this.onDeselectImage = this.onDeselectImage.bind( this );
        this.setColumnsNumber = this.setColumnsNumber.bind( this );
        this.toggleImageCrop = this.toggleImageCrop.bind( this );
        this.onMove = this.onMove.bind( this );
        this.onMoveForward = this.onMoveForward.bind( this );
        this.onMoveBackward = this.onMoveBackward.bind( this );
        this.onRemoveImage = this.onRemoveImage.bind( this );
        this.onUploadError = this.onUploadError.bind( this );
        this.setImageAttributes = this.setImageAttributes.bind( this );
        this.setAttributes = this.setAttributes.bind( this );
        this.onFocusGalleryCaption = this.onFocusGalleryCaption.bind( this );
        this.getImagesSizeOptions = this.getImagesSizeOptions.bind( this );
        this.updateImagesSize = this.updateImagesSize.bind( this );

        this.state = {
            selectedImage: null,
            attachmentCaptions: null,
        };
    }

    insertBlocksAfter( blocks ) {
		this.props.onInsertBlocks( blocks, this.props.order + 1 );

		if ( blocks[ 0 ] ) {
			// focus on the first block inserted
			this.props.onSelect( blocks[ 0 ].clientId );
		}
	}

    setAttributes( attributes ) {
        if ( attributes.ids ) {
            throw new Error(
                'The "ids" attribute should not be changed directly. It is managed automatically when "images" attribute changes'
            );
        }

        if ( attributes.images ) {
            attributes = {
                ...attributes,
                // Unlike images[ n ].id which is a string, always ensure the
                // ids array contains numbers as per its attribute type.
                ids: map( attributes.images, ( { id } ) => parseInt( id, 10 ) ),
            };
        }

        this.props.setAttributes( attributes );
    }

    onSelectImage( index ) {
        return () => {
            if ( this.state.selectedImage !== index ) {
                this.setState( {
                    selectedImage: index,
                } );
            }
        };
    }

    onDeselectImage( index ) {
        return () => {
            if ( this.state.selectedImage === index ) {
                this.setState( {
                    selectedImage: null,
                } );
            }
        };
    }

    onMove( oldIndex, newIndex ) {
        const images = [ ...this.props.attributes.images ];
        images.splice( newIndex, 1, this.props.attributes.images[ oldIndex ] );
        images.splice( oldIndex, 1, this.props.attributes.images[ newIndex ] );
        this.setState( { selectedImage: newIndex } );
        this.setAttributes( { images } );
    }

    onMoveForward( oldIndex ) {
        return () => {
            if ( oldIndex === this.props.attributes.images.length - 1 ) {
                return;
            }
            this.onMove( oldIndex, oldIndex + 1 );
        };
    }

    onMoveBackward( oldIndex ) {
        return () => {
            if ( oldIndex === 0 ) {
                return;
            }
            this.onMove( oldIndex, oldIndex - 1 );
        };
    }

    onRemoveImage( index ) {
        return () => {
            const images = filter(
                this.props.attributes.images,
                ( img, i ) => index !== i
            );
            const { columns } = this.props.attributes;
            this.setState( { selectedImage: null } );
            this.setAttributes( {
                images,
                columns: columns ? Math.min( images.length, columns ) : columns,
            } );
        };
    }

    selectCaption( newImage, images, attachmentCaptions ) {
        // The image id in both the images and attachmentCaptions arrays is a
        // string, so ensure comparison works correctly by converting the
        // newImage.id to a string.
        const newImageId = toString( newImage.id );
        const currentImage = find( images, { id: newImageId } );

        const currentImageCaption = currentImage
            ? currentImage.caption
            : newImage.caption;

        if ( ! attachmentCaptions ) {
            return currentImageCaption;
        }

        const attachment = find( attachmentCaptions, {
            id: newImageId,
        } );

        // if the attachment caption is updated
        if ( attachment && attachment.caption !== newImage.caption ) {
            return newImage.caption;
        }

        return currentImageCaption;
    }

    onSelectImages( newImages ) {
        const { images, sizeSlug } = this.props.attributes;
        const { attachmentCaptions } = this.state;
        this.setState( {
            attachmentCaptions: newImages.map( ( newImage ) => ( {
                // Store the attachmentCaption id as a string for consistency
                // with the type of the id in the images attribute.
                id: toString( newImage.id ),
                caption: newImage.caption,
            } ) ),
        } );
        this.setAttributes( {
            images: newImages.map( ( newImage ) => ( {
                ...pickRelevantMediaFiles( newImage, sizeSlug ),
                caption: this.selectCaption(
                    newImage,
                    images,
                    attachmentCaptions
                ),
                // The id value is stored in a data attribute, so when the
                // block is parsed it's converted to a string. Converting
                // to a string here ensures it's type is consistent.
                id: toString( newImage.id ),
            } ) ),
        } );
    }

    onUploadError( message ) {
        const { noticeOperations } = this.props;
        noticeOperations.removeAllNotices();
        noticeOperations.createErrorNotice( message );
    }

    setColumnsNumber( value ) {
        this.setAttributes( { columns: value } );
    }

    toggleImageCrop() {
        this.setAttributes( { imageCrop: ! this.props.attributes.imageCrop } );
    }

    getImageCropHelp( checked ) {
        return checked
            ? __( 'Thumbnails are cropped to align.' )
            : __( 'Thumbnails are not cropped.' );
    }

    onFocusGalleryCaption() {
        this.setState( {
            selectedImage: null,
        } );
    }

    setImageAttributes( index, attributes ) {
        const {
            attributes: { images },
        } = this.props;
        const { setAttributes } = this;
        if ( ! images[ index ] ) {
            return;
        }
        setAttributes( {
            images: [
                ...images.slice( 0, index ),
                {
                    ...images[ index ],
                    ...attributes,
                },
                ...images.slice( index + 1 ),
            ],
        } );
    }

    getImagesSizeOptions() {
        const { imageSizes, resizedImages } = this.props;
        return map(
            filter( imageSizes, ( { slug } ) =>
                some( resizedImages, ( sizes ) => sizes[ slug ] )
            ),
            ( { name, slug } ) => ( { value: slug, label: name } )
        );
    }

    updateImagesSize( sizeSlug ) {
        const {
            attributes: { images },
            resizedImages,
        } = this.props;

        const updatedImages = map( images, ( image ) => {
            if ( ! image.id ) {
                return image;
            }
            const url = get( resizedImages, [
                parseInt( image.id, 10 ),
                sizeSlug,
            ] );
            return {
                ...image,
                ...( url && { url } ),
            };
        } );

        this.setAttributes( { images: updatedImages, sizeSlug } );
    }

    componentDidMount() {
        const { attributes, mediaUpload } = this.props;
        const { images } = attributes;
        if (
            images &&
            images.length > 0 &&
            every( images, ( { url } ) => isBlobURL( url ) )
        ) {
            const filesList = map( images, ( { url } ) => getBlobByURL( url ) );
            forEach( images, ( { url } ) => revokeBlobURL( url ) );
            mediaUpload( {
                filesList,
                onFileChange: this.onSelectImages,
                allowedTypes: [ 'image' ],
            } );
        }
    }

    componentDidUpdate( prevProps ) {
        // Deselect images when deselecting the block
        if ( ! this.props.isSelected && prevProps.isSelected ) {
            this.setState( {
                selectedImage: null,
                captionSelected: false,
            } );
        }
    }

    render(){
        const {
            className,
            attributes,
            noticeUI,
            isSelected,
            setAttributes,
        } = this.props;
        const {
            images,
            autoPlay,
            autoPlaySpeed,
            sliderType,
            textOverlay,
            transitionSpeed,
            showDots,
            headerText,
            desktopText,
            slidesToScroll,
            transitionType,
            slidesToShow,
            carousel,
            fixHeight,
            minHeight,
            maxHeight } = attributes;

        const hasImages = !! images.length;

        const mediaPlaceholder = (
                <MediaPlaceholder
                    addToGallery={ hasImages }
                    isAppender={ hasImages }
                    className={ className }
                    disableMediaButtons={ hasImages && ! isSelected }
                    icon={ ! hasImages && icons.image }
                    labels={ {
                        title: ! hasImages && __( 'Slider Images' ),
                        instructions: ! hasImages && 'Select files from your library.',
                    } }
                    onSelect={ this.onSelectImages }
                    accept="image/*"
                    allowedTypes={ [ 'image' ] }
                    multiple
                    value={ images }
                    onError={ this.onUploadError }
                    notices={ hasImages ? undefined : noticeUI }
                    onFocus={ this.props.onFocus }
                />
        );

        if ( ! hasImages ) {
			return mediaPlaceholder;
        }

        return (
            <>
                <InspectorControls>
                <PanelBody title={__("Slider Settings", "carkeek-blocks")}>
                        <ToggleControl
                            label="Auto Play Slider"
                            checked={ autoPlay }
                            onChange={value =>
                                setAttributes({ autoPlay: value })
                            }
                        />
                        <RadioControl
                            label="Transition"
                            selected={ transitionType }
                            options={ [
                                { label: 'Slide', value: 'slide' },
                                { label: 'Fade', value: 'fade' },
                            ] }
                            onChange={ ( transitionType ) => { setAttributes( { transitionType } ) } }
                        />
                        {autoPlay &&
                        <>
                        <RangeControl
                            label={__("Time on each slide in ms", "carkeek-blocks")}
                            value={autoPlaySpeed}
                            onChange={value =>
                                setAttributes({ autoPlaySpeed: value })
                            }
                            min={1000}
                            max={10000}
                        />

                        </>
                        }
                        <RangeControl
                            label={__("Transition speed", "carkeek-blocks")}
                            value={transitionSpeed}
                            onChange={value =>
                                setAttributes({ transitionSpeed: value })
                            }
                            min={1000}
                            max={10000}
                        />
                        <ToggleControl
                            label="Show Dot Navigation"
                            checked={ showDots }
                            onChange={value =>
                                setAttributes({ showDots: value })
                            }
                        />
                        <ToggleControl
                            label="Fix Height"
                            checked={ fixHeight }
                            onChange={value =>
                                setAttributes({ fixHeight: value })
                            }
                        />
                        {fixHeight &&
                        <>
                        <RangeControl
                            label={__("Minimum Height", "carkeek-blocks")}
                            value={minHeight}
                            onChange={value =>
                                setAttributes({ minHeight: value })
                            }
                            min={100}
                            max={1000}
                        />
                        <RangeControl
                            label={__("Max Height", "carkeek-blocks")}
                            value={maxHeight}
                            onChange={value =>
                                setAttributes({ maxHeight: value })
                            }
                            min={100}
                            max={1000}
                        />
                        </>
                        }
                        {/* code is ready, but need to do more styling if carousel is desired - its set to false by default so option is not displayed */}
                        {carousel &&
                        <RadioControl
                            label="Slider type"
                            selected={ sliderType }
                            options={ [
                                { label: 'Single Slides', value: 'single' },
                                { label: 'Carousel', value: 'carousel' },
                            ] }
                            onChange={ ( sliderType ) => { setAttributes( { sliderType } ) } }
                        />
                        }
                        {sliderType == 'carousel' &&
                            <RangeControl
                            label={__("Slides to Show", "carkeek-blocks")}
                            value={slidesToShow}
                            onChange={ ( slidesToShow ) => { setAttributes( { slidesToShow } ) } }
                            min={2}
                            max={6}
                        />
                        }
                        {sliderType == 'carousel' &&
                            <RangeControl
                            label={__("Slides to Scroll", "carkeek-blocks")}
                            value={slidesToScroll}
                            onChange={ ( slidesToScroll ) => { setAttributes( { slidesToScroll } ) } }
                            min={1}
                            max={6}
                        />
                        }
                    </PanelBody>

                    <PanelBody title="Text Overlay settings" initialOpen={false}>
                        <PanelRow>
                            Set a text overlay over all slides (to place different text over each slide use the Block Slider block).
                            </PanelRow>
                            <ToggleControl
                            label="Show Text Overlay"
                            checked={ textOverlay }
                            onChange={value =>
                                setAttributes({ textOverlay: value })
                            }
                        />
                        {textOverlay &&
                        <>
                            <PanelRow>
                                <TextControl
                                label="Header"
                                value={ headerText }
                                onChange={ ( headerText ) => { setAttributes( { headerText } ) } }
                            />
                        </PanelRow>
                        <PanelRow>
                        <TextareaControl
                                label="Content (Desktop)"
                                help="Enter some text"
                                value={ desktopText }
                                onChange={ ( desktopText ) => { setAttributes( { desktopText } ) } }
                            />
                        </PanelRow>
                        </>
    }
                    </PanelBody>

                </InspectorControls>
                {textOverlay &&
                <div className="wp-block-carkeek-blocks-slider__slide-overlay">
                        <RichText
                        tagName="h1" // The tag here is the element output and editable in the admin
                        className={ className }
                        value={ headerText} // Any existing content, either from the database or an attribute default
                        formattingControls={ [] } // Allow the content to be made bold or italic, but do not allow other formatting options
                        onChange={ ( headerText ) => setAttributes( { headerText } ) } // Store updated content as a block attribute
                        placeholder={ __( 'Heading...' ) } // Display this text before any content has been added by the user
                    />
                    <RichText
                        tagName="p" // The tag here is the element output and editable in the admin
                        className={ className }
                        value={ desktopText} // Any existing content, either from the database or an attribute default
                        formattingControls={ [] } // Allow the content to be made bold or italic, but do not allow other formatting options
                        onChange={ ( desktopText ) => setAttributes( { desktopText } ) } // Store updated content as a block attribute
                        placeholder={ __( 'Text...' ) } // Display this text before any content has been added by the user
                    />
                    </div>
                }
                <Gallery
					{ ...this.props }
					selectedImage={ this.state.selectedImage }
					mediaPlaceholder={ mediaPlaceholder }
					onMoveBackward={ this.onMoveBackward }
					onMoveForward={ this.onMoveForward }
					onRemoveImage={ this.onRemoveImage }
					onSelectImage={ this.onSelectImage }
					onDeselectImage={ this.onDeselectImage }
					onSetImageAttributes={ this.setImageAttributes }
					onFocusGalleryCaption={ this.onFocusGalleryCaption }
					insertBlocksAfter={ this.insertBlocksAfter }
				/>
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
])(ImgSliderEdit);
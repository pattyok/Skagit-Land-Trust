import { Component } from "@wordpress/element";
import { withSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import { InnerBlocks, RichText } from "@wordpress/block-editor";


class ImageRollover extends Component {
    render() {
        const {
            attributes,
            setAttributes,
            className,
            innerBlocksHeadlineLevel
        } = this.props;
        const {
            rolloverText,
            rolloverHeadline,
        } = attributes;
        const headerTag = 'h' + innerBlocksHeadlineLevel;
        return (
            <div className={className}>
                <div className="wp-block-carkeek-blocks-rollover-image--images">
                    <InnerBlocks
                        templateLock={true}
                        template={[
                            ["core/image", { className: 'image-01' }],
                            ["core/image", { className: 'image-02' }],
                        ]}
                        orientation="horizontal"
                    />
                </div>
                <div className="wp-block-carkeek-blocks-rollover-image--text">
                    <RichText
                        tagName={ headerTag }
                        value={ rolloverHeadline }
                        onChange={ ( rolloverHeadline ) => setAttributes( { rolloverHeadline } ) }
                        placeholder={ __('Rollover Heading...')}
                        formattingControls={ [ ] }
                    />
                    <RichText
                        tagName={ 'p' }
                        value={ rolloverText }
                        onChange={ ( rolloverText ) => setAttributes( { rolloverText } ) }
                        placeholder={ __('Rollover Content...')}
                    />
                </div>
            </div>
        );
    }
}

export default withSelect((select, props) => {
    const parentId = select( 'core/block-editor' ).getBlockHierarchyRootClientId( props.clientId );
    const parentAttributes = select('core/block-editor').getBlockAttributes( parentId );

    return {
        innerBlocksHeadlineLevel: parentAttributes.innerBlocksHeadlineLevel
    };
})(ImageRollover);

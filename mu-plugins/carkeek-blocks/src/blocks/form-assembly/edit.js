import icons from './icons';

import { Component } from "@wordpress/element";
import { PlainText } from "@wordpress/block-editor";
import { Placeholder } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
//import { Icon } from "@wordpress/icons"; cannot get Icon to work, something to do with the Build, but not sure what

class FormAssemblyEdit extends Component {



    render() {

        const {
            attributes,
            setAttributes,
        } = this.props;
        const {
            formId,
        } = attributes;


	    //const inputId = `blocks-shortcode-input-${ instanceId }`;

            return (
                <div className="wp-block-carkeek-blocks-form-assembly components-placeholder">
                    <Placeholder className="components-placeholder__label" icon={ icons.form } label={ __( 'Form Assembly Form' ) }>
                    <label>Form Id</label>
                    <PlainText
                        className="blocks-shortcode__textarea"
                        value={ formId }
                        placeholder={ __( 'Enter Form Id...' ) }
                        onChange={ ( formId ) => setAttributes( { formId } ) }
                    />
                    </Placeholder>

                </div>
            );
        }
}

export default FormAssemblyEdit;

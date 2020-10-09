import { withSelect } from '@wordpress/data';
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";



registerBlockType("carkeek-blocks/title-block", {
    title: __("Title Block", "carkeek-blocks"),
    description: __(
        "Place the page title within your page",
        "carkeek-blocks"
    ),
    icon: "feedback",
    category: "widgets",
    attributes: {
        postTitle: {
            type: "string",
        }
    },
    edit: withSelect(
        ( select ) => {
          return {
            title: select( 'core/editor' ).getEditedPostAttribute( 'title' ),
          }
        } )( props => {
          return (
            <h1>{ props.title }</h1>
          )
      } ),
    supports: {},
    save( ) {
        return null;
    }
});

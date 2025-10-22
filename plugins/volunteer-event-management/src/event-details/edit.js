import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';
import {
    useBlockProps
} from "@wordpress/block-editor";
export default function Edit( { attributes, setAttributes, context } ) {
	const blockProps = useBlockProps();
	return (
		 <div {...blockProps}>
			<div className='block-notes'>This will display the event/shift details dynamically.</div>
		</div>
	);
}



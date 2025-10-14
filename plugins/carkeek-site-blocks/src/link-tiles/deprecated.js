import { __ } from "@wordpress/i18n";
import { InnerBlocks } from "@wordpress/block-editor";


const deprecated = [
	{
		attributes: {
			align: {
				type: "string",
			}
		},
		save() {
			return (
				<div className={"wp-block-columns"}>
					<InnerBlocks.Content />
				</div>
			);
		}
	}
];
export default deprecated;

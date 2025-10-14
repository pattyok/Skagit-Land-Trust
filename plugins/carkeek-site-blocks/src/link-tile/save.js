import { useBlockProps, BlockControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

const BlockSave = (props) => {
	const { attributes } = props;
	console.log(attributes);
	const {
		linkText,
		imageUrl,
		linkHoverText,
		linkUrl,
		focalPoint
	} = attributes;
	console.log(imageUrl);
	const imageStyle = {
		backgroundImage: `url( ${imageUrl} )`
	};
	if (focalPoint) {
		imageStyle.backgroundPosition = `${focalPoint.x *
			100}% ${focalPoint.y * 100}%`;
	}
	return (
		<div { ...useBlockProps.save( ) }>
				<a
					className={
						"ck-link-tile"
					}
					href={linkUrl}
				>

					<span className="ck-link-tile__title">{linkText}</span>

					<span className="ck-link-tile__hover">
						{linkHoverText ? linkHoverText : linkText}
					</span>
					<span className="ck-link-tile__overlay"></span>
					<span
						style={imageStyle}
						className={
							"ck-link-tile__background"
						}
					></span>
				</a>
		</div>
	);
}


export default BlockSave;
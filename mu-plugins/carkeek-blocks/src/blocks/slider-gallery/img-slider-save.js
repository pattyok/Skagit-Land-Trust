/**
 * WordPress dependencies
 */
import { RichText } from '@wordpress/block-editor';



export default function save( { attributes } ) {

	const {
        autoPlay, autoPlaySpeed, transitionSpeed, showDots, slidesToShow, headerText, desktopText, sliderType, slidesToScroll, fixHeight, minHeight, maxHeight, transitionType,
		images,
	} = attributes;
	let style;
    if (fixHeight) {
        style = {
            minHeight: minHeight,
            maxHeight: maxHeight
        }
    }
	return (
        <div>
		{ (headerText || desktopText) &&
            <div className="wp-block-carkeek-blocks-slider__slide-overlay">
                <h1>{headerText}</h1> <p className="desktop-text">{desktopText}</p>
            </div>
            }
			<div className={`wp-block-carkeek-blocks-slider__slide-wrapper fix-height-${fixHeight}`} style={style} data-minheight={minHeight} data-maxheight={maxHeight} data-showdots={showDots} data-autoplay={autoPlay} data-speed={autoPlaySpeed} data-type={sliderType} data-slides={slidesToShow} data-scroll={slidesToScroll} data-transition={transitionType} data-transitionspd={transitionSpeed}>
				{ images.map( ( image ) => {
					// The image should only have an aria-label if it's within a link and has no alt text.
					const imageLabel =
						image.caption && image.link
							? image.caption
							: null;
					const imageStyle = {
						backgroundImage: `url( ${image.fullUrl} )`
					};
					if (image.focalPointX) {
						imageStyle.backgroundPosition = `${image.focalPointX *
							100}% ${image.focalPointY  * 100}%`;
					}
					const img = (
						<div
							style={ imageStyle }
							data-id={ image.id }
							data-url={ image.url }
							data-full-url={ image.fullUrl }
							data-link={ image.link }
							data-focalx = {image.focalPointX}
							data-focaly = {image.focalPointY}
							className={ 'carkeek-slider-item__image'}
							aria-label={ imageLabel || null }
						/>
					);

					return (
						<div
							key={ image.id || image.url }
							className="carkeek-slider-item"
						>
								{ ! RichText.isEmpty( image.caption ) && (
									<RichText.Content
										tagName="div"
										className="carkeek-slider-item__caption"
										value={ image.caption }
									/>
								) }

								{ image.linksto ? <a className="carkeek-slider-item__link" href={ image.linksto }>{ img }</a> : img }

						</div>
					);
				} ) }
			</div>
		</div>
	);
}
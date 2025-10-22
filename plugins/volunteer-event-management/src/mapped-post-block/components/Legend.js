import iconsvg from "../../resources/marker";
const Legend = (props) => {	
	const iconHtml = `${iconsvg.marker}`;
	return (
		<div className="leaflet-top leaflet-left">
			<div className="leaflet-control leaflet-bar map-legend">
			<ul className="map-legend-list no-bullets">
				{props.items && props.items.map( (item, index) => (
					<li key={index} className="map-legend-item">
						<span className={`map-legend-icon icon-${item.slug}`} aria-hidden="true" dangerouslySetInnerHTML={{ __html: iconHtml }}></span>
						<span className="map-legend-label" dangerouslySetInnerHTML={{ __html: item.label }}></span>
					</li>
				) )}
			</ul>
			</div>
		</div>
	)
}
export default Legend;
import React, { useRef } from "react";
import { Marker, Popup } from "react-leaflet";
import L from "leaflet";
import iconsvg from "./marker";

const Pin = props => {
    const markerRef = useRef(null);
    const {
        center,
        title,
        link,
		icon,
		data,
		image
    } = props;

	const getMyIcon =() => {
		const iconHtml = `${iconsvg.marker}`;
		const divIcon = L.divIcon({
            className: `archive-map-icon icon-${icon}`,
            html: iconHtml,
            iconSize: [25, 35],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        });
        return divIcon;
    }

    const popup = (

        <div className={"map-archive-popup"}>
			{image && <div className="popup-image" style={{backgroundImage: `url(${image})`}}></div>}
			<div className="popup-content">
            <a
                className={"popup-link"}
                href={link}
                dangerouslySetInnerHTML={{ __html: title }}
            />
            <ul className="popup-data no-bullets">
				{data.loc_location && <li className="popup-label"><span className="popup-location" title="Location"></span> {data.loc_location}</li>}
				{data.loc_acreage && <li className="popup-label"><span className="popup-acreage" title="Acreage"></span> {`${data.loc_acreage} acres`}</li>}
				<li className="popup-label"><span className="popup-access" title="Access"></span>
				{data.loc_open_to_the_public ? "Open to the Public" : "Not open to the Public"}</li>
				</ul>
			</div>
        </div>
    );
    return (
        <Marker
            position={center}
			icon={getMyIcon()}
        >
            <Popup minWidth={325} ref={markerRef}>{popup}</Popup>
        </Marker>
    );
};

export default Pin;

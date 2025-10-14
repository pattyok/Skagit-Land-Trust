import React, { useRef } from "react";
import { Marker, Popup } from "react-leaflet";
import L from "leaflet";

const Pin = props => {
    const markerRef = useRef(null);
    const {
        center,
        title,
        excerpt,
        link,
        onItemClick,
        itemId,
        projectId,
        cat,
        project
    } = props;

    const getMyIcon = cat => {
        const myIcon = L.divIcon({
            className: cat,
            iconSize: [25, 25],
            iconAnchor: [10, 40],
            popupAnchor: [0, -56]
        });
        return myIcon;
    };
    /* gets passed to Details when clicked */
    const locData = {
        location: title,
        projectId: projectId,
        locationId: itemId
    };
    const popup = (
        <div className={"map-archive-popup"}>
            <a
                className={"popup-link"}
                href={link}
                dangerouslySetInnerHTML={{ __html: project }}
            />
            <span
                className={"popup-title"}
                dangerouslySetInnerHTML={{ __html: title }}
            />
            <span
                className={"popup-excerpt"}
                dangerouslySetInnerHTML={{ __html: excerpt }}
            />
        </div>
    );
    return (
        <Marker
            position={center}
            icon={getMyIcon(cat)}
			eventHandlers={{
				click: () => {
					onItemClick(locData);
				}
			}}
        >
            <Popup ref={markerRef}>{popup}</Popup>
        </Marker>
    );
};

export default Pin;

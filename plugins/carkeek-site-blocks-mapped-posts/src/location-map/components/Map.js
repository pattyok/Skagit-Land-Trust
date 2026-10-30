import React, { useState } from "react";
import { MapContainer, ZoomControl, GeoJSON } from "react-leaflet";
import VectorBasemapLayer from "react-esri-leaflet/plugins/VectorBasemapLayer";
import Pins from "./Pins.js";

function MapCluster(props) {
    const { locations } = props;

    const [map, setMap] = useState();

	const center = locations.markers[0].position;

    return (
                <MapContainer
                    zoom={11}
                    zoomControl={false}
                    scrollWheelZoom={false}
                    whenCreated={setMap}
                    maxZoom={18}
                    minZoom={4}
					center={center}
                >

                    <Pins
                        map={map}
                        data={locations.markers}
                        selectedIndex={"x"}
                        onItemClick={false}
                        cluster={false}
                        paddingTopLeft={[50, 50]}
                        paddingBottomRight={[50, 50]}
                        fitBounds={false}
                    />
                    <VectorBasemapLayer
                        name="arcgis/streets"
                        token={
                            "AAPTaMH9w_GFkp5y4XBA6V4Z_4Q..Z4eSDiQmiJCSE4RL6XoWA2QIE6zLRXJ7HUOom4cvBdiPTb1avVHE-KdeX8gaNyg2dli3Oqh2ZjjgNVCjrvnhFcpVC40me41YQ_Kp5px0-oMj-d3aTD-TrxAl2pwMNTNMVPtXlzREQQyHqTgL1dCDslT55_6URg5H_35w281sq_43hWaj-LoAW42o23Y-Y7DDmSOR0t7UgjSC2FPjT131tyCCmw_awIBxYnovjq3_pZnmUdBf7sX36w..AT1_ayS4xCp7"
                        }
                    />
                    <ZoomControl position="topright" />
                </MapContainer>
    );
}

export default MapCluster;

import React, { useRef, useEffect } from "react";
import MarkerClusterGroup from 'react-leaflet-cluster'
import { latLngBounds } from "leaflet";
import { FeatureGroup, useMap } from "react-leaflet";
import _ from "lodash";
import Pin from './Pin.js';

	const Pins = props => {
		const {
				data,
				selectedIndex,
				onItemClick,
				cluster,
				paddingTopLeft,
				paddingBottomRight,
				fitBounds
		} = props;
		const map = useMap();
		const groupRef = useRef();
		const clusterRef = useRef();
		const bounds = latLngBounds();


		const Markers = [];
		data.forEach(item => {
				bounds.extend(item.position);
				//if (item.cat.slugs.length > 0) {
				const title = _.get(item, 'title.rendered', '');
				Markers.push(
					<Pin
						key={item.id}
						itemId={item.id}
						project={item.project ? item.project.title : ""}
						projectId={item.project ? item.project.id : ""}
						title={item.title.rendered}
						cat={item.cat.slugs.join(" ")}
						excerpt={item.project ? item.project.popup_desc : ""}
						link={item.project ? item.project.link : ""}
						center={[
							item.acf.acf_project_location.lat,
							item.acf.acf_project_location.lng
						]}
						onItemClick={onItemClick}
						groupRef={groupRef}
					/>
				);
				//}
		});

		if (cluster) {
				if (fitBounds) {
						useEffect(() => {
								const layer = groupRef.current; //get leaflet.markercluster instance
								if (data.length > 0 && layer) {
										map.fitBounds(layer.getBounds(), {
												paddingTopLeft: paddingTopLeft,
												paddingBottomRight: [50, 50]
										}); //zoom to cover visible markers
								}
						}, [data, map, groupRef]);
				}
				return (
						<FeatureGroup ref={groupRef} name="locations">
							<MarkerClusterGroup
										ref={clusterRef}
										showCoverageOnHover={false}
										spiderfyOnMaxZoom={true}
										spiderLegPolylineOptions={{
												weight: 0,
												opacity: 0
										}}
										removeOutsideVisibleBounds={true}
										maxClusterRadius={20}
								>
										{Markers}
								</MarkerClusterGroup>
						</FeatureGroup>
				);
		}

		useEffect(() => {

				if (data.length > 0 && map) {
						if (fitBounds) {
								map.fitBounds(bounds, {
										paddingTopLeft: paddingTopLeft,
										paddingBottomRight: [50, 50]
								});
						} else {
								map.setView(bounds.getCenter(), map.getZoom());
						}
				}
		}, [data, map]);

		return Markers;
};

export default Pins;

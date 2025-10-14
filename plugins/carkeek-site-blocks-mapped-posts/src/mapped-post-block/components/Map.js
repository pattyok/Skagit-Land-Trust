import React, { useRef, useState, useEffect } from "react";
import { MapContainer, ZoomControl, AttributionControl} from "react-leaflet";
import {useNavigate } from 'react-router-dom';
import VectorBasemapLayer from "react-esri-leaflet/plugins/VectorBasemapLayer";

var _ = require('lodash');

import Pins from './Pins.js';
import FilterList from './Filters.js';
import Details from "./Details.js";

function MapCluster(props) {
    const { zoom,
		locations,
		categories,
		isMapLoading,
		isCatLoading,
		visibleLocations,
		onUpdateLocations,
		visibleBounds,
		taxFilter,
		cluster,
		allCategories} = props;
    const hideListAtLoad = window.innerWidth > 600 ? false : true;
    const [selected, setSelected] = useState();
    // let params = queryString.parse(location.search)
    let paramCats = [];

    const [selectedCats, setSelectedCats] = useState(categories);

	//handling this all manually for the sake of time, but could work on filtering by field instead of taxonomies
	const [showOnlyOpen, setShowOnlyOpen] = useState(false);

	useEffect(() => {
		setSelectedCats(allCategories);
	}, [isCatLoading, categories]);
    const [hideList, setHideList] = useState(hideListAtLoad);


    const mapRef = useRef(null);

    const arrayContains = (arr1, arr2) => {
        return _.intersection(arr1, arr2).length > 0;
    }

    const mapReady = !isMapLoading && !isCatLoading;

	const [showDetails, setShowDetails] = useState(false);


    function handleItemClick(index) {
        //setSelected(index); //this makes the map zoom back out weird, need to figure that out.
    }

    function handleHeaderClick() {
        setHideList(!hideList);
    }

    function handleMapClick() {
        setSelected();
		setShowDetails(false);
    }

	function handlePinClick(details) {
        setSelected(details);
        setShowDetails(true);
    }


    const showList = false;

    const filterLocations = () => {
        let toFilter = [];
		toFilter = _.clone(locations);

        if (selectedCats && selectedCats.length > 0 ) {
            const visible = []
            toFilter.map((item) => {
                if (arrayContains(selectedCats, item.cat.ids)) {
                    visible.push(item);
                }
            })
            onUpdateLocations(visible);
        } else {
        	onUpdateLocations(toFilter);
        }
    }

	useEffect(() => {
		if (locations && locations.length > 0){
		filterLocations();
		}
	}, [showOnlyOpen, selectedCats, locations]);
    //todo: tie this to browser resize https://react-cn.github.io/react/tips/dom-event-listeners.html
    let boundsOptions = {padding: [25, 25]}
    if ( window.innerWidth > 599 ) {
        boundsOptions= {paddingTopLeft: [275, 0]}
    }

    const updateSelectedCats = (selected) => {
        setSelectedCats( () => {
            return (selected);
        });
    }
    let mapProps = {};
    if (mapReady && visibleLocations.length > 0) {

        mapProps = {
            bounds: visibleBounds
        }
        if (paramCats.length) {
            filterLocations(paramCats);
        }
    } else {
        mapProps = {
			center: ["48.41789", "-122.323702"],
			boundsOptions: { padding: [50, 100] }
    	}
	}
	function resetFilters() {
        updateSelectedCats(allCategories);
    }
	const intro = document.getElementById('mapped-posts-map-intro').innerHTML;

	/** this method for getting the baselayer is specific to esri, to get the attribution correct, for other services you can use inside the <Map>
     * <TileLayer
        attribution='<a href="https://www.maptiler.com/copyright/" target="_blank" >&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
        url="https://api.maptiler.com/maps/basic/{z}/{x}/{y}.png?key=yRFimapDOtSxWTi4dx3l"
    />
     */

    return (
        <div className={`archive-map-wrapper`}>
        <div className={'archive-map-top'}>
			<div className={'archive-map-intro'} dangerouslySetInnerHTML={{__html: intro}}></div>
			{mapReady &&
				<>
				<div className="archive-filter-list">
					<FilterList
						title={"Map Legend Filter"}
						options={categories}
						selectedOptions={selectedCats}
						locations={locations}
						reset={resetFilters}
						onChange={(selectedCats) => updateSelectedCats(selectedCats)}
						level="0"
					/>
				</div>
				</>
			}
		</div>
        <div className={'archive-map'}>


        <MapContainer {...mapProps} zoom={zoom} maxZoom={18} minZoom={8} zoomControl={false} attributionControl={false} ref={mapRef} scrollWheelZoom={false} onClick={handleMapClick} boundsOptions={boundsOptions}>
		<VectorBasemapLayer
					name="ArcGIS:Oceans"
					token={
						"AAPK692d4e59a5064191837c52ad260003cbyE65i_A9p_iTyzJWouWHCW7sIMB2mbREywj8EIuVL8asRt_JhtCshSvLosw1RHXZ"
					}
				/>
            {mapReady &&
                <Pins fitBounds={true} selectedIndex={selected} paddingTopLeft={[50, 50]} cluster={cluster} data={visibleLocations} selectedCats={selectedCats} onItemClick={handlePinClick}  />
            }
            <ZoomControl position="topleft" />
        	<AttributionControl position="bottomleft" />
        </MapContainer>
		<Details
			{...selected}
			showDetails={showDetails}
			setShowDetails={setShowDetails}
		/>
        </div>

        </div>
    );
}

export default MapCluster;

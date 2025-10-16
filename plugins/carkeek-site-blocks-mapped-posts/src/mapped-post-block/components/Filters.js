var _ = require("lodash");
import React, { useRef, useState, useEffect, use } from "react";
import FilterDropdown from './filters/FilterDropdown.js';
import FilterCheckboxTax from "./filters/FilterCheckboxTax.js";
import FilterCheckbox from "./filters/FilterCheckbox.js";
import FilterRadio from "./filters/FilterRadio.js";

const FilterList = ({ locations, categories, onUpdateLocations, label }) => {
	console.log('FilterList render, label:', label);
	const [selectedSearch, setSelectedSearch] = useState(null);
	const [selectedCats, setSelectedCats] = useState(null);
	const [selectedType, setSelectedType] = useState(null);
	const [viewMode, setViewMode] = useState('search');
	const [filterActive, setFilterActive] = useState(false);

	const [typeOptions, setTypeOptions] = useState([]);

	function checkFilterActive() {
		if (selectedCats && selectedCats.length > 0) {
			return true;
		}
		if (selectedType && selectedType.length > 0) {
			return true;
		}
		if (selectedSearch) {
			return true;
		}
		return false;
	}

	//Actions to run when filters change all based on the state changes.
	useEffect(() => {
		if (locations && locations.length > 0){
			filterLocationsCat();
		}
	}, [selectedCats, locations]);

	useEffect(() => {
		if (locations && locations.length > 0){
			filterLocationsACF('loc_type', selectedType);
		}
	}, [selectedType, locations]);

	useEffect(() => {
		if (locations && locations.length > 0){
			filterLocationsVal(selectedSearch?.value, 'id');
		}
	}, [selectedSearch, locations]);

	//Any time any filter changes, check if any are active
	useEffect(() => {
		const active = checkFilterActive();
		setFilterActive(active);
	}, [selectedCats, selectedType, selectedSearch]);

	//Set options for type filter
	useEffect(() => {
		if (locations && locations.length > 0){
			const options = getFilterOptions(locations, 'loc_type');
			setTypeOptions(options);

		}
	}, [locations]);

	/** Reset all filters when view mode changes */
	useEffect(() => {
		resetAll();
	}, [viewMode]);


    const arrayContains = (arr1, arr2) => {
        return _.intersection(arr1, arr2).length > 0;
    }

	function filterLocationsVal( value, meta_key ) {
		if (!value) {
			onUpdateLocations(locations);
			return;
		}
		const filtered = _.filter(locations, loc => {
			if (loc[meta_key]) {
				return loc[meta_key] === value;
			}
			return false;
		});
		onUpdateLocations(filtered);
	}


	const filterLocationsCat = () => {
		let toFilter = [];
		toFilter = _.clone(locations);
		console.log('Filtering locations, total:', toFilter.length, 'selectedCats:', selectedCats);

		if (selectedCats && selectedCats.length > 0 ) {
			const visible = []
			toFilter.map((item) => {
				if (arrayContains(selectedCats, item.cats)) {
					visible.push(item);
				}
			})
			onUpdateLocations(visible);
		} else {
			onUpdateLocations(toFilter);
		}
	}

	//Filter by ACF field value
	function filterLocationsACF( meta_key, selected ) {
		if (!selected || selected.length === 0) {
			onUpdateLocations(locations);
			return;
		}
		const filtered = _.filter(locations, loc => {
			if (loc.acf && loc.acf[meta_key]) {
				//check if loc.acf[meta_key] is an object or single value.
				if (typeof loc.acf[meta_key] === 'object' && loc.acf[meta_key] !== null) {
					return arrayContains(selected, [loc.acf[meta_key].value]);
				}
				return arrayContains(selected, [loc.acf[meta_key]]);
			}
			return false;
		});
		onUpdateLocations(filtered);
	}



	function getFilterOptions(data, key1) {
		let options = [];
		if (data && data.length > 0) {
			const vals = _.map(data, item => item['acf']);
			console.log('Filter values:', vals);
			// Extract all loc_type objects, filter out falsy, and get unique by 'value'
			const options = _.chain(vals)
				.map(item => item[key1])
				.filter(Boolean)
				.uniqBy('value')
				.value()
				.sort((a, b) => { return a.label.localeCompare(b.label) });
			return options;
		}
		return options;

	}

	const resetAll = () => {
		setSelectedSearch(null);
		setSelectedCats(null);
		setSelectedType(null);
		setFilterActive(false);
		//Reset locations to all
		onUpdateLocations(locations);
	}


    return (
			<>
			<div class="archive-filter-header">
				<FilterRadio
					options={[
						{ label: 'Property Name', value: 'search' },
						{ label: 'Property Type', value: 'loc_type' },
						{ label: 'Activities', value: 'category' }
					]}
					label={label}
					selectedOption={viewMode}
					setSelectedOption={setViewMode}
					groupName="view-mode"
				/>

			</div>
			<div class="archive-filters-content">
			{viewMode === 'search' &&
				<FilterDropdown
					title="Search by Property"
					options={locations}
					setSelectedOption={setSelectedSearch}
					selected={selectedSearch}
				/>
			}
			{viewMode === 'category' &&
				<FilterCheckboxTax
					options={categories}
					setSelectedOptions={setSelectedCats}
					selectedOptions={selectedCats ? selectedCats : []}
				/>
			}
			{viewMode === 'loc_type' &&
				<FilterCheckbox
					options={typeOptions}
					setSelectedOptions={setSelectedType}
					selectedOptions={selectedType ? selectedType : []}
				/>
			}
			{filterActive &&
			<button className="archive-filter-reset" onClick={resetAll}>
				Reset Filter
			</button>
			}
			</div>
			</>
    );
};

export default FilterList;

var _ = require("lodash");
import React from "react";

const FilterList = ({ options, selectedOptions, onChange, title, reset, locations }) => {
	/** Because we are pulling categories from the projects we can't just request unempty cats, so we filter on actual using */
	const catUsing = _.uniq(_.flatten(_.map(locations, 'cat.ids')));

	const ListItem = ({ label, value, catChildren, parent, slug, color }) => {
        const selected = _.includes(selectedOptions, value);
        let icon = "";
        if (parent !== 0) {
            icon = (
                <span
                    aria-hidden="true"
                    className={`archive-filter-icon ${slug}`}
                ></span>
            );
        }

        const handleCheckboxClicked = e => {
            const myChildrenIds = _.map(catChildren, "id");

            if (e.target && e.target.type === "checkbox") {
                const selectedOptionId = parseInt(e.target.value);
                const parentId = parseInt(parent);
                let newSelectedOptions = _.cloneDeep(selectedOptions);
                // is currently selected
                if (_.includes(selectedOptions, selectedOptionId)) {
                    // remove selected value from options list
                    _.pull(newSelectedOptions, selectedOptionId);
                    // remove children from selected list
                    _.pullAll(newSelectedOptions, myChildrenIds);
                } else {
                    // is not currently selected
                    // Add selected key to optionsList
                    //newSelectedOptions.push(selectedOptionId);
                    newSelectedOptions = _.concat(
                        selectedOptions,
                        selectedOptionId,
                        myChildrenIds
                    );
                }

                //if parent is currently selected, we remove if
                if (_.includes(selectedOptions, parentId)) {
                    _.pull(newSelectedOptions, parentId);
                }

                //if the options are empty we add the parent only
                if (newSelectedOptions.length == 0) {
                    newSelectedOptions.push(parentId);
                }

                // call onChange function pulled in from Map to track state
                onChange(newSelectedOptions);
            }
        };

        let children = null;
        if (catChildren.length > 0) {

            children = (
                <ul>
                    {catChildren.map(cat => {
						if (catUsing.includes(cat.id)) {
							return (
								<ListItem
									key={cat.id}
									label={cat.name}
									value={parseInt(cat.id)}
									parent={cat.parent}
									catChildren={cat.children}
									slug={cat.slug}
								/>
							);
						}
                    })}
                </ul>
            );
        }
        return (
            <li className={`filter-${slug}`}>
                <input
                    checked={selected}
                    type="checkbox"
                    onChange={e => handleCheckboxClicked(e)}
                    value={value}
                    id={`cat-${value}`}
                />
                <label htmlFor={`cat-${value}`}>
                    {icon}
                    <span dangerouslySetInnerHTML={{ __html: label }}></span>
                </label>
                {children}
            </li>
        );
    };

    return (
			<>
			<div class="archive-filter-header">
			<h2>{title}</h2>
			<button className="archive-filter-reset" onClick={reset}>
				Reset all Filters
			</button>
			</div>
            <ul className="archive-filters">
                {options.map(cat => {
						return (
							<ListItem
								key={cat.id}
								label={`${cat.name}`}
								value={parseInt(cat.id)}
								parent={cat.parent}
								catChildren={cat.children}
								slug={cat.slug}
							/>
						);
                })}
            </ul>
			</>
    );
};

export default FilterList;

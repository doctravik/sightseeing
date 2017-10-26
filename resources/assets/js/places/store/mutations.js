import Errors from './../../helpers/Errors';
import Paginator from './../../pagination/ServerResourcePaginator';

/**
 * Save places to the store.
 *
 * @param  {Object} state
 * @param  {Array} places
 */
export const setPlaces = (state, places) => {
    state.places = places;
};

/**
 * Save count of the places to the store.
 *
 * @param  {Object} state
 * @param  {Number} count
 */
export const setPlacesCount = (state, count) => {
    state.count = count;
};

/**
 * Save paginator to the store.
 *
 * @param  {Object} state
 * @param  {Object} paginator
 */
export const setPaginator = (state, paginator) => {
    state.paginator = Paginator.make(paginator);
};

/**
 * Set new filters as intersection of the allowedFilters and the given filters.
 *
 * @param  {Object} state
 * @param  {Object} filters
 */
export const setFilters = (state, filters) => {
    // set only allowedFilters
    state.filters = state.allowedFilters.reduce((carry, filter) => {
        if (filters.hasOwnProperty(filter)) {
            carry[filter] = filters[filter]
        }
        return carry;
    }, {});
};

/**
 * Add new filter to the store.
 *
 * @param  {Object} state
 * @param  {string} [name
 * @param  {mixed} value]
 */
export const addFilter = (state, [name, value]) => {
    if (state.allowedFilters.indexOf(name) != -1) {
        state.filters = Object.assign({}, state.filters, {[name]: value});
    }
};

/**
 * Remove filter from the store.
 *
 * @param  {Object} state
 * @param  {String} name
 */
export const removeFilter = (state, name) => {
    delete(state.filters[name]);
    state.filters = Object.assign({}, state.filters);
};

/**
 * Remove array of filters from the store.
 *
 * @param  {Object} state
 * @param  {Array} filters
 */
export const removeFilters = (state, filters) => {
    filters.forEach(filter => {
        delete(state.filters[filter]);
    });

    state.filters = Object.assign({}, state.filters);
};

/**
 * Remove all filters from the store.
 *
 * @param  {Object} state
 */
export const clearFilters = (state) => {
    state.filters = {};
};

/**
 * Set errors
 *
 * @param  {Object} state
 * @param  {Object} errors
 */
export const setErrors = (state, errors) => {
    state.errors = new Errors(errors);
}

/**
 * Remove the given error.
 *
 * @param  {Object} state
 * @param  {String} error
 */
export const resetError = (state, error) => {
    state.errors.remove(error);

    state.errors = new Errors(state.errors.all());
}

/**
 * Remove array of errors.
 *
 * @param  {Object} state
 * @param  {Array} errors
 */
export const resetErrors = (state, errors) => {
    errors.map(error => {
        state.errors.remove(error);
    });

    state.errors = new Errors(state.errors.all());
}

/**
 * Remove all errors.
 *
 * @param  {Object} state
 */
export const clearErrors = (state) => {
    state.errors.clear();
}

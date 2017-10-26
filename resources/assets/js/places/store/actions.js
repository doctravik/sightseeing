import QueryString from './../../helpers/QueryString';

/**
 * Get places from db consider current filters (GET request).
 *
 * @param  {Object} options.state vuex
 * @param  {Object} options.commit vuex
 * @return {Promise}
 */
export const getPlaces = ({ state, commit }) => {
    let qs = QueryString.from(state.filters);

    return axios.get('/api/places' +  (qs ? `?${qs}` : '')
            ).then((response) => {
            commit('setPlaces', response.data.data);
            commit('setPaginator', response.data);
            commit('setPlacesCount', response.data.meta.total);
    });
}

/**
 * Store place in db.
 *
 * @param  {Object} options.dispatch vuex
 * @param  {Object} form
 * @return {Promise}
 */
export const storePlace = ({ dispatch }, form) => {
    return form.post('/api/places').then(response => {
        dispatch('getPlaces');
    });
}

/**
 * Update place in db
 *
 * @param  {Object} context
 * @param  {Object} form
 * @param  {String} placeSlug
 * @return {Promise}
 */
export const updatePlace = (context, { form, placeSlug }) => {
    return form.patch('/api/places/' + placeSlug);
}

/**
 * Delete place from db (DELETE request).
 *
 * @param  {Object} options.dispatch vuex
 * @param  {String} placeSlug
 * @return {Promise}
 */
export const deletePlace = ({ dispatch }, placeSlug) => {
    return axios.delete('/api/places/' + placeSlug).then(() => {
            dispatch('getPlaces');
        });
}

/**
 * Upload new image.
 *
 * @param  {Object} context
 * @param  {Object} form
 * @param  {String} placeSlug
 * @return {Promise}
 */
export const uploadImage = (context, { form, placeSlug }) => {
    return form.post('/api/places/' + placeSlug + '/images');
}

/**
 * Delete image of the place.
 *
 * @param  {Object} context vuex
 * @param  {Object} form
 * @param  {String} placeSlug
 * @return {Promise}
 */
export const deleteImage = (context, { form, placeSlug }) => {
    return form.delete('/api/places/' + placeSlug + '/images');
}

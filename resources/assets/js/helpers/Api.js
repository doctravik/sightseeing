class Api
{
    /**
     * Send a Get request to the given URL.
     *
     * @param {String} url
     * @param {Object} config
     * @return {Promise}
     */
    static get(url, config = {}) {
        return (new this).submitWithoutData('get', url, config);
    }

    /**
     * Send a POST request to the given URL.
     *
     * @param {String} url
     * @param {Object} data
     * @param {Object} config
     * @return {Promise}
     */
    static post(url, data, config = {}) {
        return (new this).submitWithData('post', url, data, config);
    }

    /**
     * Send a PUT request to the given URL.
     *
     * @param {String} url
     * @param {Object} data
     * @param {Object} config
     * @return {Promise}
     */
    static put(url, data, config = {}) {
        return (new this).submitWithData('put', url, data, config);
    }


    /**
     * Send a PATCH request to the given URL.
     *
     * @param {String} url
     * @param {Object} data
     * @param {Object} config
     * @return {Promise}
     */
    static patch(url, data, config = {}) {
        return (new this).submitWithData('patch', url, data, config);
    }

    /**
     * Send a DELETE request to the given URL.
     *
     * @param {String} url
     * @param {Object} config
     * @return {Promise}
     */
    static delete(url, config = {}) {
        return (new this).submitWithoutData('delete', url, config);
    }

    /**
     * Submit request with data.
     *
     * @param {String} requestType
     * @param {String} url
     * @param {Object} data
     * @param {Object} config
     * @return {Promise}
     */
    submitWithData(requestType, url, data, config) {
        return this.handleRequest(axios[requestType](url, data, config));
    }

    /**
     * Submit request without data.
     *
     * @param {String} requestType
     * @param {String} url
     * @param {Object} config
     * @return {Promise}
     */
    submitWithoutData(requestType, url, config) {
        return this.handleRequest(axios[requestType](url, config));
    }

    /**
     * @param  {Promise} request
     * @return {Promise}
     */
    handleRequest(request) {
        return request.then(response => {
            this.onSuccess(response);

            return Promise.resolve(response);
        })
        .catch(error => {
            this.onFail(error);

            return Promise.reject(error);
        });
    }


    /**
     * Handle a successful form submission.
     *
     * @param {Object} response
     * @return {void}
     */
    onSuccess(response) {
        flash(response.data.flash);
    }

    /**
     * Handle a failed web request.
     *
     * @param {Object} error
     * @return {void}
     */
    onFail(error) {
        flash(error.response.data.flash, 'is-danger');
    }
}

export default Api;

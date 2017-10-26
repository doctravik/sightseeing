class QueryString
{
    /**
     * Build query string.
     *
     * @param {Object} params
     * @return string
     */
    static from(params) {
       return (new this).build(params);
    }

    /**
     * Transform object to url query string.
     *
     * @param {Object} params
     * @return string
     */
    build(params) {
        return Object.keys(params).map(key => {
            return this.buildQuery(key, params[key]);
        }).join('&');
    }

    /**
     * Build query string
     *
     * @param  {String} key
     * @param  {String} value
     * @return {String}
     */
    buildQuery(key, value) {
        if (value) {
            return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
        }

        return encodeURIComponent(key);
    }
}

export default QueryString;

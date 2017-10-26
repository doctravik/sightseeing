class UrlParser
{
    /**
     * Create a new UrlParser.
     *
     * @param  {String} url
     */
    constructor(url) {
        this.url = url;
        this.pattern = /(\w+)(=([-\s\w.]+)?)?/g;
    }

    /**
     * Parse url and get pairs of key/value.
     *
     * @param  {Object} params
     * @return {Object}
     */
    getParams(params = {}) {
        let match = null;
        let url = decodeURIComponent(this.url);

        while (match = this.pattern.exec(url)) {
            params[match[1]] = match[3] ? match[3] : null;
        }

        return params;
    }
}

export default UrlParser;

class Geocoder
{
    constructor() {
        this.geocoder = new google.maps.Geocoder();
    }

    /**
     * Find geo object by address.
     *
     * @param  {string} address
     * @return {Promise}
     */
    findByAddress(address) {
        return new Promise((resolve, reject) => {
            this.geocoder.geocode({ 'address': address }, (results, status) => {
                if (status == 'OK') {
                    resolve(results);
                } else {
                    reject(status);
                }
            });
        });
    }
}

export default Geocoder;

class Country
{
    static from(place) {
        return new Country().extract(place);
    }

    /**
     * Extract country name from geocoder response object.
     *
     * @param {object} place
     * @param {null} country
     * @return {string|null}
     */
    extract(place, country = null) {
        if (! this.validate(place)) {
            return null;
        }

        for (let i = 0; i < place.address_components.length; i++) {
            if (place.address_components[i].types[0] == 'country') {
                country = place.address_components[i]['long_name'];
                break;
            }
        }

        return country;
    }

    /**
     * Validate place object
     *
     * @param  {object} place
     * @return {boolean}
     */
    validate(place) {
        return place && place.address_components;
    }
}

export default Country;

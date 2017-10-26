class DatabasePlace
{
    static adapt(place, adapter = {}) {
        let self = new this;

        if (place) {
            adapter.country = place.country;
            adapter.address = place.address;
            adapter.description = self.stripTag(place.description);
            adapter.name = place.name;
            adapter.latitude = place.geometry.location.lat;
            adapter.longitude = place.geometry.location.lng;
        }

        return adapter;
    }

    /**
     * Remove tags from text.
     *
     * @param  {string} text
     * @return {string}
     */
    stripTag(text) {
        return text.replace(/<\/?\w+[\s]*?\/?>/gi, '')
    }
}

export default DatabasePlace;

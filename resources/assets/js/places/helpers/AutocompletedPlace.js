import Country from './../../geo/Country';

class AutocompletedPlace
{
    static adapt(place, adapter = {}) {
        if (place) {
            adapter.country = Country.from(place);
            adapter.address = place.formatted_address;
            adapter.name = place.name;
            adapter.description = null;
            adapter.latitude = place.geometry.location.lat();
            adapter.longitude = place.geometry.location.lng();
        }

        return adapter;
    }
}

export default AutocompletedPlace;

import Errors from './../../helpers/Errors';

export default {
    places: [],
    filters: {},
    count: null,
    paginator: null,
    errors: new Errors(),
    allowedFilters: [
        'favorites',
        'page',
        'my' ,
        'author',
        'latitude',
        'longitude',
        'distance',
        'country',
        'name',
        'sort'
    ]
}

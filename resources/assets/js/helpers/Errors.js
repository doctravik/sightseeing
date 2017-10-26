import ObjectCollection from './ObjectCollection';

class Errors extends ObjectCollection
{
    /**
     * Get first item.
     *
     * @param {string} key
     * @return {mixed}
     */
    first(key) {
        let error = this.get(key);

        if (error instanceof Array) {
            return error[0];
        }

        return error;
    }
}

export default Errors;

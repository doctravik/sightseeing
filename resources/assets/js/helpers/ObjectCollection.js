class ObjectCollection
{
    /**
     * @return {void}
     */
    constructor(items = {}) {
        this.items = items;
    }

    /**
     * Set items.
     *
     * @param {object} items
     * @return {void}
     */
    set(items) {
      this.items = items;
    }

    /**
     * Merge current items with new items.
     *
     * @param {object} newItems
     * @return {object}
     */
    merge(newItems) {
        this.items = Object.assign(this.items, newItems);
    }

    /**
     * Get the given property.
     *
     * @param {string} key
     * @return {string}
     */
    get(key) {
      return this.items[key];
    }

    /**
     * Get all properties.
     *
     * @return {object}
     */
    all() {
      return this.items;
    }

    /**
     * Get all properties that has value
     *
     * @return {object}
     */
    filled() {
        return Object.keys(this.items).reduce((carry, filter) => {
            if (this.has(filter)) {
                carry[filter] = this.get(filter);
            }
            return carry;
        }, {});
    }

    /**
     * Add item to collection.
     *
     * @param {string} key
     * @param {string} value
     * @return {void}
     */
    add(key, value) {
        this.items[key] = value;
    }

    /**
     * Remove item from collection
     *
     * @param {string} key
     * @return {void}
     */
    remove(key) {
        if (this.exists(key)) {
            delete this.items[key];
        }
    }

    /**
     * Remove all items from collection
     *
     * @return {void}
     */
    clear() {
        this.items = {};
    }

    /**
     * Reset values of all properties in Object
     *
     * @return {void}
     */
    reset() {
        for(let item in this.items) {
            this.resetItem(item);
        }
    }

    /**
     * Reset property value
     *
     * @param  {string} key
     * @return {void}
     */
    resetItem(key) {
        this.add(key, null);
    }

    /**
     * Checks whether item with the given key exists.
     *
     * @param {string} key
     * @return {boolean}
     */
    exists(key) {
      return this.items.hasOwnProperty(key);
    }

    /**
     * Checks whether item with the given key has any value.
     *
     * @param {string} key
     * @return {boolean}
     */
    has(key) {
      return this.items.hasOwnProperty(key) && !! this.get(key);
    }

    /**
     * Checks whether any properties exists in Object.
     *
     * @return {boolean}
     */
    any() {
      return !! Object.keys(this.items).length;
    }
}

export default ObjectCollection;

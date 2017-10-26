let authorizations = {
    /**
     * Check of user is admin.
     *
     * @return {Boolean}
     */
    isAdmin () {
        return window.app.user.is_admin;
    },

    /**
     * Whether user is owner of model by checking foreign key of the model
     *
     * @param  {Object} model
     * @param  {String} key
     * @return {Boolean}
     */
    isOwner (model, key = 'user_id') {
        return model[key] === window.app.user.id;
    },

    /**
     * Whether user is owner of model by checking nested relations of the model.
     *
     * @param  {Object} model
     * @param  {String} key
     * @return {Boolean}
     */
    isAuthor (model, key = 'author') {
        if (model.hasOwnProperty(key) && model[key] instanceof Object) {
            return model[key].id === window.app.user.id;
        }

        return false;
    }
}

export default authorizations;

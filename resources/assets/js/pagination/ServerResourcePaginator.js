class ServerResourcePaginator
{
    /**
     * Create a new server Laravel resource paginator instance.
     *
     * @param  object paginator
     * @return void
     */
    constructor(paginator) {
        this.paginator = paginator;
    }

    static make(paginator) {
        return (new ServerResourcePaginator(paginator)).adapt();
    }

    adapt() {
        let links = this.paginator.links;

        return {
            'currentPage': this.paginator.meta.current_page,
            'lastPage': this.paginator.meta.last_page,
            'perPage': this.paginator.meta.per_page,
            'total': this.paginator.meta.total,
            'nextUrl': (links && links.next) ? links.next : null,
            'prevUrl': (links && links.prev) ? links.prev : null
        };
    }
}

export default ServerResourcePaginator;

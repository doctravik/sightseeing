class ServerFractalPaginator
{
    /**
     * Create a new server Fractal paginator instance.
     *
     * @param  object paginator
     * @return void
     */
    constructor(paginator) {
        this.paginator = paginator;
    }

    static make(paginator) {
        return (new ServerFractalPaginator(paginator)).adapt();
    }

    adapt() {
        let links = this.paginator.links;

        return {
            'items': [],
            'currentPage': this.paginator.current_page,
            'lastPage': this.paginator.total_pages,
            'perPage': this.paginator.per_page,
            'total': this.paginator.total,
            'count': this.paginator.count,
            'nextUrl': (links && links.next) ? links.next : null,
            'prevUrl': (links && links.previous) ? links.previous : null,
        };
    }
}

export default ServerFractalPaginator;

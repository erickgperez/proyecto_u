export interface Header {
    title: string;
    key: string;
}

export interface SortBy {
    order: boolean | 'asc' | 'desc' | undefined;
    key: string;
}

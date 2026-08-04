function builder_url_query(params){
    const url = new URL(window.location);
    for(let $key in params)
            url.searchParams.set($key, params[$key]);
    return url.toString();
}
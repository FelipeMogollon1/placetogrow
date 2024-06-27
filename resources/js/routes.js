const metaElement = document.head.querySelector('meta[name="base_url"]');
const baseUrl = metaElement.getAttribute('content');
export const routes = {
    admin: {
        microsites: {
            index: baseUrl + '/api/v1/microsite/',
            create: baseUrl + '/api/v1/microsite/',
            delete: baseUrl + '/api/v1/microsite/:id',
            update: baseUrl + '/api/v1/microsite/:id'
        }
    }
}

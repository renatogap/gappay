export function gerarLink(url: string): string {
    if (url.startsWith('/')) {
        return url
    } else {
        return `${import.meta.env.VITE_API_URL}/${url}`
    }
}
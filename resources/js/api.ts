import axios, {AxiosError, AxiosInstance, AxiosResponse} from "axios";

const api: AxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL + '/api',
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
})

const apiNoPrefix: AxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

api.interceptors.response.use(function (response: AxiosResponse<any, any>) {
        return response
    },
    function (error: AxiosError) {

        switch (error.response?.status) {
            // case 401://Não está logado
            //     if (window.location.pathname !== import.meta.env.VITE_API_URL as string) {
            //         alert('Você não está logado. Faça o login e tente novamente')
            //         window.location.href = import.meta.env.VITE_API_URL as string
            //     }
            //     break
            case 419://Sessão expirada
                if (window.location.pathname !== import.meta.env.VITE_API_URL as string) {
                    alert('Sessão expirada. Faça o login e tente novamente')
                }
                break
            case 403://Sem permissão de acesso
                alert('Seu usuário não possui permissão para acessar o recurso solicitado')
                break
            case 405:
                alert('Método http não permitido para esta rota')
                break
            // case 500://está oculto, pois o programador costuma tratar este erro
            //   alert('Falha ao tentar executar seu pedido.')
            //   break
            case 503: //Sistema em manutenção
                alert('Sistema em manutenção. Por Favor, tente mais tarde')
        }

        return Promise.reject(error)
    }
)

export default api;
export { apiNoPrefix }

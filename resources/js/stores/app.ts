// Utilities
import { defineStore } from 'pinia'
import router from '@/router'
import api from '@/helpers/configuracaoBasicaAxios'
import { converterAxiosParaAutenticacao } from '@/helpers/seguranca'
import {LoginResponse, Menu} from '@/types/App'

// Truque para tornar mais simples o retorno do estado inicial da sessão (logout do usuário)
const getDefaultStateInstance = () => {
    return {
        exibirMenu: true,
        somenteIcone: false,
        exibirFiltros: false,
        tema: 'skeletonLightTheme',
        paginaAnterior: '',
        menu: [] as Menu[],
        usuario: {
            nome: '',
            iniciais: '',
            email: '',
            rotas: [] as string[],
            pa: false,
        },
        obrigatorio: [// Não remova nenhum item deste array sob hipótese alguma. Vue guard entrará em loop infinito
            // comece todas as rotas com /
            '',
            // '/',
            // '/login',
            '/login/esqueci-email-senha',
            '/login/atualizar-senha',
            '/senha'
            // '/configuracoes',//obrigatório apenas para usuários autenticados
            // '/configuracoes/senha'//obrigatório apenas para usuários autenticados
        ],
    }
}
const state = getDefaultStateInstance()

export const useAppStore = defineStore('app', {
    state: () => (state),
    actions: {
        autenticarUsuario (autenticacao: LoginResponse): void {
            this.usuario.nome = autenticacao.nome
            this.usuario.email = autenticacao.email
            this.usuario.iniciais = this.usuario.nome.split(' ').map((n: string) => n[0]).join('')
            // obtêm primeira letra do nome e sobrenome
            this.usuario.iniciais = this.usuario.iniciais[0] + this.usuario.iniciais[this.usuario.iniciais.length - 1]
            this.usuario.rotas = autenticacao.rotas
            this.menu = autenticacao.menus
            this.usuario.pa = autenticacao.pa
        },
        isObrigatorio (rota: string): boolean {
            return this.obrigatorio.includes(rota)
        },
        async isUsuarioAutenticado (): Promise<boolean> {
            if (this.usuario.nome !== '' && this.usuario.email !== '') {
                return true
            }

            // tenta autenticar automaticamente (se houver dados em sessionStorage)
            // const email = sessionStorage.getItem('email')
            // const nome = sessionStorage.getItem('nome')

            // if (email !== null && nome !== null) {
            return api.get('/api/usuario/info')
                .then(r => {
                    const autenticacao = converterAxiosParaAutenticacao(r)
                    this.autenticarUsuario(autenticacao)
                    if (this.paginaAnterior !== '' && this.paginaAnterior !== undefined) {
                        router.push(this.paginaAnterior)
                    }

                    return true
                })
                .catch(() => {
                    return false
                })
        },
        async logout (): Promise<void> {

            await api.get('/api/logout')
                .finally(() => {
                    this.limparSessao()
                    router.push({name: 'login'})
                    // router.push((import.meta as any).env.VITE_TELA_LOGIN)
                })
        },
        isPrimeiroAcesso (): boolean {
            return this.usuario.pa
        },
        limparSessao (): void {
            Object.assign(this.$state, getDefaultStateInstance())
        },
        setTema (tema: string): void {
            this.tema = `${tema}LightTheme`
        },
        setPaginaAnterior (pagina: string): void {
            this.paginaAnterior = pagina
        },
    },
})

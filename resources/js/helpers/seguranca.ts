import { useAppStore } from '@/stores/app'
import { AxiosResponse } from 'axios'
import {LoginResponse} from '@/types/App';
import {RouteLocationNormalized} from "vue-router";

const converterAxiosParaAutenticacao = (r: AxiosResponse<LoginResponse>): LoginResponse => {
  return {
    nome: r.data.nome,
    email: r.data.email,
    rotas: r.data.rotas,
    menus: r.data.menus,
    cadastro_incompleto: r.data.cadastro_incompleto,
    pa: r.data.pa
  }
}

const possuiPermissaoRota = (rota: RouteLocationNormalized) => {

    // rota.matched é um array de rotas que correspondem ao caminho atual
    // A última rota é geralmente a mais específica, então usamos ela para verificar permissões
    // Se a rota não tiver matched, usamos a rota atual (rota.path)
    //a primeira retorna rota no formato /usuario/:id/edit (o pinia guarda neste formato)
    // a segunda retorna rota no formato /usuario/123/edit
    const r = rota.matched[rota.matched.length -1]?.path || rota.path
    const store = useAppStore()

    const rotaVue = vueToLaravelRoute(r);

    if (store.isObrigatorio(rotaVue) || rota.meta?.requiresAuth === false) {
        return true
    }

    // verifica se a rota está entre as rotas do usuário ou possui * (todas as rotas)
    return store.usuario.rotas.includes(rotaVue) || store.usuario.rotas.includes('*')
}

/**
 * Verifica se o usuário possui permissão para acessar a rota
 * Usado para exibir botões do grid
 * @param rota
 */
const possuiPermissao = (rota: string | undefined) => {
    if (typeof rota === 'undefined') return false

    const store = useAppStore()

    const rotaVue = vueToLaravelRoute(rota);

    if (store.isObrigatorio(rotaVue)) {
        return true
    }

    // verifica se a rota está entre as rotas do usuário ou possui * (todas as rotas)
    return store.usuario.rotas.includes(rotaVue) || store.usuario.rotas.includes('*')
}

function vueToLaravelRoute(route: string): string {
    return route.replace(/:([^/]+)/g, '{$1}');
}

// const converterUrl = (url: string, id: number) => {
const gerarUrl = (url: string | undefined, id: number) => {
  if (typeof url === 'undefined') return ''
  return url.replaceAll(/:[a-zA-Z_-]+/g, id.toString())
}

export { converterAxiosParaAutenticacao, gerarUrl, possuiPermissao, possuiPermissaoRota }

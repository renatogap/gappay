import {createRouter, createWebHistory, RouteRecordRaw} from "vue-router";
import {useAppStore} from "@/stores/app";
import {possuiPermissaoRota} from "@/helpers/seguranca";

const routes: Array<RouteRecordRaw> = [
    // {
    //     path: '/',
    //     component: () => import('../layouts/default.vue'),
    //     children: [
    //         {
    //             path: '/configuracoes',
    //             component: () => import('../pages/configuracoes/configuracoes-index.vue'),
    //         }
    //     ]
    // },
    // {
    //     path: '/login',
    //     component: () => import('../layouts/duasColunas.vue'),
    //     children: [
    //         {
    //             path: '',
    //             name: 'login',
    //             component: () => import('../pages/login/CaixaLogin.vue'),
    //         },
    //         {
    //             path: 'esqueci-email-senha',
    //             component: () => import('../pages/login/recuperar-senha.vue'),
    //         },
    //         {
    //             path: 'atualizar-senha',
    //             component: () => import('@/pages/login/atualizar-senha.vue'),
    //             meta: {
    //                 requiresAuth: false
    //             },
    //         }
    //     ]
    // },
    {
        path: '/atualizar-senha/:hash',
        component: () => import('../layouts/duasColunas.vue'),
        children: [
            {
                path: '',
                component: () => import('../pages/login/atualizar-senha.vue'),
                props: true,
                meta: {
                    requiresAuth: false
                },
            }
        ]
    },
    {
        path: '/admin/usuario',
        component: () => import('../layouts/default.vue'),
        children: [
            {
                path: '',
                component: () => import('../pages/usuario/usuario-index.vue'),
            },
            {
                path: 'create',
                component: () => import('../pages/usuario/usuario-create.vue'),
            },
            {
                path: ':usuario/edit',
                component: () => import('../pages/usuario/usuario-create.vue'),
                props: true,
            },
        ]
    },
    {
        path: '/admin/perfil',
        component: () => import('../layouts/default.vue'),
        children: [
            {
                path: '',
                name: 'perfil-index',
                component: () => import('../pages/seguranca/perfis/perfis-index.vue'),
            },
            {
                path: 'create',
                name: 'perfil-create',
                component: () => import('../pages/seguranca/perfis/perfis-create.vue'),
            },
            {
                path: ':id/edit',
                props: true,
                component: () => import('../pages/seguranca/perfis/perfis-edit.vue'),
            },
        ]
    },
    {
        path: '/seguranca',
        component: () => import('../layouts/default.vue'),
        children: [
            {
                path: 'acoes',
                component: () => import('../pages/seguranca/acoes/acoes-index.vue'),
            },
            {
                path: 'acoes/create',
                component: () => import('../pages/seguranca/acoes/acoes-create.vue'),
            },
            {
                path: 'acoes/:id/edit',
                component: () => import('../pages/seguranca/acoes/acoes-edit.vue'),
            },
        ],
    },
    {
        path: '/senha',
        component: () => import('../pages/usuario/senha.vue'),
        name: 'atualizar-senha',
    },
    // {
    //     path: '/home',
    //     component: () => import('../layouts/default.vue'),
    //     children: [
    //         {
    //             path: '',
    //             component: () => import('../pages/Home.vue'),
    //             name: 'home',
    //         },
    //     ]
    // },
    {
        path: '/:catchAll(.*)',
        component: () => import('../pages/erros/404.vue'),
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to) => {
    const store = useAppStore()
    const paginaSolicitada = store.paginaAnterior ?? to.path // se não houver página anterior, utiliza a atual

    if (to.name === 'login') {
        const autenticado = await store.isUsuarioAutenticado()

        if (autenticado) {
            if (store.isPrimeiroAcesso()) {
                return {name: 'atualizar-senha'}
            }
            return {name: 'home'}
        }
    } else if (!possuiPermissaoRota(to)) {
        const autenticado = await store.isUsuarioAutenticado()
        if (autenticado) {
            if (store.isPrimeiroAcesso()) {
                return {name: 'atualizar-senha'}
            }
            if (!possuiPermissaoRota(to)) {
                alert('Você não tem permissão para acessar esta página.')
                return false
            }
        } else {// não autenticado e não possui permissão (ainda)
            store.paginaAnterior = paginaSolicitada // salva página anterior
            return {name: 'login'}
        }
    }
})

export default router

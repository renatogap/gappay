// define a menu type
export interface Menu {
    id: number
    nome: string
    acao: string
    icone: string
    submenus: Menu[]
    configuracoes: string
}

// === Menu do Grid ===
export interface PropriedadeBotaoTabela {
    icone?: string
    nome?: string
    link?: string
    acao?: (obj?: any) => void
    // evento: Function;
}

export interface BotoesTabela {
    visualizar?: PropriedadeBotaoTabela
    editar?: PropriedadeBotaoTabela
    excluir?: PropriedadeBotaoTabela
    outros?: PropriedadeBotaoTabela[]
}

// === Itens do Grid Ações ===
export interface ItensAcoes {
    id: number
    method: string
    nome: string
    descricao: string
}

// === Itens do Grid Usuário ===
export interface ItensUsuario {
    id: number
    nome: string
    email: string
    perfil: string[]
    situacao: string[]
    acoes: string[]
    ativo: boolean
}

export interface LoginResponse {
    nome: string
    email: string
    rotas: string[]
    menus: Menu[]
    cadastro_incompleto: boolean,
    pa: boolean
}

export interface PerfisAcoes {
    nome: string,
    filhos: AcoesFilhas[]
}

export interface AcoesFilhas {
    id: number
    nome_amigavel?: string
    descricao?: string
    grupo: string
    total_dependencia: number
}

export interface Header {
    title: string;
    value: string;
    key: string;
    align?: 'start' | 'end' | 'center'
}

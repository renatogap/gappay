import {useTabelaStore} from "@/stores/tabela";
import {ItensUsuario} from "@/types/App";

const storeTabela = useTabelaStore()
const abrirMenuTabela = (item: any) => {
    storeTabela.selecionar(item)
    storeTabela.acao = 'menu' // Define a ação como 'menu' para indicar que o menu foi aberto
    // menuAtivo.value = mobile.value ? !menuAtivo.value : false

}

const excluir = (item: ItensUsuario) => {
    storeTabela.selecionar(item)
    storeTabela.acao = 'excluir' // Define a ação como 'excluir' para indicar que o item será excluído
}

export {
    abrirMenuTabela,
    excluir
}

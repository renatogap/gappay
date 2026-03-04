import { defineStore } from 'pinia'

export const useTabelaStore = defineStore('tabela', {
  state: () => ({
    linhaSelecionada: {} as any,
    acao: '' as string, // Ação que será executada no menu da tabela
  }),
  actions: {
    limparAcao(): void {
        this.acao = ''
    },
    selecionar (linha: any): void {
      this.linhaSelecionada = linha
    },
  },
})

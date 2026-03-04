<script setup lang="ts">

import PcCadastro from "@/components/PcCadastro.vue";
import {onMounted, reactive, ref} from "vue";
import {obrigatorio} from "@/helpers/validacoes";
import api from "@/api";
import {PerfisAcoes} from "@/types/App";
import {useRouter} from "vue-router";
import PcSnackbar from "@/components/PcSnackbar.vue";

const router = useRouter();
const ocupado = ref(false);
const form = reactive({
  nome: '',
  metodo: 'GET',
  permissoes: [] as number[],
});
const tela = reactive({
  grupo: 'Todos' as string,
  grupos: [] as string[],
  dados: [],
  snackbar: false,
  corMensagem: 'error' as 'error' | 'success',
  mensagem: '',
  tempo: 5000,
});
const tabela = ref<PerfisAcoes[]>();

const isUsuarioAdmin = true; // Simulação de verificação de permissão de usuário admin

const estilo = (indicePai: number) => {
  return {
    backgroundColor: indicePai % 2 === 0 ? '#f5f5f5' : '#ffffff',
  };
};
const corChip = (subItem: any) => {
  if (subItem.total_dependencia === 0 && subItem.nome) {
    return 'red lighten-2';
  }
};
const link = (subItem: any) => {
  return `/seguranca/acoes/${subItem.id}/edit`
};

const filtrar = (indicePai: number) => {
  if (tela.grupo === 'Todos') {
    return true
  }
  else if (tela.grupo !== '') {
    return tabela.value && tabela.value[indicePai].nome === tela.grupo
  } else {
    return true
  }
};
const salvar = () => {
  ocupado.value = true
  api.post('/perfil', form)
      .then((r) => {
        tela.mensagem = r.data.message
        tela.corMensagem = 'success';
        tela.snackbar = true;

        // Redirecionar para a página de edição do perfil recém-criado depois de 3 segundos
        setTimeout(() => {
          router.push(`/admin/perfil/${r.data.perfil}/edit`);
        }, 3000);
      })
      .catch((error) => {
        tela.snackbar = true;
        tela.mensagem = error.response?.data?.message || 'Erro ao salvar perfil';
        tela.corMensagem = 'error';
      })
      .finally(() => {
        ocupado.value = false
      });
};

onMounted(() => {
  ocupado.value = true
  api.get('/perfil/create')
      .then(({data}) => {
        tela.grupos = data.data.grupos
        tabela.value = data.data.destaque
      })
      .finally(() => {
        ocupado.value = false
      });
})


</script>

<template>
  <v-container>
    <pc-snackbar
      v-model="tela.snackbar"
      :cor-mensagem="tela.corMensagem"
      :mensagem="tela.mensagem"
      :tempo="tela.tempo"
      @update:ativar="tela.snackbar = $event"
    />
    <pc-cadastro @submit="salvar">
      <template #titulo>
        Cadastro de Perfil
      </template>

      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <v-text-field
            label="Nome do Perfil"
            v-model="form.nome"
            :rules="[obrigatorio]"
          />
        </v-col>
      </v-row>

      <v-table>
        <template #default>
          <thead>
            <tr>
              <th class="d-flex">
                <v-autocomplete
                  class="align-center mt-2"
                  label="Grupo"
                  v-model="tela.grupo"
                  :items="tela.grupos"
                  density="compact"
                />
              </th>
              <th v-if="isUsuarioAdmin">
                Dependências
              </th>
              <th>Permissão</th>
              <th>Descrição</th>
            </tr>
          </thead>

          <tbody>
            <template
              v-for="(item, indicePai) in tabela"
              :key="indicePai"
            >
              <tr
                v-for="(subItem, index) in item.filhos"
                :key="subItem.id"
                v-show="filtrar(indicePai)"
                :style="estilo(indicePai)"
              >
                <td
                  v-if="index === 0"
                  :rowspan="item.filhos.length"
                  class="text-h6"
                >
                  {{ item.nome }}
                </td>
                <td v-if="isUsuarioAdmin">
                  <v-btn
                    :color="corChip(subItem)"
                    fab
                    size="small"
                    :to="link(subItem)"
                    class="elevation-0"
                  >
                    {{ subItem.total_dependencia }}
                  </v-btn>
                </td>
                <td>
                  <label
                    style="cursor: pointer"
                  ><input
                    type="checkbox"
                    style="cursor: pointer"
                    :value="subItem.id"
                    v-model="form.permissoes"
                  > {{ subItem.nome_amigavel }}</label>
                </td>
                <td>{{ subItem.descricao }}</td>
              </tr>
            </template>
          </tbody>
        </template>
      </v-table>

      <v-btn
        icon="mdi-content-save"
        color="primary"
        size="large"
        class="fab"
        elevation="4"
        :loading="ocupado"
        type="submit"
      />
    </pc-cadastro>
  </v-container>
</template>

<style scoped>
.fab {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 1000;
}
</style>

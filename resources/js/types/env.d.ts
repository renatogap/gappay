/// <reference types="vite/client" />
interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
    readonly VITE_APP_NOME_SISTEMA: string;
    readonly VITE_API_URL: string;
    readonly VITE_API_TELA_LOGIN: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}

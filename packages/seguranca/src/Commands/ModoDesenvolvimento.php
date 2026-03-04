<?php

namespace GapPay\Seguranca\Commands;

use Illuminate\Console\Command;

class ModoDesenvolvimento extends Command
{
    private string $arquivoOriginal;
    private string $conteudoArquivo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seguranca:modo-dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura o composer.json para que o segurança esteja em modo desenvolvimento.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
//        $this->fazerBackup();
        $this->imprimir("Configurando composer para usar segurança do diretório packages na raiz do projeto dev-master");

        $this->arquivoOriginal = base_path('composer.json');
        $this->configurarComposerParaUsarLinkSimbolico();
        $this->configurarVersaoDoSeguranca();

        file_put_contents(base_path('composer.json'), $this->conteudoArquivo);

        return 0;
    }

//    public function fazerBackup(): void
//    {
//        $this->arquivoOriginal = base_path('composer.json');
//        $arquivoOriginalBkp = base_path('composer_prod.json');
//
//        $this->imprimir("Configurando segurança para o modo produção (padrão)");
//
//        if (!file_exists($arquivoOriginalBkp)) {//copia o arquivo somente se ainda existir
//            if (!copy($this->arquivoOriginal, $arquivoOriginalBkp)) {
//                $this->imprimir("Falha ao copiar composer.json");
//            }
//        }
//    }

    public function configurarComposerParaUsarLinkSimbolico(): void
    {
        $pesquisarPor = '{
            "type": "vcs",
            "url": "ssh://administrador@10.74.0.123/home/administrador/repos/seguranca.git"
        }';

        $substituirPor = '{
            "type": "path",
            "url": "packages/policiacivil/seguranca",
            "options": {
                "symlink": true
            }
        }';

        $txt = file_get_contents($this->arquivoOriginal);
        $this->conteudoArquivo = str_replace($pesquisarPor, $substituirPor, $txt);
    }

    public function configurarVersaoDoSeguranca(): void
    {
        $encontrado = preg_match('|(?<="policiacivil/seguranca": ")[a-z0-9-.*]*(?=")|', $this->conteudoArquivo, $ocorrencias);

        if ($encontrado) {
            $this->conteudoArquivo = str_replace(
                "\"policiacivil/seguranca\": \"$ocorrencias[0]\",",
                "\"policiacivil/seguranca\": \"dev-master\",",
                $this->conteudoArquivo
            );
        }
    }

    public function imprimir($txt): void
    {
        echo "$txt" . PHP_EOL;
    }
}

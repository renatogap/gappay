<?php

namespace GapPay\Seguranca\Commands;

use Illuminate\Console\Command;

class ModoProducao extends Command
{
    private string $arquivoOriginal;
    private string $conteudoArquivo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seguranca:modo-prod {versao=dev-master}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura o composer.json para que o segurança esteja em modo produção (modo padrão)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->imprimir("Restaurando configuração para baixar segurança do git na versão "
            . $this->argument('versao'));

        $this->arquivoOriginal = base_path('composer.json');

        $this->configurarComposerParaUsarGit();
        $this->configurarVersaoDoSeguranca($this->argument('versao'));
        file_put_contents($this->arquivoOriginal, $this->conteudoArquivo);
        return 0;
    }

    public function configurarComposerParaUsarGit(): void
    {
        $pesquisarPor = '{
            "type": "path",
            "url": "packages/policiacivil/seguranca",
            "options": {
                "symlink": true
            }
        }';

        $substituirPor = '{
            "type": "vcs",
            "url": "ssh://administrador@10.74.0.123/home/administrador/repos/seguranca.git"
        }';

        $txt = file_get_contents($this->arquivoOriginal);
        $this->conteudoArquivo = str_replace($pesquisarPor, $substituirPor, $txt);
    }

    public function configurarVersaoDoSeguranca(string $versao): void
    {
        $encontrado = preg_match('|(?<="policiacivil/seguranca": ")[a-z0-9-.*]*(?=")|', $this->conteudoArquivo, $ocorrencias);

        if ($encontrado) {
            $this->conteudoArquivo = str_replace(
                "\"policiacivil/seguranca\": \"$ocorrencias[0]\",",
                "\"policiacivil/seguranca\": \"$versao\",",
                $this->conteudoArquivo
            );
        }
    }

//    public function restaurarBackup(): void
//    {
//        $arquivoBackup = base_path('composer_prod.json');
//
//        if (!file_exists($arquivoBackup)) {
//            $this->imprimir("Não há composer_prod.json para restaurar");
//        } else {
//            rename('composer.json', 'composer_dev.json');
//            rename('composer_prod.json', 'composer.json');
//            unlink('composer_dev.json');
//        }
//    }

    public function imprimir($txt): void
    {
        echo "$txt" . PHP_EOL;
    }
}

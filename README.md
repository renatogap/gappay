## Projeto Skeleton
Este projeto foi criado com o intuito de servir como base para projetos que usam o framework Laravel integrado com o módulo de Segurança.

## Instalação
Altere o código deste sistema no arquivo: ```config/policia.php```
```php
...
'codigo' => 35// 35 é o código do município do Skeleton e não deve ser usado em outros projetos
...
```

## Comandos úteis
Cria um autocomplete para as facades do Laravel
```bash
php artisan ide-helper:generate
```

Cria documentação para os models. O parâmetro -RW sobrescreve a documentação existente.
```bash
php artisan ide-helper:models -RW
```
Fonte: [Laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper?tab=readme-ov-file#phpstorm-meta-for-container-instances)

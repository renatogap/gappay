class AdminUsuarioController {
    constructor() {
        this._tabela = document.getElementById('grid');
        //this._reativar = document.getElementById('reativar');
        //this._reativarTooltip = this._reativar.Tooltip;
    }
    
    pesquisar(e) {
        e.preventDefault();
        
    }  
    
    criar() {
        window.location = `${BASE_URL}admin/usuario/criar`;
    }

    /*editar(e) {
        
            
        } else {
            alert('Selecione um item');
            return false;
        }
    }*/

    editar(id) {
        window.location = `${BASE_URL}admin/usuario/editar/${id}`;
    }
        
    excluir(id) {
        
        if (!confirm('Deseja realmente EXCLUIR este usuário?')){
            return false;
        }
        
                    
        $.ajax({
            url: `${BASE_URL}admin/usuario/excluir/`+id,
            type: 'post',
            data: {
                id: id,
                _token: document.getElementsByName('_token')[0].value
            },
            success: (response) => {
                alert(response.message);
                window.location.reload();
            },
            error: (response) => {
                alert(response.error);
            }
        });
    }

    reativar(usuarioId, usuarioNome, usuarioStatus) {
        let acao = usuarioStatus === 1 ? 'DESATIVAR' : 'REATIVAR';

        if (!confirm(`Deseja ${acao} o usuário ${usuarioNome} ?`)){
            return false;
        }

        Ajax.ajax({
            url: `${BASE_URL}admin/usuario/reativar/${usuarioId}`,
            method: 'post',
            data: {
                _token: document.getElementsByName('_token')[0].value,
                ativo: !usuarioStatus
            },
            beforeSend: () => {
                this._reativar = true;
            },
            success: (response) => {
                alert(response.message);
                window.location.reload();
            },
            error: (response) => {
                alert(response.message);
            }
        });
    }
}
@extends('layouts.default')
@section('conteudo')
<h5 class="mb-3">
    Pesquisar Usuários

    <a href="{{url('')}}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
        keyboard_backspace
    </a>
</h5>


<form id="form" method="get" action="{{url('admin/usuario')}}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <input type="text" id="nome" name="nome" value="{{ $nome }}" class="form-control form-control-sm" placeholder="Nome">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="email" id="email" name="email" value="{{ $email }}" class="form-control form-control-sm" placeholder="E-mail">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 mr-5">
                <button type="submit" id="pesquisar" class="btn btn-primary btn-sm">Pesquisar</button>

                <button id="novo" type="button" class="btn btn-success btn-sm" onclick="oController.criar(this)" title="Criar novo usuário">
                    <i class="material-icons icone">add</i> Novo Usuário
                </button>
            </div>
        </div>
    </div>
    <table id="grid" class="table table-striped table-hover table-sm" width="100%">
        <thead class="bg-primary text-white">
            <tr>
                <th width="75%">Nome</th>
                <th width="15%"></th>
            </tr>

        </thead>
        <tbody>
            @foreach($usuarios as $user)
            <tr>
                <td>
                    <b>{{ $user->nome }}</b><br>
                    {{ $user->email }}<br>
                    <span class="text-muted">{{ $user->perfil }}</span><br>
                    {!! $user->ativo == 1 ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Inativo</span>' !!}
                </td>

                <td class="text-right">
                    <button type="button" class="btn text-primary" title="Edita" onclick="oController.editar(<?= $user->id ?>)">
                        <i class="material-icons icone">edit</i>
                    </button>
                    <button id="excluir" type="button" class="btn text-success" onclick="oController.reativar(<?= $user->id ?>, '<?= $user->nome ?>', <?= $user->ativo ?>)" title="Remove">
                        <i class="material-icons icone">
                            lock_reset
                        </i>
                    </button>

                    <button id="excluir" type="button" class="btn text-danger" onclick="oController.excluir(<?= $user->id ?>)" title="Remove">
                        <i class="material-icons icone">delete</i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
@endsection
@section('scripts')
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/app/models/Ajax.js')}}"></script>
<script src="{{asset('js/app/controllers/AdminUsuarioController.js')}}"></script>
<!--@include('layouts.datatables', ['carregamento_inicial' => true, 'colunas' => ['nome', 'email']])-->
<script>
    oController = new AdminUsuarioController();
</script>
@endsection
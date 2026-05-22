@extends('layouts.default')
@section('conteudo')
    <h5>
        <span class="material-icons icone">person_add</span> Cadastro de Responsável
        <a href="{{ url('') }}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
            keyboard_backspace
        </a>
    </h5>
    <hr>

    @if (session('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('error') !!}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('success') !!}
        </div>
    @endif

    @php
        $step = $step ?? 1;
        $step = in_array($step, [1, 2, 3]) ? $step : 1;
        $progress = $step === 1 ? 33 : ($step === 2 ? 66 : 100);
    @endphp

    {{-- <div class="mb-4">
        <div class="d-flex justify-content-between mb-2">
            <strong>Passo {{ $step }} de 3</strong>
            <small>
                @if($step === 1) Informe somente o e-mail do responsável.
                @elseif($step === 2) Valide o e-mail usando o token recebido.
                @else Cadastre os dados pessoais e os alunos.
                @endif
            </small>
        </div>
        <div class="progress" style="height: 12px;">
            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div> --}}

    {{-- STEPPERS --}}
    <div class="cadastro-steps mb-4">
        <div class="step {{ $step >= 1 ? 'active' : '' }} {{ $step > 1 ? 'done' : '' }}">
            <div class="circle">
                @if($step > 1)
                    <span class="material-icons">check</span>
                @else
                    1
                @endif
            </div>
            <div class="label">Informar<br>e-mail</div>
        </div>

        <div class="line"></div>

        <div class="step {{ $step >= 2 ? 'active' : '' }} {{ $step > 2 ? 'done' : '' }}">
            <div class="circle">
                @if($step > 2)
                    <span class="material-icons">check</span>
                @else
                    2
                @endif
            </div>
            <div class="label">Validar<br>e-mail</div>
        </div>

        <div class="line"></div>

        <div class="step {{ $step >= 3 ? 'active' : '' }}">
            <div class="circle">3</div>
            <div class="label">Dados<br>pessoais</div>
        </div>
    </div>
    {{-- FIM STEPPERS --}}

    <form action="{{ url('cliente/cadastro/store') }}" method="POST">
        @csrf
        <input type="hidden" name="step" value="{{ $step }}">
        <input type="hidden" name="responsavel_id" value="{{ old('responsavel_id', request('responsavel_id')) }}">

        @if ($step === 1)
            <div class="form-group">
                <label for="email">E-mail do responsável</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                Enviar token por e-mail
            </button>
        @elseif ($step === 2)
            <div class="form-group">
                <label for="email">E-mail cadastrado</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', request('email')) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="token">Token recebido por e-mail</label>
                <input type="text" class="form-control @error('token') is-invalid @enderror" id="token" name="token" value="{{ old('token') }}" required>
                @error('token') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                Validar token
            </button>
        @else
            <div class="form-group">
                <label for="nome">Nome completo</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required>
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" required>
                @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control @error('senha') is-invalid @enderror" id="senha" name="senha" required>
                    @error('senha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="senha_confirmation">Confirmar senha</label>
                    <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" required>
                </div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input @error('termos') is-invalid @enderror" id="termos" name="termos" value="1" {{ old('termos') ? 'checked' : '' }} required>
                <label class="form-check-label" for="termos">Li e aceito os termos de uso</label>
                @error('termos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <hr>

            <h6 class="mb-2" style="font-weight: 600;">
                <span class="material-icons" style="font-size: 1em; vertical-align: middle;">school</span>
                Alunos
            </h6>

            <div id="lista-alunos">
                @if(old('alunos'))
                    @foreach(old('alunos') as $index => $aluno)
                        <div class="aluno-item card card-body mb-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Aluno #{{ $index + 1 }}</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remover-aluno">
                                    <span class="material-icons" style="font-size: 1em;">delete</span>
                                </button>
                            </div>
                            <div class="form-group">
                                <label>Nome do aluno</label>
                                <input type="text" class="form-control" name="alunos[{{ $index }}][nome]" value="{{ $aluno['nome'] ?? '' }}" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Série</label>
                                    <input type="text" class="form-control" name="alunos[{{ $index }}][serie]" value="{{ $aluno['serie'] ?? '' }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Matrícula</label>
                                    <input type="text" class="form-control" name="alunos[{{ $index }}][matricula]" value="{{ $aluno['matricula'] ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="aluno-item card card-body mb-3 p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Aluno #1</strong>
                        </div>
                        <div class="form-group">
                            <label>Nome do aluno</label>
                            <input type="text" class="form-control" name="alunos[0][nome]" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Série</label>
                                <input type="text" class="form-control" name="alunos[0][serie]" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Matrícula</label>
                                <input type="text" class="form-control" name="alunos[0][matricula]" required>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <button type="button" class="btn btn-outline-primary btn-block mb-3" id="btn-add-aluno">
                <span class="material-icons" style="font-size: 1em; vertical-align: middle;">add</span>
                Adicionar aluno
            </button>

            <button type="submit" class="btn btn-success btn-block">
                Concluir cadastro
            </button>
        @endif
    </form>
@endsection

@section('scripts')
@if($step === 3)
<script>
    var indexAluno = {{ old('alunos') ? count(old('alunos')) : 1 }};

    document.getElementById('btn-add-aluno').addEventListener('click', function () {
        var i = indexAluno++;
        var html = `
            <div class="aluno-item card card-body mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Aluno #${i + 1}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remover-aluno">
                        <span class="material-icons" style="font-size: 1em;">delete</span>
                    </button>
                </div>
                <div class="form-group">
                    <label>Nome do aluno</label>
                    <input type="text" class="form-control" name="alunos[${i}][nome]" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Série</label>
                        <input type="text" class="form-control" name="alunos[${i}][serie]" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Matrícula</label>
                        <input type="text" class="form-control" name="alunos[${i}][matricula]" required>
                    </div>
                </div>
            </div>`;

        document.getElementById('lista-alunos').insertAdjacentHTML('beforeend', html);
    });

    document.getElementById('lista-alunos').addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-remover-aluno');
        if (btn) {
            btn.closest('.aluno-item').remove();
        }
    });
</script>
@endif
@endsection
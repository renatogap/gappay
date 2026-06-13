@extends('layouts.default')
@section('conteudo')
    <h5>
        <span class="material-icons icone">person_add</span> Cadastro de Responsável
        @if($step == 1)
            <a href="{{ url('cliente/login') }}" class="material-icons float-right" style="font-size: 1.3em; color: #333;">
                keyboard_backspace
            </a>
        @endif
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
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', request('email')) }}" required readonly>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Insira o código recebido no e-mail</label>
                <div class="d-flex justify-content-center gap-2" id="token-inputs" style="gap: 10px;">
                    @for($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                            class="form-control text-center font-weight-bold"
                            style="width: 48px; height: 56px; font-size: 1.5em; border-radius: 8px;"
                            data-index="{{ $i }}">
                    @endfor
                </div>
                <input type="hidden" name="token" id="token" value="{{ old('token') }}">
                @error('token') <div class="text-danger mt-1 text-center">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                Validar token
            </button>
        @else
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nome">Nome completo</label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required>
                    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}"  maxlength="16" placeholder="(99) 9 9999-9999" required>
                    @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control @error('senha') is-invalid @enderror" id="senha" name="senha" minlength="6" required>
                    @error('senha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" minlength="6" required>
                    @error('confirmar_senha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input @error('termos') is-invalid @enderror" id="termos" name="termos" value="1" {{ old('termos') ? 'checked' : '' }} required>
                <label class="form-check-label" for="termos">Li e aceito os termos de uso</label>
                @error('termos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-success btn-block">
                Concluir cadastro
            </button>
        @endif
    </form>
@endsection

@section('scripts')
@if($step === 2)
<script>
    const inputs = document.querySelectorAll('#token-inputs input');
    const hidden = document.getElementById('token');

    function syncHidden() {
        hidden.value = Array.from(inputs).map(i => i.value).join('');
    }

    inputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
            syncHidden();
            if (this.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            var paste = (e.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, 6);

            paste.split('').forEach(function(char, i) {
                if (inputs[i]) inputs[i].value = char;
            });
            syncHidden();

            var next = inputs[Math.min(paste.length, inputs.length - 1)];
            if (next) next.focus();
        });
    });
</script>
@endif

@if($step === 3)
<script>
   var telInput = document.getElementById('telefone');

    function mascararTelefone(valor) {
        var v = valor.replace(/\D/g, '').slice(0, 11);
        if (v.length <= 10) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else {
            v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        }
        return v;
    }

    telInput.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace') {
            var v = this.value;
            if (/[\s\-\(\)]$/.test(v)) {
                this.value = v.slice(0, -1);
            }
        }
    });

    telInput.addEventListener('input', function () {
        this.value = mascararTelefone(this.value);
    });

    telInput.value = mascararTelefone(telInput.value);

    document.querySelector('form').addEventListener('submit', function (e) {
        document.querySelectorAll('.erro-nome').forEach(function (el) { el.remove(); });

        var nomeInput = document.querySelector('input[name="nome"]');
        var nome = nomeInput ? nomeInput.value.trim() : '';
        var tokens = nome.replace(/\s+/g, ' ').split(' ').filter(function (t) { return t.length > 0; });
        var primeiro = tokens[0] || '';
        var ultimo = tokens[tokens.length - 1] || '';
        var mensagem = null;

        nomeInput.classList.remove('is-invalid');

        if (tokens.length < 2) {
            mensagem = 'Informe o nome e sobrenome do responsável.';
        } else if (primeiro.length < 3) {
            mensagem = 'O nome deve ter pelo menos 3 letras.';
        } else if (ultimo.length < 3) {
            mensagem = 'O sobrenome deve ter pelo menos 3 letras.';
        }

        if (mensagem) {
            e.preventDefault();
            nomeInput.classList.add('is-invalid');
            var div = document.createElement('div');
            div.className = 'invalid-feedback erro-nome';
            div.textContent = mensagem;
            nomeInput.after(div);
            nomeInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endif

@endsection
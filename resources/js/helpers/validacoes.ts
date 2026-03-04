export const obrigatorio = (v: string): string | boolean => !!v || 'Campo obrigatório'
export const arrayNaoVazio = (v: string): string | boolean => (Array.isArray(v) && (v.length > 0)) || 'Este campo não ficar vazio'

export const obrigatorioBoolean = (v: string): string | boolean => (typeof v === 'boolean') || 'Campo obrigatório'

export const tamanhoCPf = (v: string): string | boolean => {
    // Se estiver vazio, não valida
    if (!v) return true

    // Remove caracteres não numéricos
    const cpf = v.replace(/[^\d]/g, '')

    // Verifica se tem 11 dígitos
    if (cpf.length !== 11) return 'CPF incompleto'

    // Verifica se todos os dígitos são iguais (CPF inválido)
    if (/^(\d)\1{10}$/.test(cpf)) return 'CPF inválido'

    // Validação do primeiro dígito verificador
    let soma = 0
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i)
    }

    let resto = soma % 11
    const digito1 = resto < 2 ? 0 : 11 - resto

    if (parseInt(cpf.charAt(9)) !== digito1) return 'CPF inválido'

    // Validação do segundo dígito verificador
    soma = 0
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i)
    }

    resto = soma % 11
    const digito2 = resto < 2 ? 0 : 11 - resto

    if (parseInt(cpf.charAt(10)) !== digito2) return 'CPF inválido'

    // CPF válido
    return true
}

export const validateCheckbox = (v: any) => {
  return v.some((isChecked: any) => isChecked) || 'Campo obrigatório'
}

export const obrigatorioTabela = (v: string): string | boolean => {
  if (v!) {
    return true
  }
  return 'campo obrigatorio'
}

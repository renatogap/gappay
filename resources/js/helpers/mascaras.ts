const mascaraCpf = (txt: string) => {
  // usado quando o usuário cola um cpf sem formatação
  if (txt.match(/\d{11}/)) return txt.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/g, '$1.$2.$3-$4')

  return txt.replace(/^(\d{3})(\d)$/g, '$1.$2')
    .replace(/^(\d{3}\.\d{3})(\d+)$/g, '$1.$2')
    .replace(/^(\d{3}\.\d{3}\.\d{3})(\d+)$/g, '$1-$2')
}

export {
  mascaraCpf,
}

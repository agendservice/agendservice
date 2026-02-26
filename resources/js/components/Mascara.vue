<script>
export default {
    // Remove tudo que não é número
    limpar: function(valor) {
        if (!valor) return '';
        return valor.toString().replace(/\D/g, '');
    },
    texto: function(valor) {
        if (!valor) return '';
        return valor.toString().replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    },
    cpf: function(valor) {
        if (!valor) return '';
        valor = this.limpar(valor);
        
        // Limita ao tamanho máximo
        if (valor.length > 11) valor = valor.slice(0, 11);

        // Aplica a máscara: 000.000.000-00
        return valor
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    },

    cnpj: function(valor) {
        if (!valor) return '';
        valor = this.limpar(valor);
        
        if (valor.length > 14) valor = valor.slice(0, 14);
        
        // Aplica a máscara: 00.000.000/0000-00
        return valor
            .replace(/^(\d{2})(\d)/, '$1.$2')
            .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
            .replace(/\.(\d{3})(\d)/, '.$1/$2')
            .replace(/(\d{4})(\d)/, '$1-$2');
    },

    telefone: function(valor) {
        if (!valor) return '';
        valor = this.limpar(valor);
        
        if (valor.length > 11) valor = valor.slice(0, 11);

        // Máscara dinâmica (8 ou 9 dígitos)

        // (00) 0000-0000 ou (00) 00000-0000
        if (valor.length <= 10) {
            return valor.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
        } else {
            return valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        }
    },

    cep: function(valor) {
        if (!valor) return '';
        valor = this.limpar(valor);
        if (valor.length > 8) valor = valor.slice(0, 8);

        // 00000-000
        return valor.replace(/^(\d{5})(\d)/, '$1-$2');
    },
    moeda: function(valor) {
        // 1. Limpa o valor, mantendo apenas dígitos (0-9)
        let digitos = valor.replace(/\D/g, "");

        // Se o valor estiver vazio após a limpeza (ex: usuário apagou tudo), retorna string vazia
        if (!digitos) {
            return "";
        }

        // 2. Converte a string de dígitos em um número inteiro (tratado como centavos)
        let centavos = parseInt(digitos, 10);

        // 3. Divide por 100 para posicionar a vírgula (centavos)
        let numero = centavos / 100;

        console.log(numero, typeof numero);
        // 4. Formata como número no padrão pt-BR sem símbolo de moeda
        return new Intl.NumberFormat('pt-BR', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(numero);
    },
    numero: function(valor) {
        if (!valor) return '';
        return valor.toString().replace(/\D/g, '');
    },
    percentual: function(valor) {
        if (!valor) return '';
        valor = this.limpar(valor);
        if (valor.length > 3) valor = valor.slice(0, 3);
        return valor;
    },
    
    // Função genérica se você quiser passar o padrão manualmente (estilo vue-the-mask)
    generica: function(valor, pattern) {
        // Lógica complexa de replace caractere a caractere
        // (Geralmente é melhor usar bibliotecas para genéricos, mas para fixos o Regex acima é melhor)
        return valor; 
    }
}
</script>
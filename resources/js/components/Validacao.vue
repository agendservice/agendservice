<script>
export default {
    required: value => !!value || "Campo Obrigatório!",
    requiredIf: function(value, condition) {
        if (condition) {
            return this.required(value);
        }
        return true;
    },
    cpfValido: function(value) {
        let re = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/g;
        if (value && !re.test(value)) {
            return "CPF inválido";
        }

        return true;
    },
    cnpjValido: function(value) {
        let re = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/g;
        if (value && !re.test(value)) {
            return "CNPJ inválido";
        }

        return true;
    },
    telefoneValido: function(value) {
        let re = /^\(\d{2}\) \d{4,5}-\d{4}$/g;
        if (value && !re.test(value)) {
            return "Telefone inválido";
        }

        return true;
    },
    emailValido: function(value) {
        let re = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
        if (value && !re.test(value)) {
            return "Email inválido";
        }

        return true;
    },
    requiredObject: function(value) {
        if (typeof value == "undefined" ||  (value && Object.keys(value).length == 0)) {
            return "Campo Obrigatório!";
        }
        return true;
    },
    requiredObjectIf: function(value, condition) {
        if (condition) {
            return this.requiredObject(value);
        }
        return true;
    },
    requiredObjectIf_message: function (value, condition, message) {
        if (condition) {
            if(this.requiredObject(value) === true) {
                return true;
            } else {
                return message;
            }
        }
        return true;
    },
    matchPasswords: function(password, confirmationPassword) {
        if (password != confirmationPassword) {
            return "Senhas diferentes";
        }
        return true;
    },
    minMoney: function(value) {
        if (value && value.indexOf('-') < 0) {
            return true;
        }
        return "Valor mínimo: 0,01";
    },
    maiorQue: function(value, val) {
        if (value > val) {
            return true;
        }
        return "Valor deve ser maior que " + val;
    },
    maiorQueOuVazio: function(value, val) {
        if (!value) return true;
        if (value > val) {
            return true;
        }
        return "Valor deve ser maior que " + val;
    },
    menorIgualQue: function(value, val) {
        if (value && value.indexOf(',') > 0) {
            value = Number(value.replaceAll('.', '').replaceAll(',', '.'));
        }
        if (value <= val) {
            return true;
        }
        return "Valor deve ser menor ou igual a " + val;
    },

    igualQue: function(value, val) {
        
        return "Valor deve ter " + val + " digitos";
    },
    zeroMoney: function(value) {
        let re = /([1-9])+/gi;
        if (value && value.match(re) == null) {
            return "Informe um valor maior que 0";
        }
        return true;
    },
    checkRegex: function(valor, condicao, mensagem) {
        let re = new RegExp(condicao, 'g');
        if (valor && !re.test(valor)) {
            return mensagem;
        }

        return true;
    },
    checkEmail: function(valor) {
        let re = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
        if (valor && !re.test(valor)) {
            return "Email inválido";
        }

        return true;
    },
    checkDecimalDigits: function(valor, maxAntesPonto, maxDepoisPonto) {
        let exp = `^\\d{1,${maxAntesPonto}}(?:\\.\\d{1,${maxDepoisPonto}})?$`;
        return this.checkRegex(valor, exp, `Até ${maxAntesPonto} dígitos antes da vírgula e ${maxDepoisPonto} após.`);
    },
    checkDecimaisDigits: function(valor, maxAntesPonto, maxDepoisPonto) {
        let exp = `^\\d{1,${maxAntesPonto}}(?:\\,\\d{1,${maxDepoisPonto}})?$`;
        return this.checkRegex(valor, exp, `Até ${maxAntesPonto} dígitos antes da vírgula e ${maxDepoisPonto} após.`);
    },
    rangeSize: function (value, sizeMin, sizeMax) {
        if (value) {
            if (value.length >= sizeMin) {
                if (value.length <= sizeMax) {
                    return true;
                }
                return "Tamanho máximo: "+sizeMax;
            }
            return "Tamanho mínimo: "+sizeMin;
        }
        return true;
    },

    maxSize: function (value, sizeMax) {
        if (value) {
            if (value.length <= sizeMax) {
                return true;
            }
            return "Tamanho máximo: "+sizeMax;
        }
        return true;
    },
    
    sizeCNPJ: function (value, sizeCNPJ) {
        if (value) {
            if (value.length === sizeCNPJ) {
                return true;
            }
            return "CNPJ deve ter: "+sizeCNPJ+" dígitos";
        }
        return false;
    },

    sizeCPF: function (value, sizeCPF) {
        if (value) {
            if (value.length === sizeCPF) {
                return true;
            }
            return "CPF deve ter: "+sizeCPF+" dígitos";
        }
        return false;
    },

    sizeTelefone: function (value, sizeTelefone) {
        if (value) {
            if (value.length === sizeTelefone) {
                return true;
            }
            return "Telefone deve ter: "+sizeTelefone+" dígitos";
        }
        return false;
    },
    requiredAndNotNull: function (value) {
        if (value && (value.trim()).length > 0) {
            return true;
        }
        return "Campo Obrigatório!";
    },
    requiredNotNull: function (value) {
        if (value && (value.trim()).length == 0) {
            return "Campo não poderá ser preenchido com vazio.";
        }
        
        return true;
    },
    requiredAndNotNullIf: function(value, condition) {
        if (condition) {
            return this.requiredAndNotNull(value);
        } else {
            return true;
        }
    },
    showMessageErrorIf: function (message, condition) {
        if (condition) {
            return message;
        }
        return true;
    },
    validateDate: function (value) {
        if (!value) {
            return true;
        }
        if (value.length !== 10) {
            return "Formato de data inválido";
        }

        const parts = value.split('/');
        if (parts.length !== 3) {
            return "Formato de data inválido";
        }

        const dia   = Number(parts[0]);
        const mes   = Number(parts[1]);
        const ano   = Number(parts[2]);

        if (!Number.isInteger(ano) || ano < 1900) {
            return "Formato de ano inválido";
        }

        if (!Number.isInteger(mes) || mes < 1 || mes > 12) {
            return "Formato de mês inválido";
        }

        const diasNoMes = new Date(ano, mes, 0).getDate();
        if (!Number.isInteger(dia) || dia < 1 || dia > diasNoMes) {
            return "Formato de dia inválido para o mês informado";
        }

        return true;
    },
    validateTime: function (value) {
        if (value) {
            if (value.length == 5) {
                let hora = Number(value.substr(0,2));
                if (hora >= 0 && hora <= 23) {
                    let minuto = Number(value.substr(-2));
                    if (minuto >= 0 && minuto <= 59) {
                        return true;
                    } else {
                        return "Formato de minutos inválido";
                    }
                } else {
                    return "Formato de hora inválido";
                }
            } else {
                return "Formato de tempo inválido";

            }
        }

        return true;

    },
    validateLatitude: function (value) {
        if(!value) return true;

        if(value < -90 || value > 90) return "Latitude inválida";

        return true;
    },
    validateLongitude: function (value) {
        if(!value) return true;

        if(value < -180 || value > 180) return "Longitude inválida";

        return true;
    },
    formatoHorario: function (value) {
        if (value) {
            if (value.length < 5 || value.indexOf(':') < 0) {
                return 'Formato de horário inválido';
            }

            if (Number(value.substr(0, 2)) >= 24 || Number(value.substr(0, 2)) < 0) {
                return 'Formato de hora inválido';
            }
            if (Number(value.substr(-2)) > 59 || Number(value.substr(-2)) < 0) {
                return 'Formato de minuto inválido';
            }
        }
        return true;
    }
}
</script>
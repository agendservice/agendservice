<script>
import Moment from "moment";
export default {
  moment: Moment,
  formatarInteiroDecimal: function (numero) {
    if ((numero+'').indexOf('.') >= 0) {
      return parseFloat(Number(numero)).toFixed(2).replaceAll('.', ',').replace(/\d(?=(\d{3})+\,)/g, '$&.');
    }
    return (Number(numero)+'').replaceAll('.', ',');
  },
  formatarDecimal: function (numero) {
    return parseFloat(Number(numero)).toFixed(2).replaceAll('.', ',').replace(/\d(?=(\d{3})+\,)/g, '$&.');
  },
  dinheiroParaNumero: function (valor) {
    return Number(valor.substr(3).replace(".", "").replaceAll(",", "."));
  },
  numeroParaDinheiro: function (valor) {
    let val = parseFloat(Number(valor)).toFixed(2).replaceAll(".", ",");
    return val;
  },
  formatarData: function (valor) {
    if (valor) {
      if (valor.indexOf("-") >= 0) {
        // Formato atual: YYYY-MM-DD
        return this.moment(valor, "YYYY-MM-DD").format("DD/MM/YYYY");
      }
      return valor;
    }
    return "";
  },
  ucWords: function (frase) {
    if (frase) {
      let novaFrase = "";
      for (const palavra of frase.split(" ")) {
        novaFrase += (palavra[0]?palavra[0].toUpperCase():'') + palavra.substr(1).toLowerCase() + "##";
      }

      return novaFrase.replaceAll("##", " ");
    }
  },
  formatarTamanhoArquivo: function (tamanho) {
    if (tamanho > 999999) {
      return parseFloat(tamanho/1000/1000).toFixed(2)+' MB';
    } else {
      return parseFloat(tamanho/1000).toFixed(2)+' kB';
    }
  },
  formatarDinheiro: function (numero) {

    if (numero == null || numero === '') { return 'R$ 0,00'; }

    let n;

    if (typeof numero === 'number') {
      n = numero;
    } else {
      // normaliza string pt-BR: "1.230,00" -> "1230.00"
      let s = String(numero).trim();
      s = s.replace(/\s/g, '');
      if (s.indexOf(',') > -1) {
        s = s.replace(/\./g, '').replace(',', '.');
      } else {
        s = s.replace(/\./g, '');
      }
      n = parseFloat(s);
    }

    if (!Number.isFinite(n)) { return 'R$ 0,00'; }

    return n.toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  },
  formatarDinheiroFull: function (numero) {
    if (!numero) {return 'R$ 0,0000';}
    return Number(numero).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL', minimumFractionDigits: 4, maximumFractionDigits: 4});
  },
  validarData: function (data) {
    // Espera formato: dd/mm/yyyy
    const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
    const match = data.match(regex);

    if (!match) return false;

    const dia = parseInt(match[1], 10);
    const mes = parseInt(match[2], 10) - 1; // JavaScript: janeiro = 0
    const ano = parseInt(match[3], 10);

    const dataObj = new Date(ano, mes, dia);

    return (
      dataObj.getFullYear() === ano &&
      dataObj.getMonth() === mes &&
      dataObj.getDate() === dia
    );
  },

  validarHorario: function (horario) {
    // Espera formato: HH:mm
    const regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
    return regex.test(horario);
  }
};
</script>
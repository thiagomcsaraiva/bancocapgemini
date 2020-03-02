import { http } from '../../services/config';

export default{
    // REQUISIÃO A API PARA LISTAR AS CONTAS CADASTRADAS
    listar:() => {
        return http.get('contas');
    },
    
    // REQUISIÃO A API CONSULTAR SALDO DE UMA CONTA INFORMADA
    saldo:(idConta) => {
        return http.get('saldo', { 
            params: { idConta: idConta }
        });
    },
    
    // REQUISIÃO A API PARA GRAVAR UM DEPÓSITO OU SAQUE
    transacao:(valor, idConta) => {
        return http.get('transacoes/create', {
            params: { valor: valor, idConta: idConta }
        });
    }
}
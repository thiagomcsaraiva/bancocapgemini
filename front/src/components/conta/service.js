import { http } from '../../services/config';

export default{
    listar:() => {
        return http.get('contas');
    },

    saldo:(idConta) => {
        return http.get('saldo', { 
                params: { idConta: idConta }
            });
    },

    transacao:(valor, idConta) => {
        return http.get('transacoes/create', {
                params: { valor: valor, idConta: idConta }
        });
    }
}
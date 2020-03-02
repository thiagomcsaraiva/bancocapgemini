<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app clipped>
            <v-list dense>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>CONTAS</v-list-item-title>
                        <v-list-item-subtitle>Clique para acessar a conta</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-divider></v-divider>
                <!-- LISTA DE CONTAS CADASTRADAS -->
                <v-list>
                    <v-list-item v-for="item in contas" :key="item.id" link @click="detalharConta(item)">
                        <v-list-item-avatar>
                            <img :src="`https://randomuser.me/api/portraits/men/${item.id}.jpg`" alt="">
                        </v-list-item-avatar>
                        <v-list-item-title v-text="item.nome_titular" />
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app clipped-left color="blue" dense>    
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer" v-on="on"/>
                </template>
                <span v-if="!drawer">Mostrar Contas</span>
                <span v-else>Ocultar Contas</span>
            </v-tooltip>
            <v-toolbar-title class="mr-12 align-center">
                <span class="title">Banco Capgemini</span>
            </v-toolbar-title>
        </v-app-bar>

        <v-container fluid>
            <v-row justify="center" align="center">
                <v-col cols="12" sm="8" md="6">
                    <v-alert :type="tipoMsg" v-model="hasMsg" dismissible>
                        {{ msg }}
                    </v-alert>
                    <v-card class="elevation-12" v-if="conta.numero && drawer">
                    
                        <!-- SESSÃO DAS INFORMAÇÕES DA CONTA -->
                        <v-list-item three-line>
                            <v-list-item-content>
                                <div class="mb-4">Conta: {{ conta.numero }}</div>
                                <div class="mb-4">Nome do titular: {{ conta.nome_titular }}</div>
                                <v-list-item-title class="headline mb-1">Saldo: R$ {{ conta.saldo | formataValor }}</v-list-item-title>
                            </v-list-item-content>

                            <v-list-item-avatar>
                                <img :src="`https://randomuser.me/api/portraits/men/${conta.numero}.jpg`" alt="">
                            </v-list-item-avatar>
                        </v-list-item>

                        <v-divider></v-divider>

                        <!-- SESSÃO DAS AÇÕES DE DEPÓSITO E SAQUE -->
                        <v-card-actions v-if="!operacao">
                            <v-btn dark large color="teal" @click="operacao = 'depósito', hasMsg = false">
                                Depósito
                            </v-btn>
                            <v-btn v-if="conta.saldo > 0" dark large color="error" @click="operacao = 'saque', hasMsg = false">
                                Saque
                            </v-btn>
                        </v-card-actions>
                        <!-- SESSÃO DO FORM DE DEPÓSITO E SAQUE -->
                        <div class="px-2 py-2" v-if="operacao">
                            <v-form ref="form" azy-validation>
                                <v-row>
                                    <v-col md="4">
                                        <vuetify-money v-model="valor" label="Valor" outlined :options="options"/>
                                    </v-col>
                                    <v-col md="8">
                                        <v-btn large :disabled="valor <= 0" color="success" class="mr-4 mt-1" @click="realizarOperacao()">Confirmar {{operacao}}</v-btn>
                                        <v-btn large class="mr-4 mt-1" @click="voltar()">Voltar</v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
  
</template>

<script>
    import Conta from './service.js';

    export default {
        name: 'Home',
        data: () => ({
            drawer: false,
            contas: [],
            saldoConta: null,
            valor: null,
            operacao: false,
            hasMsg: false,
            msg: null,
            tipoMsg: null,
            conta: {
                numero: '',
                nome_titular: '',
                saldo: '',
            },
            options: {
                locale: "pt-BR",
                prefix: "R$",
                precision: 2
            }
        }),
        created () {
            // CARREGA LISTA DE CONTAS PARA O MENU
            Conta.listar().then(resposta => {
                this.contas = resposta.data;
            });
        },
        methods:{
            // CARREGA INFORMAÇÕES DA CONTA SELECIONADA NO MENU E SEU SALDO PARA EXIBIÇÃO
            detalharConta(conta){
                this.hasMsg = false;
                Conta.saldo(conta.id).then(resposta => {
                    this.conta.numero = conta.id;
                    this.conta.nome_titular = conta.nome_titular;
                    this.conta.saldo = resposta.data;
                });
            },
            // FECHA SESSÃO DE DEPÓSITO OU SAQUE
            voltar(){
                this.operacao = null;
                this.valor = null;
                this.hasMsg = false;
            },
            // REALIZA DEPÓSITO OU SAQUE
            realizarOperacao(){
                if(this.operacao == 'saque'){
                    if(this.valor > this.conta.saldo){
                        this.tipoMsg = 'error';
                        this.msg = 'Não foi possível realizar a transação, saldo insuficiente!';
                        this.hasMsg = true;
                        return;
                    }
                    this.valor = -this.valor; 
                }

                Conta.transacao(this.valor,this.conta.numero).then(resposta => {
                    if(resposta.status == 200){
                        this.tipoMsg = 'success';
                        this.msg = 'Transação realizada com sucesso.';
                        this.hasMsg = true;
                        this.operacao = null;
                        this.valor = null;
                        Conta.saldo(this.conta.numero).then(resposta => {
                            this.conta.saldo = resposta.data;
                        });
                    }
                });
            }
        },
        filters: {
            formataValor: function (value) {
                if (value) {
                    let val = (value/1).toFixed(2).replace('.', ',');
                    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            },
        }
    }
</script>

<style>
    .v-card .v-avatar{
        border-radius: 0%;
        width: 100px !important;
        height: 100px !important;
    }
</style>


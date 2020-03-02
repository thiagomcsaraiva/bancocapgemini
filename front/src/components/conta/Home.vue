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

        <v-list>
          <v-list-item
            v-for="item in contas"
            :key="item.id"
            link
            @click="detalharConta(item)"
          >
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

    <v-content>
      <v-container class="fill-height" fluid>
        <v-row justify="center" align="center">
          <v-col cols="12" sm="8" md="6">
            <v-card
                class="elevation-12"
                v-if="conta.numero && drawer"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="mb-4">Conta: {{ conta.numero }}</div>
                        <div class="mb-4">Nome do titular: {{ conta.nome_titular }}</div>
                        <v-list-item-title class="headline mb-1">Saldo: R$ {{ formataValor(conta.saldo) }}</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-avatar>
                        <img :src="`https://randomuser.me/api/portraits/men/${conta.numero}.jpg`" alt="">
                    </v-list-item-avatar>
                </v-list-item>

                <v-divider></v-divider>

                <v-card-actions v-if="!operacao">
                    <v-btn dark depressed color="teal" @click="operacao = 'depósito'">
                        Depósito
                    </v-btn>
                    <v-btn v-if="conta.saldo > 0" dark depressed color="error" @click="operacao = 'saque'">
                        Saque
                    </v-btn>
                </v-card-actions>
                <div class="px-2 py-2" v-if="operacao">
                    <v-form ref="form" azy-validation>
                        <v-row>
                            <v-col md="4">
                                <v-text-field prefix="R$" v-model="valor" label="Valor" required></v-text-field>
                            </v-col>
                            <v-col md="8">
                                <v-btn :disabled="valor <= 0" color="success" class="mr-4 mt-3" @click="realizarOperacao()">Confirmar {{operacao}}</v-btn>
                                <v-btn class="mr-4 mt-3" @click="voltar()">Voltar</v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
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
            conta: {
                numero: '',
                nome_titular: '',
                saldo: '',
            }
        }),
        created () {
            Conta.listar().then(resposta => {
                this.contas = resposta.data;
            });
            this.$vuetify.theme.dark = true
        },
        methods:{
            detalharConta(conta){
                Conta.saldo(conta.id).then(resposta => {
                    this.conta.numero = conta.id;
                    this.conta.nome_titular = conta.nome_titular;
                    this.conta.saldo = resposta.data;
                });
            },
            voltar(){
                this.operacao = null;
                this.valor = null;
            },
            realizarOperacao(){
                if(this.operacao == 'saque'){
                    this.valor = -this.valor; 
                }

                Conta.transacao(this.valor,this.conta.numero).then(resposta => {
                    if(resposta.status == 200){
                        this.operacao = null;
                        this.valor = null;
                        Conta.saldo(this.conta.numero).then(resposta => {
                            this.conta.saldo = resposta.data;
                        });
                    }
                });
            },
            formataValor(value) {
                let val = (value/1).toFixed(2).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
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


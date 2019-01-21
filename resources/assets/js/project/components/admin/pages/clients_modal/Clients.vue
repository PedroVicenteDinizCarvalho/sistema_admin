<template>
  <div class="container">
      <h1>Clientes <span v-if="clients>total > 0">({{ clients.total }})</span></h1>
      <hr>
	  
	    <div class="row options">
            <div class="col">
                <a ref="modal" @click.prevent="create" class="btn btn-success">
                    Adicionar
                </a>
            </div>

            <div class="col">
                <search @search="searchClient"></search>

                <div v-if="search">
                    Resultados para a pesquisa: {{ search }}
                </div>
            </div>
        </div>

		    <table class="table table-dark">
            <tr>
                <th width="100">Imagem</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Documento</th>
                <th>Email</th>
                <th>Telefone ddd</th>
                <th>Telefone Number</th>
                <th width="200">Ações</th>
            </tr>
            <tr v-for="(client, key) in clients.data" :key="key">
                <td>
                  <div v-if="client.image">
                    <img :src="[`/storage/clients/${client.image}`]" :alt="client.name" class="img-list">
                  </div>
                </td>
                <td v-text="client.name"></td>
                <td v-text="client.type"></td>
                <td v-text="client.document"></td>
                <td v-text="client.email"></td>
                <td v-text="client.tel_ddd"></td>
                <td v-text="client.tel_number"></td>
                <td>
                    <a href="#" @click.prevent="edit(client.id)" class="btn btn-info">
                        Editar
                    </a>
                    <a href="#" @click.prevent="confirmDelete(client)" class="btn btn-danger">Deletar</a>
                </td>
            </tr>
        </table>

        <vodal :show="showModal" animation="zoom" @hide="hide" :width="600" :height="500">
            <form-client
                :client="client"
                :update="update"
                @success="success">
            </form-client>
        </vodal>

        <paginate
            :pagination="clients"
            :offset="3"
            @paginate="loadClients"></paginate>
  </div>
</template>

<script>
import Vodal from 'vodal'

import SearchClientComponent from './partials/SearchClientComponent'
import PaginationComponent from '../../../layouts/PaginationComponent'
import FormClientComponent from './partials/FormClientComponent'



export default {
	name: 'client-component',
	created () {
		this.loadClients()
	},

    data () { 
      return {
      	search: null,
      	clientId: null,
      	showModal: false,
      	client: {
      		name: '',
      		type: '',
      		image: '',
      		type_document: '',
      		document: '',
      		email: '',
      		tel_ddd: '',
      		tel_number: '',
      	},
      	update: false,
      }
    },

    computed: {
    	clients (){
    		return this.$store.state.clients.items
    	},
    	params (){
    		return {
    			page: this.clients.current_page,
    			filter: this.search,
    		}
    	}
    },

    methods: {
    	loadClients (page= 1) {
    		this.$store.dispatch('loadClients', {...this.params, page})
    	},

      edit (id) {
        this.reset()

        this.$store.dispatch('loadClient', id)
          .then(response => {
            this.client = response
            this.showModal = true
            this.update = true
          })
          .catch(error => this.$snotify.error('Erro ao carregar Cliente'))
      },

      searchClient (search) {
        this.search = search
        this.loadClients()
      },

      confirmDelete (client) {
         this.clientId = client.id

         this.$snotify.error(`Deseja realmente deletar este cliente: ${client.name}`, client.name, {
                timeout: 10000,
                showProgressBar: true,
                closeOnClick: true,
                pauseOnHover: true,
                buttons: [
                    {text: 'Não', action: () => console.log('Clicked: No')},
                    {text: 'Sim', action: () => this.destroy(), bold: false},
                ]
              })
      },

      destroy (){
        this.$store.dispatch('destroyClient', this.clientId)
            .then(() => {
              this.clientId = null
              this.loadClients()
            })
      },

    	create (){
    		this.reset()
    		this.update = false
    		this.showModal = true
    	},
      hide () {
        this.showModal = false
      },
      sucess () {
        this.reset()
        this.loadClients()
        this.hide()
      },
      reset () {
        this.client = {
            id: '',
            name: '',
            type: '',
            image: '',
            type_document: '',
            document: '',
            email: '',
            tel_ddd: '',
            tel_number: '',
        }
      }
    },

    components: {
        search: SearchClientComponent,
        paginate: PaginationComponent,
        formClient: FormClientComponent,
        Vodal,
    }
}
</script>

<style scoped>
.img-list{max-width: 50px;}
.options{margin: 20px 0;}
.vodal-dialog{height: auto; max-width: 90%;}
</style>
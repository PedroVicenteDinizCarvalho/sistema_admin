<template>
    <div class="container">
        <h1>Clientes <span v-if="clients.total > 0">({{ clients.total }})</span></h1>

        <div class="row options">
            <div class="col">
                <router-link :to="{name: 'client.add'}" class="btn btn-success">
                    Adicionar
                </router-link>
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
                <th>Tipo de Documento</th>
                <th>Email</th>
                <th>Telefone</th>
                <th width="200">Ações</th>
            </tr>
            <tr v-for="(client, key) in clients.data" :key="key">
                <td>
                  <div v-if="client.image">
                    <img :src="[`/storage/clients/${clients.image}`]" :alt="client.name" class="img-list">
                  </div>
                </td>
                <td v-text="client.name"></td>
                <td v-text="client.type"></td>
                <td v-text="client.document"></td>
                <td v-text="client.email"></td>
                <td v-text="client.tel_ddd, client.tel_number"></td>
                <td>
                    <router-link :to="{name: 'client.edit', params: {id: client.id}}" class="btn btn-success">
                        Editar
                    </router-link>
                    <a href="#" @click.prevent="confirmDelete(client)" class="btn btn-danger">Deletar</a>
                </td>
            </tr>
        </table>

        <paginate
            :pagination="clients"
            :offset="3"
            @paginate="loadClients"></paginate>


    </div>
</template>

<script>
import SearchClientComponent from './partials/SearchClientComponent'
import PaginationComponent from '../../../layouts/PaginationComponent'

export default {
    name: 'client-component',
    created () {
      this.loadClient()
    },
    data () {
        return {
            search: null,
            clientId: null,
        }
    },
    computed: {
      clients () {
          return this.$store.state.clients.items
      },
      params () {
          return {
              page: this.clients.current_page,
              filter: this.search,
          }
      }
    },
    methods: {
        loadClients (page) {
            this.$store.dispatch('loadClients', {...this.params, page})
        },
        searchClient (search) {
            this.search = search

            this.loadClients(1)
        },
        confirmDelete (client) {
            this.clientId = client.id

            this.$snotify.error(`Deseja realmente deletar o cliente: ${client.name}`, client.name, {
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
        destroy () {
            this.$store.dispatch('destroyClient', this.clientId)
                            .then(() => {
                                this.clientId = null
                                this.loadClient(1)
                            })
        }
    },
    components: {
        search: SearchClientComponent,
        paginate: PaginationComponent
    }
}
</script>


<style scoped>
.img-list{max-width: 50px;}
.options{margin: 20px 0;}
</style>
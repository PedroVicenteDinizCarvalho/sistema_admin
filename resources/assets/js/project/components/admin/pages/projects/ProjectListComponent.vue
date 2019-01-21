<template>
    <div class="container">
        <h1>Projetos <span v-if="projects.total > 0">({{ projects.total }})</span></h1>

        <div class="row options">
            <div class="col">
                <router-link :to="{name: 'project.add'}" class="btn btn-success">
                    Adicionar
                </router-link>
            </div>

            <div class="col">
                <search @search="searchProject"></search>

                <div v-if="search">
                    Resultados para a pesquisa: {{ search }}
                </div>
            </div>
        </div>

        <table class="table table-dark">
            <tr>
                <th width="100">Imagem</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Preço</th>
                <th>Parcelas</th>
                <th>Parcelas Pagas</th>
                <th>Data Limite</th>
                <th>Cliente</th>
                <th width="200">Ações</th>
            </tr>
            <tr v-for="(project, key) in projects.data" :key="key">
                <td>
                  <div v-if="project.image">
                    <img :src="[`/storage/projects/${projects.image}`]" :alt="project.name" class="img-list">
                  </div>
                </td>
                <td v-text="project.name"></td>
                <td v-text="project.description"></td>
                <td v-text="project.type"></td>
                <td v-text="project.price"></td>
                <td v-text="project.plots"></td>
                <td v-text="project.paid_installments"></td>
                <td v-text="project.deadline"></td>
                <td v-text="project.client"></td>
                <td>
                    <router-link :to="{name: 'project.edit', params: {id: project.id}}" class="btn btn-success">
                        Editar
                    </router-link>
                    <a href="#" @click.prevent="confirmDelete(project)" class="btn btn-danger">Deletar</a>
                </td>
            </tr>
        </table>

        <paginate
            :pagination="projects"
            :offset="3"
            @paginate="loadProjects"></paginate>


    </div>
</template>

<script>
import SearchProjectComponent from './partials/SearchProjectComponent'
import PaginationComponent from '../../../layouts/PaginationComponent'

export default {
    name: 'project-component',
    created () {
      this.loadProject()
    },
    data () {
        return {
            search: null,
            projectId: null,
        }
    },
    computed: {
      projects () {
          return this.$store.state.projects.items
      },
      params () {
          return {
              page: this.projects.current_page,
              filter: this.search,
          }
      }
    },
    methods: {
        loadProjects (page) {
            this.$store.dispatch('loadProjects', {...this.params, page})
        },
        searchProject (search) {
            this.search = search

            this.loadProjects(1)
        },
        confirmDelete (project) {
            this.projectId = project.id

            this.$snotify.error(`Deseja realmente deletar o cliente: ${project.name}`, project.name, {
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
            this.$store.dispatch('destroyProject', this.projectId)
                            .then(() => {
                                this.projectId = null
                                this.loadProject(1)
                            })
        }
    },
    components: {
        search: SearchProjectComponent,
        paginate: PaginationComponent
    }
}
</script>


<style scoped>
.img-list{max-width: 50px;}
.options{margin: 20px 0;}
</style>
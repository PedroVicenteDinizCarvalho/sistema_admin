<template>
  <div class="container">
      <h1>Projects <span v-if="projects>total > 0">({{ projects.total }})</span></h1>
      <hr>
    
      <div class="row options">
            <div class="col">
                <a ref="modal" @click.prevent="create" class="btn btn-success">
                    Adicionar
                </a>
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
                    <img :src="[`/storage/projects/${project.image}`]" :alt="project.name" class="img-list">
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
                    <a href="#" @click.prevent="edit(project.id)" class="btn btn-info">
                        Editar
                    </a>
                    <a href="#" @click.prevent="confirmDelete(project)" class="btn btn-danger">Deletar</a>
                </td>
            </tr>
        </table>

        <vodal :show="showModal" animation="zoom" @hide="hide" :width="600" :height="500">
            <form-project
                :project="project"
                :update="update"
                @success="success">
            </form-project>
        </vodal>

        <paginate
            :pagination="projects"
            :offset="3"
            @paginate="loadProjects"></paginate>
  </div>
</template>

<script>
import Vodal from 'vodal'

import SearchProjectComponent from './partials/SearchProjectComponent'
import PaginationComponent from '../../../layouts/PaginationComponent'
import FormProjectComponent from './partials/FormProjectComponent'



export default {
  name: 'project-component',
  created () {
    this.loadProjects()
  },

    data () { 
      return {
        search: null,
        projectId: null,
        showModal: false,
        project: {
          name: '',
          description: '',
          type: '',
          image: '',
          price: '',
          plots: '',
          paid_installments: '',
          deadline: '',
          client: '',
        },
        update: false,
      }
    },

    computed: {
      projects (){
        return this.$store.state.projects.items
      },
      params (){
        return {
          page: this.projects.current_page,
          filter: this.search,
        }
      }
    },

    methods: {
      loadProjects (page= 1) {
        this.$store.dispatch('loadProjects', {...this.params, page})
      },

      edit (id) {
        this.reset()

        this.$store.dispatch('loadProject', id)
          .then(response => {
            this.project = response
            this.showModal = true
            this.update = true
          })
          .catch(error => this.$snotify.error('Erro ao carregar Projeto'))
      },

      searchProject (search) {
        this.search = search
        this.loadProjects()
      },

      confirmDelete (project) {
         this.projectId = project.id

         this.$snotify.error(`Deseja realmente deletar este projeto: ${project.name}`, project.name, {
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
        this.$store.dispatch('destroyProject', this.projectId)
            .then(() => {
              this.projectId = null
              this.loadProjects()
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
        this.loadProjects()
        this.hide()
      },
      reset () {
        this.project = {
            name: '',
            description: '',
            type: '',
            image: '',
            price: '',
            plots: '',
            paid_installments: '',
            deadline: '',
            client: '',
        }
      }
    },

    components: {
        search: SearchProjectComponent,
        paginate: PaginationComponent,
        formProject: FormProjectComponent,
        Vodal,
    }
}
</script>

<style scoped>
.img-list{max-width: 50px;}
.options{margin: 20px 0;}
.vodal-dialog{height: auto; max-width: 90%;}
</style>
<template>
    <div class="container">
        <h1>Editar Projeto <b>{{project.name}}</b></h1>

        <form-project-component
            :project="project"
            :update="true">
        </form-project-component>
    </div>
</template>

<script>
import FormProjectComponent from './partials/FormProjectComponent'

export default {
    props: {
        id: {
            require: true
        }
    },
    created () {
        this.$store.dispatch('loadProject', this.id)
                        .then(response => this.project = response)
                        .catch(error => {
                            this.$snotify.error('Erro ao carregar projeto', 'Erro')
                        })
    },
    data () {
        return {
            project: {}
        }
    },
    components: {
        FormProjectComponent
    }
}
</script>
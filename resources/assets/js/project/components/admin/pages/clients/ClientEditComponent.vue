<template>
    <div class="container">
        <h1>Editar Cliente <b>{{client.name}}</b></h1>

        <form-client-component
            :client="client"
            :update="true">
        </form-client-component>
    </div>
</template>

<script>
import FormClientComponent from './partials/FormClientComponent'

export default {
    props: {
        id: {
            require: true
        }
    },
    created () {
        this.$store.dispatch('loadClient', this.id)
                        .then(response => this.client = response)
                        .catch(error => {
                            this.$snotify.error('Erro ao carregar cliente', 'Erro')
                        })
    },
    data () {
        return {
            client: {}
        }
    },
    components: {
        FormClientComponent
    }
}
</script>
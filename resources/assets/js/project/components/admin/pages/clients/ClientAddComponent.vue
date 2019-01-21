<template>
    <div class="container">
        <h1>Adicionar Novo Cliente</h1>

        <form-client-component></form-client-component>
    </div>
</template>

<script>
import FormClientComponent from './partials/FormClientComponent'

export default {
    components: {
        FormClientComponent
    }
}
</script>
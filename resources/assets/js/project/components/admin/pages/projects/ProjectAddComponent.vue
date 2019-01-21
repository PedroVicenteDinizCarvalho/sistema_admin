<template>
    <div class="container">
        <h1>Adicionar Novo Projeto</h1>

        <form-project-component></form-project-component>
    </div>
</template>

<script>
import FormProjectComponent from './partials/FormProjectComponent'

export default {
    components: {
        FormProjectComponent
    }
}
</script>
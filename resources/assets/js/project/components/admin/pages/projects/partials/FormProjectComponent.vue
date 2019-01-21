<template> 
    <div>
      <form @submit.prevent="onSubmit">
        <div :class="['form-group', {'has-error': errors.image}]">
            <picture-input
                ref="pictureInput"
                @change="onChanged"
                @remove="onRemoved"
                :width="200"
                :removable="true"
                removeButtonClass="btn btn-danger"
                :height="200"
                accept="image/jpeg, image/png, image/gif"
                buttonClass="btn btn-primary"
                :customStrings="{
                upload: '<h1>Upload</h1>',
                drag: 'Clique ou arraste para aqui'}">
            </picture-input>
            <div v-if="errors.image" class="help-block">
                <p>{{ errors.image[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.name}]">
            <input type="text" class="form-control" placeholder="Nome do Projeto" v-model="project.name">
            <div v-if="errors.name" class="help-block">
                <p>{{ errors.name[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.description}]">
            <input type="text" class="form-control" placeholder="Descrição" v-model="project.description">
            <div v-if="errors.description" class="help-block">
                <p>{{ errors.description[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.type}]">
            <input type="text" class="form-control" placeholder="Tipo" v-model="project.type">
            <div v-if="errors.type" class="help-block">
                <p>{{ errors.type[0] }}</p>
            </div>
        </div>
        
        <div :class="['form-group', {'has-error': errors.price}]">
            <input type="text" class="form-control" placeholder="Preço" v-model="project.price">
            <div v-if="errors.price" class="help-block">
                <p>{{ errors.price[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.plots}]">
            <input type="text" class="form-control" placeholder="Parcelas" v-model="project.plots">
            <div v-if="errors.plots" class="help-block">
                <p>{{ errors.plots[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.paid_installments}]">
            <input type="text" class="form-control" placeholder="Parcelas Pagas" v-model="project.paid_installments">
            <div v-if="errors.paid_installments" class="help-block">
                <p>{{ errors.paid_installments[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.deadline}]">
            <input type="text" class="form-control" placeholder="Data Limite" v-model="project.deadline">
            <div v-if="errors.deadline" class="help-block">
                <p>{{ errors.deadline[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.client}]">
            <input type="text" class="form-control" placeholder="Cliente" v-model="project.client">
            <div v-if="errors.client" class="help-block">
                <p>{{ errors.client[0] }}</p>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
  </div>
</template>

<script>
import PictureInput from 'vue-picture-input'

export default {
    props: {
        update: {
            require: false,
            type: Boolean,
            default: false
        },
        project: {
            require: false,
            type: Array|Object,
            default: () => {
                return {
                    id: '',
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
        }
    },
    data () {
        return {
            errors: {},
            imagePreview: null,
            upload: null,
        }
    },
    methods: {
        onChanged() {
            console.log("New picture loaded");
            if (this.$refs.pictureInput.file) {
                this.upload = this.$refs.pictureInput.file;
            } else {
                console.log("Old browser. No support for Filereader API");
            }
        },
        onRemoved() {
            this.upload = null;
        },
        onSubmit () {
            const action = this.update ? 'editProject' : 'addProject'

            const formData = new FormData()
            if (this.upload != null)
                formData.append('image', this.upload)
            
            formData.append('id', this.project.id)
            formData.append('name', this.project.name)
            formData.appent('description', this.project.description)
            formData.append('type', this.project.type)
            formData.append('price', this.project.price)
            formData.append('plots', this.project.plots)
            formData.append('paid_installments', this.project.paid_installments)
            formData.append('deadline', this.project.deadline)
            formData.append('client', this.project.client)

            return this.$store.dispatch(action, formData)
                        .then(() => {
                            this.$snotify.success('Sucesso ao salvar o registro')

                            this.$router.push({name: 'projects'})
                        })
                        .catch(errors => {
                            this.$snotify.error('Algo errado...', 'Erro')

                            this.errors = errors.hasOwnProperty('errors') ? errors.errors : errors
                        })
        }
    },
    components: {
        PictureInput
    }
}
</script>

<style>
form{
    margin: 10px 0;
}
.img-responsive{max-width: 60px;}
</style>
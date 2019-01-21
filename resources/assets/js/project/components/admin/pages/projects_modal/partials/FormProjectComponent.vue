<template>
    <div>
      <form @submit.prevent="onSubmit">
        <div :class="['form-group', {'has-error': errors.image}]">
            <div v-if="!imagePreview">
                <input
                    type="file"
                    ref="picture"
                    class="form-control"
                    @change="onFileChange"
                    accept="image/*">
            </div>
            <div v-else class="text-center">
                <img :src="imagePreview" class="img-responsive">
                <button @click.prevent="removeImage" class="btn btn-danger">Remover Imagem</button>
            </div>
            <div v-if="errors.image" class="help-block">
                <p>{{ errors.image[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.name}]">
            <input type="text" class="form-control" placeholder="Nome Projeto" v-model="project.name">
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
            <input type="number" class="form-control" placeholder="Preço" v-model="project.price">
            <div v-if="errors.price" class="help-block">
                <p>{{ errors.price[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.plots}]">
            <input type="number" class="form-control" placeholder="Parcelas" v-model="project.plots">
            <div v-if="errors.plots" class="help-block">
                <p>{{ errors.plots[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.paid_installments}]">
            <input type="number" class="form-control" placeholder="DDD" v-model="project.paid_installments">
            <div v-if="errors.paid_installments" class="help-block">
                <p>{{ errors.paid_installments[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.deadline}]">
            <input type="number" class="form-control" placeholder="Data Limite" v-model="project.deadline">
            <div v-if="errors.project.deadline" class="help-block">
                <p>{{ errors.project.deadline[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.client}]">
            <input type="number" class="form-control" placeholder="Data Limite" v-model="project.client">
            <div v-if="errors.project.client" class="help-block">
                <p>{{ errors.project.client[0] }}</p>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
  </div>
</template>

<script>
import PictureInput from 'vue-picture-input'

export default {
    mounted () {
        this.reset()
    },
    props: {
        update: {
            require: false,
            type: Boolean,
            default: false
        },
        project: {
            require: false,
            type: Object
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
        onFileChange (e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return

            this.upload = files[0]
            this.createImage(files[0])
        },

        createImage (file) {
            let reader = new FileReader()
            reader.onload = (e) => {
                this.imagePreview = e.target.result
            }
            reader.readAsDataURL(file)
        },

        removeImage () {
            this.imagePreview = null,
            this.upload = null
        },

        onSubmit () {
            const action = this.update ? 'editProject' : 'addProject'

            const formData = new FormData()
            if (this.upload != null)
                formData.append('image', this.upload)
            
            formData.append('id', this.project.id)
            formData.append('name', this.project.name)
            formData.append('description', this.project.description)
            formData.append('type', this.project.type)
            formData.append('price', this.project.price)
            formData.append('plots', this.project.plots)
            formData.append('paid_installments', this.project.paid_installments)
            formData.append('deadline', this.project.deadline)
            formData.append('client', this.project.client)

            return this.$store.dispatch(action, formData)
                        .then(() => {
                            this.$swal('Sucesso', 'Operação realizada com sucesso!', 'success')

                            this.$emit('success')

                            this.errors = {}
                            this.upload = null
                            this.imagePreview = null
                        })
                        .catch(errors => {
                            this.$snotify.error('Algo deu errado...')

                            this.errors = errors.hasOwnProperty('errors') ? errors.errors : errors
                        })
        },

        reset () {
            console.log('Form reset')
            this.errors = {}
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
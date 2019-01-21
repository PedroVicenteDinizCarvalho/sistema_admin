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
            <input type="text" class="form-control" placeholder="Nome Cliente" v-model="client.name">
            <div v-if="errors.name" class="help-block">
                <p>{{ errors.name[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.type}]">
            <input type="text" class="form-control" placeholder="Tipo de Cliente" v-model="client.type">
            <div v-if="errors.type" class="help-block">
                <p>{{ errors.type[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.type_document}]">
            <input type="text" class="form-control" placeholder="Tipo de Dodumento" v-model="client.type_document">
            <div v-if="errors.type_document" class="help-block">
                <p>{{ errors.type_document[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.document}]">
            <input type="text" class="form-control" placeholder="Documento" v-model="client.document">
            <div v-if="errors.document" class="help-block">
                <p>{{ errors.document[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.email}]">
            <input type="email" class="form-control" placeholder="Email" v-model="client.email">
            <div v-if="errors.email" class="help-block">
                <p>{{ errors.email[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.tel_ddd}]">
            <input type="number" class="form-control" placeholder="DDD" v-model="client.tel_ddd">
            <div v-if="errors.tel_ddd" class="help-block">
                <p>{{ errors.tel_ddd[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.tel_number}]">
            <input type="number" class="form-control" placeholder="Número" v-model="client.tel_number">
            <div v-if="errors.tel_number" class="help-block">
                <p>{{ errors.tel_number[0] }}</p>
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
        client: {
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
            const action = this.update ? 'editClient' : 'addClient'

            const formData = new FormData()
            if (this.upload != null)
                formData.append('image', this.upload)
            
            formData.append('id', this.client.id)
            formData.append('name', this.client.name)
            formData.append('type', this.client.type)
            formData.append('type_document', this.client.type_document)
            formData.append('document', this.client.document)
            formData.append('email', this.client.email)
            formData.append('tel_ddd', this.client.tel_ddd)
            formData.append('tel_number', this.client.tel_number)

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
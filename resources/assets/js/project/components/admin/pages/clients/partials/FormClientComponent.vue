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
            <input type="text" class="form-control" placeholder="Nome do Cliente" v-model="client.name">
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
            <input type="text" class="form-control" placeholder="Tipo de Documento" v-model="client.type_document">
            <div v-if="errors.type" class="help-block">
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
            <input type="text" class="form-control" placeholder="Email" v-model="client.email">
            <div v-if="errors.email" class="help-block">
                <p>{{ errors.email[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.tel_ddd}]">
            <input type="text" class="form-control" placeholder="DDD" v-model="client.tel_ddd">
            <div v-if="errors.tel_ddd" class="help-block">
                <p>{{ errors.tel_ddd[0] }}</p>
            </div>
        </div>

        <div :class="['form-group', {'has-error': errors.tel_number}]">
            <input type="text" class="form-control" placeholder="Numero" v-model="client.tel_number">
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
    props: {
        update: {
            require: false,
            type: Boolean,
            default: false
        },
        client: {
            require: false,
            type: Array|Object,
            default: () => {
                return {
                    id: '',
                    name: '',
                    type: '',
                    image: '',
                    type_document: '',
                    document: '',
                    email: '',
                    tel_ddd: '',
                    tel_number: '',
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
                            this.$snotify.success('Sucesso ao salvar o registro')

                            this.$router.push({name: 'clients'})
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
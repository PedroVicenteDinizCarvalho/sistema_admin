import axios from 'axios'
import { URL_BASE } from '../../../configs/configs'

const RESOURCE = 'clients/'

const CONFIG = {
    headers: {
        'content-type': 'multipart/form-data'
    }
}

export default {
    loadClients (context, params) {
        // Inicia Preloader
        context.commit('LOADING', true)

        axios.get(`${URL_BASE}${RESOURCE}`, {params})
                    .then(response => context.commit('CLIENTS_LOAD', response.data))
                    .catch(error => console.log(error))
                    .finally(() => context.commit('LOADING', false))
    },


    loadClient (context, id) {
        context.commit('LOADING', true)

        return new Promise((resolve, reject) => {
            axios.get(`${URL_BASE}${RESOURCE}${id}`)
                    .then(response => resolve(response.data))
                    .catch(error => reject(error))
                    .finally(() => context.commit('LOADING', false))
        })
    },


    addClient (context, formData) {
        context.commit('LOADING', true)

        return new Promise((resolve, reject) => {
            axios.post(`${URL_BASE}${RESOURCE}`, formData, CONFIG)
                    .then(response => resolve())
                    .catch(error => reject(error.response.data.errors))
                    .finally(() => context.commit('LOADING', false))
        })
    },


    editClient (context, formData) {
        context.commit('LOADING', true)

        formData.append('_method', 'PATCH')

        return new Promise((resolve, reject) => {
            axios.post(`${URL_BASE}${RESOURCE}${formData.get('id')}`, formData, CONFIG)
                    .then(response => resolve())
                    .catch(error => reject(error.response.data.errors))
                    .finally(() => context.commit('LOADING', false))
        })
    },


    destroyClient (context, id) {
        context.commit('LOADING', true)

        return new Promise((resolve, reject) => {
            axios.delete(`${URL_BASE}${RESOURCE}${id}`)
                    .then(response => resolve())
                    .catch(error => reject(error.response.data))
                    // .finally(() => context.commit('LOADING', false))
        })
    },
}
import Vue from 'vue'
import Vuex from 'vuex'

import Product from './modules/products/products'
import Client from './modules/clients/clients'
import Project from './modules/projects/projects'
import preloader from './modules/preloader/preloader'
import auth from './modules/auth/auth'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        products: Product,
        clients: Client,
        projects: Project,
        preloader,
        auth,
    }
})
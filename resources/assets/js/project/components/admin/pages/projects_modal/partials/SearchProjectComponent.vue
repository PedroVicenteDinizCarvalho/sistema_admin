<template>
  <div>
      <form class="form form-inline" @submit.prevent="searchProjects">
          <input type="text" v-model="search" placeholder="Buscar?" class="form-control mr-sm-2">

          <button type="submit" class="btn btn-outline-success">Buscar</button>
      </form>
  </div>
</template>


<script>
export default {
  data () {
      return {
          search: ''
      }
  },
  methods: {
      searchProjects () {
          this.$emit('search', this.search)
      }
  }
}
</script>
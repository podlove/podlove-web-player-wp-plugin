<template>
  <div class="page-header">
    <h2>{{ title }}</h2>
    <el-row>
      <el-button icon="el-icon-check" type="primary">Save</el-button>
      <el-button type="danger" icon="el-icon-delete" plain v-if="this.routeName !== 'settings'"> Delete</el-button>
    </el-row>
  </div>
</template>

<script>
import { get } from 'lodash'
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters(['routeName', 'routeId']),
    title () {
      switch(this.routeName) {
        case 'config':
          return `Configuration ${this.routeId}`

        case 'template':
          return `Template ${this.routeId}`

        case 'theme':
          return `Theme ${this.routeId}`

        case 'settings':
          return `Settings`

        default:
          return null
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.page-header {
  padding: 0.5em 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>

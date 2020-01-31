<template>
  <div class="flex">
    <el-container>
      <el-aside width="200px">
        <navigation />
      </el-aside>
      <el-container>
        <el-header>
          <page-header></page-header>
        </el-header>
        <el-main class="flex configurator">
          <router-view class="mr-6"></router-view>
          <preview class="mr-6" />
        </el-main>
      </el-container>
    </el-container>
    <create-modal />
    <delete-modal />
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import { get } from 'lodash/fp'
  import { Preview, Navigation, PageHeader, CreateModal, DeleteModal } from './components'

  export default {
    name: 'configurator',

    methods: mapActions('lifecycle', ['bootstrap']),

    mounted () {
      this.bootstrap()
    },

    components: {
      PageHeader,
      Navigation,
      Preview,
      CreateModal,
      DeleteModal
    }
  }
</script>

<style lang="postcss">
  @tailwind base;
  @tailwind components;
  @tailwind utilities;

  @import '~element-ui/lib/theme-chalk/index.css';

  .configurator {
    height: calc(100vh - 32px);
  }
</style>

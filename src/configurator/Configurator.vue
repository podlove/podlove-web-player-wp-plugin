<template>
  <div class="flex">
    <el-container>
      <el-container>
        <el-header class="p-0 flex">
          <navigation />
          <global-actions class="el-menu el-menu--horizontal" />
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
  import { Preview, Navigation, GlobalActions, CreateModal, DeleteModal } from './components'

  export default {
    name: 'configurator',

    methods: mapActions('lifecycle', ['bootstrap']),

    mounted () {
      this.bootstrap()
    },

    components: {
      GlobalActions,
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

  @import './theme/index.css';

  .configurator {
    height: calc(100vh - 60px - 32px);
  }

  .el-menu.el-menu--horizontal {
    border-bottom: 1px solid #82878C;
  }

  .el-loading-spinner .circular {
    display: inline;
  }
</style>

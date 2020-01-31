<template>
  <div class="player-configurator">
    <el-container>
      <el-aside width="200px">
        <navigation />
      </el-aside>
      <el-container>
        <el-header>
          <page-header></page-header>
        </el-header>
        <el-main class="player-main">
          <router-view class="player-col"></router-view>
          <preview class="player-col" />
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

<style lang="scss">
  @import '~normalize.css';
  @import '~element-ui/lib/theme-chalk/index.css';
  @import '~styles/variables';
  @import '~styles/form';
  @import '~styles/fixes';

  html {
    box-sizing: border-box;
  }
  *, *:before, *:after {
    box-sizing: inherit;
  }

  .player-configurator {
    height: calc(100vh - 32px);
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    display: flex
  }

  .player-main {
    display: flex;
  }

  .player-col {
    margin-right: 2em;

    &:last-child {
      margin-right: 0;
    }
  }

//   <style lang="postcss">
//   @tailwind base;
//   @tailwind components;
//   @tailwind utilities;
// </style>

</style>

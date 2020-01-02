<template>
  <div class="player-configurator">
    <el-row :gutter="20">
      <el-col :span="18" class="players">
        <h2>{{ $i18n('title') }}</h2>
        <div id="player"></div>

        <el-row :gutter="20" v-if="loaded">
          <el-col :xs="24" :md="12">
            <h4>{{ $i18n('theme') }}</h4>
            <theme-component></theme-component>
          </el-col>

          <el-col :xs="24" :md="12">
            <h4>{{ $i18n('tabs') }}</h4>
            <tabs-component></tabs-component>
          </el-col>
        </el-row>

        <el-row :gutter="20" v-if="loaded">
          <el-col :xs="24" :md="12">
            <h4>{{ $i18n('components') }}</h4>
            <visible-component></visible-component>
          </el-col>

          <el-col :xs="24" :md="12">
            <h4>{{ $i18n('controls') }}</h4>
            <controls-component></controls-component>
          </el-col>
        </el-row>
      </el-col>
      <el-col :span="6" class="sidebar" v-if="loaded">
        <el-row :gutter="20">
          <el-col>
            <h2>{{ $i18n('show_poster') }}</h2>
            <poster-component></poster-component>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col>
            <h2>{{ $i18n('config') }}</h2>
            <el-tooltip class="item" popper-class="tooltip" effect="dark" :content="$i18n('enclosures_tooltip')" placement="left">
              <h4>{{ $i18n('enclosures') }}</h4>
            </el-tooltip>
            <enclosures-component></enclosures-component>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col>
            <el-button type="primary" size="large" @click="save()">{{ $i18n('save') }}</el-button>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import { get } from 'lodash/fp'

  import ThemeComponent from './components/Theme'
  import TabsComponent from './components/Tabs'
  import VisibleComponent from './components/Components'
  import ControlsComponent from './components/Controls'
  import EnclosuresComponent from './components/Enclosures'
  import PosterComponent from './components/Poster'

  export default {
    name: 'configurator',

    methods: {
      ...mapActions(['boot', 'save'])
    },

    computed: mapState({
      loaded: get('loaded')
    }),

    mounted () {
      this.boot()
    },

    components: {
      ThemeComponent,
      TabsComponent,
      VisibleComponent,
      ControlsComponent,
      EnclosuresComponent,
      PosterComponent
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
    height: 100%;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    padding: $spacing;
  }

  .preview {
    margin-bottom: $spacing * 2;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .tooltip {
    width: 300px;
  }

  .el-checkbox+.el-checkbox {
    margin-left: 0;
  }

  .el-checkbox {
    margin-right: $spacing;
  }

</style>

<template>
  <div>
    <el-card class="config-card">
      <h4 class="card-title" slot="header">Preview</h4>
      <div class="preview-options">
        <div class="option">
          <h4 class="option-title">Config</h4>
          <el-select class="select" :disabled="routeName === 'config'" :value="preview.config" @change="updatePreviewOption({ option: 'config', value: $value })" placeholder="Select Config" size="small">
            <el-option
              v-for="item in configList"
              :key="`config-${item}`"
              :label="item"
              :value="item"
            >
            </el-option>
          </el-select>
        </div>
        <div class="option">
          <h4 class="option-title">Theme</h4>
          <el-select class="select" :disabled="routeName === 'theme'" :value="preview.theme" @change="updatePreviewOption({ option: 'theme', value: $value })" placeholder="Select Theme" size="small">
            <el-option
              v-for="item in themeList"
              :key="`theme-${item}`"
              :label="item"
              :value="item"
            >
            </el-option>
          </el-select>
        </div>
        <div class="option">
          <h4 class="option-title">Templates</h4>
          <el-select class="select" :disabled="routeName === 'template'" :value="preview.template" @change="updatePreviewOption({ option: 'template', value: $event })" placeholder="Select Template" size="small">
            <el-option
              v-for="item in templateList"
              :key="`template-${item}`"
              :label="item"
              :value="item"
            >
            </el-option>
          </el-select>
        </div>
        <div class="option">
          <h4 class="option-title">Sizes</h4>
          <el-select class="select" :value="preview.size" @change="updatePreviewOption({ option: 'size', value: $event })" placeholder="Select Template" size="small">
            <el-option
              v-for="item in sizes"
              :key="`size-${item}`"
              :label="item"
              :value="item"
            >
            </el-option>
          </el-select>
        </div>
      </div>
      <player ref="player" :config="config" :template="template" :theme="theme" :size="preview.size" :key="configHash" />
    </el-card>
  </div>
</template>

<script>
import Player from './Player'
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import hash from 'object-hash'


export default {
  components: {
    Player
  },

  data() {
    return {
      sizes: ['mobile', 'tablet', 'desktop']
    }
  },

  computed: {
    ...mapGetters(['routeName', 'configList', 'themeList', 'templateList', 'preview', 'routeName', 'routeId', 'configs', 'templates', 'themes']),
    config() {
      return get(this.configs, this.preview.config, {})
    },
    template() {
      return get(this.templates, this.preview.template, '')
    },
    theme() {
      return get(this.themes, this.preview.theme, {})
    },
    configHash() {
      return hash({ ...this.config, template: this.template, theme: this.theme, size: this.preview.size })
    }
  },

  watch: {
    routeName() {
      this.updatePreviewOption({ option: this.routeName, value: this.routeId })
    }
  },

  methods: mapActions(['updatePreviewOption'])
}
</script>

<style lang="scss" scoped>
.config-card {
  margin-bottom: 2em;
  width: 100%;

  .card-title {
    margin: 0;
  }
}

.preview-options {
  display: flex;
  margin-bottom: 2em;

  .option {
    flex: 1 1 0px;
    margin-right: 2em;
  }
}

.option-title {
  margin: 0 0 0.5em 0;
}

.select {
  input {
    background: #fff;
    border: 1px solid #dcdfe6;
  }
}
</style>

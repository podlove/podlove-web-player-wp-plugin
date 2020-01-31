<template>
  <div class="preview">
    <card title="Preview">
      <div class="flex mb-6">
        <form-element label="Config">
          <el-select
            class="select"
            :disabled="name === 'config'"
            :value="preview.config"
            @change="updatePreviewOption({ option: 'config', value: $value })"
            placeholder="Select Config"
            size="small"
          >
            <el-option v-for="item in configList" :key="`config-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element label="Theme">
          <el-select
            class="select"
            :disabled="name === 'theme'"
            :value="preview.theme"
            @change="updatePreviewOption({ option: 'theme', value: $value })"
            placeholder="Select Theme"
            size="small"
          >
            <el-option v-for="item in themeList" :key="`theme-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element label="Templates">
          <el-select
            class="select"
            :disabled="name === 'template'"
            :value="preview.template"
            @change="updatePreviewOption({ option: 'template', value: $event })"
            placeholder="Select Template"
            size="small"
          >
            <el-option v-for="item in templateList" :key="`template-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element label="Sizes">
          <el-select
            class="select"
            :value="preview.size"
            @change="updatePreviewOption({ option: 'size', value: $event })"
            placeholder="Select Template"
            size="small"
          >
            <el-option v-for="item in sizes" :key="`size-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>
      </div>
      <player
        v-if="source"
        ref="player"
        :config="config"
        :template="template"
        :theme="theme"
        :size="preview.size"
        :key="configHash"
        @ready="connectPlayerStore"
      />
    </card>
  </div>
</template>

<script>
import Player from './Player'
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import hash from 'object-hash'
import { setTheme } from '@podlove/player-actions/theme'
import Card from './Card'
import FormElement from './FormElement'

export default {
  components: {
    Player,
    Card,
    FormElement,
  },

  data() {
    return {
      store: {},
      sizes: ['mobile', 'tablet', 'desktop'],
    }
  },

  computed: {
    ...mapGetters('configs', ['configList', 'configs']),
    ...mapGetters('themes', ['themes', 'themeList']),
    ...mapGetters('templates', ['templates', 'templateList']),
    ...mapGetters('settings', ['source']),
    ...mapGetters('preview', ['preview']),
    ...mapGetters('router', ['name', 'id']),
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
      return hash({ ...this.config, template: this.template, size: this.preview.size, source: this.source })
    },
  },

  watch: {
    name() {
      this.updatePreviewOption({ option: this.name, value: this.id })
    },
    id() {
      this.updatePreviewOption({ option: this.name, value: this.id })
    },
    theme: {
      handler(val) {
        this.updateTheme(val)
      },
      deep: true,
    },
  },

  mounted() {
    this.updatePreviewOption({ option: this.name, value: this.id })
  },

  methods: {
    ...mapActions('preview', ['updatePreviewOption']),

    connectPlayerStore(store) {
      this.store = store
    },

    updateTheme(theme) {
      if (!this.store.dispatch) {
        return
      }

      this.store.dispatch(
        setTheme({
          version: 5,
          theme,
        })
      )
    },
  },
}
</script>

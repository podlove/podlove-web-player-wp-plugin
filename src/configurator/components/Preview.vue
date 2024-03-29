<template>
  <div class="preview">
    <card title="Preview">
      <div class="flex mb-6">
        <form-element :label="$i18n(['preview', 'config'])">
          <el-select
            class="select"
            :disabled="name === 'config'"
            :value="preview.config"
            @change="updatePreviewOption({ option: 'config', value: $event })"
            :placeholder="$i18n(['preview', 'config-placeholder'])"
            size="small"
          >
            <el-option v-for="item in configList" :key="`config-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element :label="$i18n(['preview', 'theme'])">
          <el-select
            class="select"
            :disabled="name === 'theme'"
            :value="preview.theme"
            @change="updatePreviewOption({ option: 'theme', value: $event })"
            :placeholder="$i18n(['preview', 'theme-placeholder'])"
            size="small"
          >
            <el-option v-for="item in themeList" :key="`theme-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element :label="$i18n(['preview', 'template'])">
          <el-select
            class="select"
            :disabled="name === 'template'"
            :value="preview.template"
            @change="updatePreviewOption({ option: 'template', value: $event })"
            :placeholder="$i18n(['preview', 'template-placeholder'])"
            size="small"
          >
            <el-option v-for="item in templateList" :key="`template-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element :label="$i18n(['preview', 'size'])">
          <el-select
            class="select"
            :value="preview.size"
            @change="updatePreviewOption({ option: 'size', value: $event })"
            :placeholder="$i18n(['preview', 'size-placeholder'])"
            size="small"
          >
            <el-option v-for="item in Object.keys(sizes)" :key="`size-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>
      </div>
      <player
        v-if="source"
        ref="player"
        :config="config"
        :template="template"
        :theme="theme"
        :size="sizes[preview.size]"
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
      store: {}
    }
  },

  computed: {
    ...mapGetters('configs', ['configList', 'configs']),
    ...mapGetters('themes', ['themes', 'themeList']),
    ...mapGetters('templates', ['templates', 'templateList']),
    ...mapGetters('settings', ['source']),
    ...mapGetters('preview', ['preview', 'contentWidth']),
    ...mapGetters('router', ['name', 'id']),

    config() {
      const config = get(this.configs, this.preview.config, {})
      const clients = get(config, ['subscribe-button', 'clients'], [])

      return {
        ...config,
        ['subscribe-button']: clients.length > 0 ? get(config, 'subscribe-button') : null
      }
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
    sizes() {
      return {
        ...(this.contentWidth ? { content: this.contentWidth } : {}),
        mobile: 350,
        tablet: 600,
        desktop: 950
      }
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

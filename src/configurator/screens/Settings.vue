<template>
  <div class="template">
    <card :title="$i18n(['settings', 'defaults'])">
      <form-element full :label="$i18n(['settings', 'default-config'])">
       <el-select
          :value="defaults.config"
          size="small"
          @change="updateDefault({ type: 'config', value: $event })"
        >
          <el-option v-for="(item, index) in configList" :value="item" :key="`default-config-${index}`"></el-option>
        </el-select>
      </form-element>

      <form-element full :label="$i18n(['settings', 'default-theme'])">
       <el-select
          :value="defaults.theme"
          size="small"
          @change="updateDefault({ type: 'theme', value: $event })"
        >
          <el-option v-for="(item, index) in themeList" :value="item" :key="`default-theme-${index}`"></el-option>
        </el-select>
      </form-element>

      <form-element full :label="$i18n(['settings', 'default-template'])">
       <el-select
          :value="defaults.template"
          size="small"
          @change="updateDefault({ type: 'template', value: $event })"
        >
          <el-option v-for="(item, index) in templateList" :value="item" :key="`default-theme-${index}`"></el-option>
        </el-select>
      </form-element>
    </card>

    <card :title="$i18n(['settings', 'source'])">
      <form-element full>
       <el-select
          :placeholder="$i18n(['settings', 'select-source'])"
          :value="source"
          size="small"
          @change="updateSource"
        >
          <el-option v-for="(item, index) in sources" :label="item.label" :value="item.value" :key="`source-${index}`"></el-option>
        </el-select>
      </form-element>
    </card>

     <card :title="$i18n(['settings', 'enclosure'])">
      <form-element full>
       <el-select
          :value="enclosure"
          size="small"
          @change="updateEnclosure"
        >
          <el-option :value="null" :label="$i18n(['settings', 'enclosure-disabled'])"></el-option>
          <el-option value="top" :label="$i18n(['settings', 'enclosure-top'])"></el-option>
          <el-option value="bottom" :label="$i18n(['settings', 'enclosure-bottom'])"></el-option>
        </el-select>
      </form-element>
    </card>

     <card :title="$i18n(['settings', 'legacy'])">
      <form-element full>
       <el-checkbox
          :value="legacy"
          size="small"
          @change="updateLegacy"
        >
        {{ $i18n(['settings', 'legacy-browser']) }}
        </el-checkbox>
      </form-element>
    </card>
  </div>
</template>

<script>
import { reduce, get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { Card, FormElement } from '../components'

export default {
  computed: {
    ...mapGetters('settings', ['source', 'settings', 'enclosure', 'legacy', 'defaults']),
    ...mapGetters('configs', ['configList']),
    ...mapGetters('themes', ['themeList']),
    ...mapGetters('templates', ['templateList']),

    sources() {
      return reduce(get(this.settings, 'source.items', {}), (result, value, label) => [...result, { label, value }], [])
    }
  },

  components: {
    Card,
    FormElement
  },

  methods: mapActions('settings', ['updateSource', 'updateEnclosure', 'updateLegacy', 'updateDefault'])
}
</script>

<style lang="scss">

</style>

<template>
  <div class="template">
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
    ...mapGetters('settings', ['source', 'settings', 'enclosure', 'legacy']),

    sources() {
      return reduce(get(this.settings, 'source.items', {}), (result, value, label) => [...result, { label, value }], [])
    }
  },

  components: {
    Card,
    FormElement
  },

  methods: mapActions('settings', ['updateSource', 'updateEnclosure', 'updateLegacy'])
}
</script>

<style lang="scss">

</style>

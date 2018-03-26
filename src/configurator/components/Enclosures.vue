<template>
   <el-form label-position="top" label-width="100px">
    <el-form-item>
      <el-checkbox class="block-checkbox"
        @input="checked => setEnclosure({ key: 'enabled', value: checked })" :checked="enclosure.enabled">{{ $i18n('enclosures_enabled') }}</el-checkbox>
      <el-checkbox class="block-checkbox"
        @input="checked => setEnclosure({ key: 'bottom', value: checked })" :checked="enclosure.enabled && enclosure.bottom" :disabled="!enclosure.enabled">{{ $i18n('enclosures_bottom') }}</el-checkbox>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { get } from 'lodash/fp'
import { head } from 'lodash'

export default {
  computed: mapState({
    enclosure: get('config.enclosure')
  }),
  methods: mapActions(['setEnclosure'])
}
</script>

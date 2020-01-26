<template>
  <div class="template">
    <card title="Source">
      <form-element>
       <el-select
          placeholder="Select Source"
          :value="source"
          size="small"
          @change="updateSource"
        >
          <el-option v-for="(item, index) in sources" :label="item.label" :value="item.value" :key="`source-${index}`"></el-option>
        </el-select>
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
    ...mapGetters(['source', 'settings']),

    sources() {
      return reduce(get(this.settings, 'source.items', {}), (result, value, label) => [...result, { label, value }], [])
    }
  },

  components: {
    Card,
    FormElement
  },

  methods: mapActions(['updateSource'])
}
</script>

<style lang="scss">

</style>

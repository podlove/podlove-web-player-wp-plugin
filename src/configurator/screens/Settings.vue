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
          <el-option label="CDN" value="https://cdn.podlove.org/web-player/5.x/embed.js"></el-option>
          <el-option label="Local" value="/wp-content/plugins/podlove-web-player-beta/web-player/embed.js"></el-option>
        </el-select>
      </form-element>
    </card>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { Card, FormElement } from '../components';

export default {
  computed: mapGetters(['source']),

  components: {
    Card,
    FormElement
  },

  methods: mapActions(['updateSource'])
}
</script>

<style lang="scss">

</style>

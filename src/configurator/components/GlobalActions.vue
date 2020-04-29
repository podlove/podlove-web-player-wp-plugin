<template>
  <div class="flex p-2">
    <el-button icon="el-icon-check" type="primary" @click="save">{{ $i18n(['actions', 'save']) }}</el-button>
  </div>
</template>

<script>
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: mapGetters('router', ['name', 'id']),
  methods: {
    ...mapActions('lifecycle', ['save'])
  },
}
</script>

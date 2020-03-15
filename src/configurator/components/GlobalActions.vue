<template>
  <div class="flex p-2">
    <el-button icon="el-icon-check" type="primary" @click="save">{{ $i18n(['actions', 'save']) }}</el-button>
    <el-button
      type="danger"
      icon="el-icon-delete"
      plain
      v-if="this.name !== 'settings'"
      :disabled="this.id === 'default'"
      @click="showDeleteModal({ target: name, id })"
      >{{ $i18n(['actions', 'delete']) }}</el-button
    >
  </div>
</template>

<script>
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: mapGetters('router', ['name', 'id']),
  methods: {
    ...mapActions('lifecycle', ['save']),
    ...mapActions('modal', ['showDeleteModal']),
  },
}
</script>

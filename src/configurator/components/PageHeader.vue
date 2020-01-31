<template>
  <div class="pb-2 pt-2 w-full flex items-center justify-between">
    <h2 class="text-xl font-bold">{{ title }}</h2>
    <el-row>
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
    </el-row>
  </div>
</template>

<script>
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters('router', ['name', 'id']),
    title() {
      return this.$i18n([this.name, 'title'], { id: this.id })
    },
  },
  methods: {
    ...mapActions('lifecycle', ['save']),
    ...mapActions('modal', ['showDeleteModal']),
  },
}
</script>

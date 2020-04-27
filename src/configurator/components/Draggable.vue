<template>
  <draggable class="drag-list" :list="list" group="channels" @change="change">
    <el-tag
      class="draggable"
      v-for="element in list"
      :key="id(element)"
      closable
      :hit="selected === element"
      :type="type(element)"
      @click="click(element)"
      @close="remove(element)"
    >
      {{ title(element) }}
    </el-tag>
  </draggable>
</template>

<script>
import Draggable from 'vuedraggable'

export default {
  props: {
    list: {
      type: Array,
      default: []
    },
    selected: {
      type: String,
      default: ''
    },
    type: {
      type: Function,
      default: () => ''
    },
    title: {
      type: Function,
      default: (element) => element
    },
    id: {
      type: Function,
      default: (element) => element
    }
  },

  methods: {
    change() {
      this.$emit('change', this.list)
    },
    click(element) {
      this.$emit('click', element)
    },
    remove(element) {
      this.$emit('remove', element)
    }
  },

  components: {
    Draggable
  }
}
</script>

<style lang="scss">
.drag-list {
  width: 100%;
  .draggable {
    width: 100%;
    display: block;
    position: relative;
    cursor: move;
    margin-bottom: 0.5em;

    .el-icon-close {
      position: absolute;
      right: 5px;
      top: 50%;
      margin-top: -8px;
    }
  }
}
</style>

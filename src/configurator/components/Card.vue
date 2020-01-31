<template>
  <el-card class="w-full mb-6" style="min-width: 450px;">
    <h4 class="m-0 font-bold" slot="header" v-if="title">{{ title }}</h4>
    <slot></slot>
  </el-card>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: null
    }
  }
}
</script>

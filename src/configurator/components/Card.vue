<template>
  <el-card class="config-card">
    <h4 class="card-title" slot="header" v-if="title">{{ title }}</h4>
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

<style lang="scss">
.config-card {
  width: 100%;
  min-width: 500px;
  margin-bottom: 2em;

  .card-title {
    margin: 0;
  }

  .card-content {
    display: flex;
  }
}

</style>

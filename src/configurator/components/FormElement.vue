<template>
  <div class="form-element" :class="{ full }">
    <h4 class="title" v-if="label">{{ label }}</h4>
    <div class="input">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: null
    },
    full: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss">
.form-element {
  width: 200px;
  margin-right: 2em;

  .title {
    margin: 0 0 0.5em 0;

    .el-icon-info {
      margin-left: 0.25em;
    }
  }

  &.full {
    width: 400px;
    margin-bottom: 1em;
  }

  .input {
    .el-select, .el-switch {
      margin-bottom: 0.5em;
      width: 100%;
      min-height: 32px;
      display: flex;
      align-items: center;
    }

    .el-select, .el-input {
      input {
        background: #fff;
        border: 1px solid #dcdfe6;
      }
    }
  }
}
</style>

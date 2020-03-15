<template>
  <div class="form-element w-48 mr-6" :class="{ full }">
    <h4 class="mb-2" v-if="label">{{ label }}</h4>
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
      }
    }
  }
}
</style>

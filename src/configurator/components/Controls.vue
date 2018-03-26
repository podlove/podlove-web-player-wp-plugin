<template>
  <el-form label-position="top" label-width="100px">
    <el-form-item>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'controlSteppers', visible })" :checked="visibleComponents.controlSteppers">Steppers</el-checkbox>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'controlChapters', visible })" :checked="visibleComponents.controlChapters">Chapters</el-checkbox>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { get } from 'lodash/fp'
import { head } from 'lodash'

export default {
  computed: mapState({
    visibleComponents: get('config.visibleComponents')
  }),
  methods: mapActions(['setComponent'])
}
</script>

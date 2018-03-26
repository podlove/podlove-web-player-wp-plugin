<template>
   <el-form label-position="top" label-width="100px">
    <el-form-item>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'poster', visible })" :checked="visibleComponents.poster">{{ $i18n('components_poster') }}</el-checkbox>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'showTitle', visible })" :checked="visibleComponents.showTitle">{{ $i18n('components_show_title') }}</el-checkbox>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'episodeTitle', visible })" :checked="visibleComponents.episodeTitle">{{ $i18n('components_episode_title') }}</el-checkbox>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'subtitle', visible })" :checked="visibleComponents.subtitle">{{ $i18n('components_subtitle') }}</el-checkbox>
      <el-checkbox class="block-checkbox" @input="visible => setComponent({ component: 'progressbar', visible })" :checked="visibleComponents.progressbar">{{ $i18n('components_progressbar') }}</el-checkbox>
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

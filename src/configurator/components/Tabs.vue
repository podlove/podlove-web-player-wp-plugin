<template>
  <el-form label-position="top" label-width="100px">
    <el-form-item :label="$i18n('tabs_available')">
      <el-checkbox @input="visible => setComponent({ component: 'tabInfo', visible })" :checked="visibleComponents.tabInfo">{{ $i18n('tab_info') }}</el-checkbox>
      <el-checkbox @input="visible => setComponent({ component: 'tabChapters', visible })" :checked="visibleComponents.tabChapters">{{ $i18n('tab_chapters') }}</el-checkbox>
      <el-checkbox @input="visible => setComponent({ component: 'tabShare', visible })" :checked="visibleComponents.tabShare">{{ $i18n('tab_share') }}</el-checkbox>
      <el-checkbox @input="visible => setComponent({ component: 'tabDownload', visible })" :checked="visibleComponents.tabDownload" >{{ $i18n('tab_download') }}</el-checkbox>
      <el-checkbox @input="visible => setComponent({ component: 'tabAudio', visible })" :checked="visibleComponents.tabAudio" label="audio">{{ $i18n('tab_audio') }}</el-checkbox>
    </el-form-item>

    <el-form-item :label="$i18n('tabs_default_active')">
      <el-select clearable @input="setTab" :value="activeTab" :placeholder="$i18n('select')" :disabled="
        !visibleComponents.tabInfo &&
        !visibleComponents.tabChapters &&
        !visibleComponents.tabShare &&
        !visibleComponents.tabDownload &&
        !visibleComponents.tabAudio
      ">
        <el-option value="none" :label="$i18n('default')"></el-option>
        <el-option value="info" :label="$i18n('tab_info')" v-if="visibleComponents.tabInfo"></el-option>
        <el-option value="chapters" :label="$i18n('tab_chapters')" v-if="visibleComponents.tabChapters"></el-option>
        <el-option value="share" :label="$i18n('tab_share')" v-if="visibleComponents.tabShare"></el-option>
        <el-option value="download" :label="$i18n('tab_download')" v-if="visibleComponents.tabDownload"></el-option>
        <el-option value="audio" :label="$i18n('tab_audio')" v-if="visibleComponents.tabAudio"></el-option>
      </el-select>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { get } from 'lodash/fp'
import { head } from 'lodash'

export default {
  computed: {
    ...mapState({
      tabs: get('config.tabs'),
      visibleComponents: get('config.visibleComponents')
    }),
    activeTab () {
      const activeTab = Object.keys(this.tabs).filter(tab => this.tabs[tab])
      return head(activeTab)
    }
  },
  methods: mapActions(['setComponent', 'setTab'])
}
</script>

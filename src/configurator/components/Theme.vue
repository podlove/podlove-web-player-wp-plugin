<template>
   <el-form label-position="top" label-width="100px">
      <el-form-item :label="$i18n('theme_main')">
      <el-input v-model="theme.main" @input="changeTheme('main')">
        <el-color-picker slot="append" class="borderless" v-model="theme.main" @active-change="color => previewTheme('main', color)" @change="color => changeTheme('main', color)"></el-color-picker>
      </el-input>
    </el-form-item>
    <el-form-item :label="$i18n('theme_highlight')">
      <el-input class="inline-input" v-model="theme.highlight" @input="changeTheme('highlight')">
        <el-color-picker slot="append" class="borderless" v-model="theme.highlight" @active-change="color => previewTheme('highlight', color)" @change="color => changeTheme('highlight', color)"></el-color-picker>
      </el-input>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { get } from 'lodash/fp'

export default {
  computed: mapState({
    theme: get('config.theme')
  }),

  methods: {
    ...mapActions(['setTheme', 'setComponent', 'setTab', 'simulateTheme']),
    previewTheme (prop, color) {
      this.simulateTheme({ ...this.theme, [prop]: color })
    },
    changeTheme (prop, color) {
      this.setTheme({ ...this.theme, [prop]: color })
    }
  }
}
</script>

<style lang="scss">
  .borderless {
    height: 22px;
    padding-top: 4px;

    .el-color-picker__trigger {
      border: none;
      height: 100%;
      padding: 0;
    }
  }
</style>


<template>
  <el-menu :default-active="active" class="navigation">
    <el-submenu index="1">
      <template slot="title">
        <i class="el-icon-c-scale-to-original"></i>
        <span slot="title">Configuration</span>
      </template>
      <el-menu-item v-for="(config, index) in configList" :key="`config-${index}`" @click="navigate({ name: 'config', params: { id: config } })" :index="`1-${index + 1}`">{{ config }}</el-menu-item>
      <el-menu-item index="1-add" @click="showCreateModal('config')">
        <i class="el-icon-circle-plus-outline"></i>
        <span slot="title">Add</span>
      </el-menu-item>
    </el-submenu>
    <el-submenu index="2">
      <template slot="title">
        <i class="el-icon-view"></i>
        <span slot="title">Themes</span>
      </template>
      <el-menu-item v-for="(theme, index) in themeList" :key="`theme-${index}`" @click="navigate({ name: 'theme', params: { id: theme } })" :index="`2-${index + 1}`">{{ theme }}</el-menu-item>
      <el-menu-item index="2-add" @click="showCreateModal('theme')">
        <i class="el-icon-circle-plus-outline"></i>
        <span slot="title">Add</span>
      </el-menu-item>
    </el-submenu>
    <el-submenu index="3">
      <template slot="title">
        <i class="el-icon-document"></i>
        <span slot="title">Templates</span>
      </template>
      <el-menu-item v-for="(template, index) in templateList" :key="`template-${index}`" @click="navigate({ name: 'template', params: { id: template } })" :index="`3-${index + 1}`">{{ template }}</el-menu-item>
      <el-menu-item index="3-add" @click="showCreateModal('template')">
        <i class="el-icon-circle-plus-outline"></i>
        <span slot="title">Add</span>
      </el-menu-item>
    </el-submenu>
    <el-menu-item index="4" @click="navigate({ name: 'settings' })">
      <i class="el-icon-setting"></i>
      <span slot="title">Settings</span>
    </el-menu-item>
  </el-menu>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters('configs', ['configList']),
    ...mapGetters('themes', ['themeList']),
    ...mapGetters('templates', ['templateList']),
    ...mapGetters(['routeName', 'routeId']),
    active() {
      switch (this.routeName) {
        case 'config':
          return `1-${this.configList.findIndex(id => id === this.routeId) + 1}`
        case 'theme':
          return `2-${this.themeList.findIndex(id => id === this.routeId) + 1}`
        case 'template':
          return `3-${this.templateList.findIndex(id => id === this.routeId) + 1}`
        case 'settings':
          return '4'
      }
    }
  },
  methods: {
    ...mapActions(['showCreateModal']),
    navigate(param) {
      this.$router.push(param).catch(err => {})
    }
  }
};
</script>

<style lang="scss" scoped>
  .navigation {
    height: 100%;
  }
</style>

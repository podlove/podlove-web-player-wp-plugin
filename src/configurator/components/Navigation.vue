<template>
  <el-menu :default-active="active" class="w-full" mode="horizontal">
    <el-submenu index="1">
      <template slot="title">
        <i class="el-icon-c-scale-to-original"></i>
        <span slot="title">{{ $i18n(['navigation', 'configuration']) }}</span>
      </template>
      <el-menu-item
        v-for="(config, index) in order(configList)"
        :key="`config-${index}`"
        @click="navigate({ name: 'config', params: { id: config } })"
        :index="`1-${index + 1}`"
      >
        <div class="flex w-full items-center justify-between" :class="{ 'font-bold': activeItem('config', config) }">
          <span>{{ config }}</span>
          <button
            @click="showDeleteModal({ target: 'config', id: config })"
            class="p-0 -mr-2"
            v-if="config !== 'default'"
          >
            <i class="el-icon-close"></i>
          </button>
        </div>
      </el-menu-item>
      <el-menu-item index="1-add" @click="showCreateModal('config')">
        <i class="el-icon-circle-plus-outline"></i>
        <span slot="title">{{ $i18n(['actions', 'add']) }}</span>
      </el-menu-item>
    </el-submenu>
    <el-submenu index="2">
      <template slot="title">
        <i class="el-icon-view"></i>
        <span slot="title">{{ $i18n(['navigation', 'themes']) }}</span>
      </template>
      <el-menu-item
        v-for="(theme, index) in order(themeList)"
        :key="`theme-${index}`"
        @click="navigate({ name: 'theme', params: { id: theme } })"
        :index="`2-${index + 1}`"
      >
        <div class="flex w-full items-center justify-between" :class="{ 'font-bold': activeItem('theme', theme) }">
          <span>{{ theme }}</span>
          <button @click="showDeleteModal({ target: 'theme', id: theme })" class="p-0 -mr-2" v-if="theme !== 'default'">
            <i class="el-icon-close"></i>
          </button>
        </div>
      </el-menu-item>
      <el-menu-item index="2-add" @click="showCreateModal('theme')">
        <i class="el-icon-circle-plus-outline"></i>
        <span slot="title">{{ $i18n(['actions', 'add']) }}</span>
      </el-menu-item>
    </el-submenu>
    <el-submenu index="3">
      <template slot="title">
        <i class="el-icon-document"></i>
        <span slot="title">{{ $i18n(['navigation', 'templates']) }}</span>
      </template>
      <el-menu-item
        v-for="(template, index) in order(templateList)"
        :key="`template-${index}`"
        @click="navigate({ name: 'template', params: { id: template } })"
        :index="`3-${index + 1}`"
      >
        <div class="flex w-full items-center justify-between"  :class="{ 'font-bold': activeItem('template', template) }">
          <span>{{ template }}</span>
          <button
            @click="showDeleteModal({ target: 'template', id: template })"
            class="p-0 -mr-2"
            v-if="template !== 'default'"
          >
            <i class="el-icon-close"></i>
          </button>
        </div>
      </el-menu-item>
      <el-menu-item index="3-add" @click="showCreateModal('template')">
        <i class="el-icon-circle-plus-outline"></i>
        <span slot="title">{{ $i18n(['actions', 'add']) }}</span>
      </el-menu-item>
    </el-submenu>
    <el-menu-item index="4" @click="navigate({ name: 'settings' })">
      <i class="el-icon-setting"></i>
      <span slot="title">{{ $i18n(['navigation', 'settings']) }}</span>
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
    ...mapGetters('router', ['name', 'id']),
    active() {
      switch (this.name) {
        case 'config':
          return `1-${this.configList.findIndex(id => id === this.id) + 1}`
        case 'theme':
          return `2-${this.themeList.findIndex(id => id === this.id) + 1}`
        case 'template':
          return `3-${this.templateList.findIndex(id => id === this.id) + 1}`
        case 'settings':
          return '4'
      }
    }
  },
  methods: {
    ...mapActions('modal', ['showCreateModal', 'showDeleteModal']),
    navigate(param) {
      this.$router.push(param).catch(err => {})
    },
    order(list) {
      return list.sort((a, b) => {
        if (a === 'default') {
          return -1
        }
        if (a < b) {
          return -1
        }
        if (a > b) {
          return 1
        }
        return 0
      })
    },
    activeItem(type, id) {
      return this.name === type && this.id === id;
    },
  },
}
</script>

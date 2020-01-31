<template>
  <div v-if="open">
    <div class="v-modal z-40" tabindex="0" @click="closeModal"></div>
    <div
      tabindex="-1"
      role="dialog"
      aria-modal="true"
      aria-label="Tip"
      class="el-message-box__wrapper z-50"
    >
      <div class="el-message-box">
        <div class="el-message-box__header" v-if="title">
          <div class="el-message-box__title">{{ title }}</div>
          <button type="button" aria-label="Close" class="el-message-box__headerbtn" @click="closeModal">
            <i class="el-message-box__close el-icon-close"></i>
          </button>
        </div>
        <div class="el-message-box__content">
          <div class="el-message-box__container" v-if="message">
            <div class="el-message-box__message">{{ message }}</div>
          </div>
        </div>
        <div class="el-message-box__btns">
          <el-button size="small" @click="closeModal">Cancel</el-button>
          <el-button ref="delete" type="danger" icon="el-icon-delete" size="small" @click="remove({ target, id })">Delete</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters('modal', ['target', 'id', 'type', 'visible']),

    title() {
      switch (this.target) {
        case 'config':
          return 'Delete Config'
        case 'theme':
          return 'Delete Theme'
        case 'template':
          return 'Delete Template'
        default:
          return null
      }
    },

    message() {
      switch (this.target) {
        case 'config':
          return `Do you really want to delete the config ${this.id}`;
        case 'theme':
          return `Do you really want to delete the theme ${this.id}`;
        case 'template':
          return `Do you really want to delete the template ${this.id}`;
        default:
          return null
      }
    },

    open() {
      return this.visible && this.type === 'delete'
    }
  },

  watch: {
    visible() {
      setTimeout(() => {
        this.open && this.$refs.delete.$el.focus()
      }, 10)
    }
  },

  created() {
    document.addEventListener('keyup', evt => evt.keyCode === 27 && this.visible && this.closeModal())
  },

  methods: {
    ...mapActions('lifecycle', ['remove']),
    ...mapActions('modal', ['closeModal'])
  }
};
</script>

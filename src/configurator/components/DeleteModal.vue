<template>
  <div v-if="visible">
    <div class="v-modal" tabindex="0" style="z-index: 998;" @click="closeModal"></div>
    <div
      tabindex="-1"
      role="dialog"
      aria-modal="true"
      aria-label="Tip"
      class="el-message-box__wrapper"
      style="z-index: 999;"
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
            <!---->
            <div class="el-message-box__message">{{ message }}</div>
          </div>
        </div>
        <div class="el-message-box__btns">
          <el-button size="small" @click="closeModal">Cancel</el-button>
          <el-button type="danger" icon="el-icon-delete" size="small" @click="remove({ type: modal.target, id: modal.id })">Delete</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters(['modal']),

    title() {
      switch (this.modal.target) {
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
      switch (this.modal.target) {
        case 'config':
          return `Do you really want to delete the config ${this.modal.id}`;
        case 'theme':
          return `Do you really want to delete the theme ${this.modal.id}`;
        case 'template':
          return `Do you really want to delete the template ${this.modal.id}`;
        default:
          return null
      }
    },

    visible() {
      return this.modal.visible && this.modal.type === 'delete'
    }
  },

  created() {
    document.addEventListener('keyup', evt => evt.keyCode === 27 && this.modal.visible && this.closeModal())
  },

  methods: mapActions(['closeModal', 'remove'])
};
</script>

<style lang="scss">
  .invalid {
    input,
    .el-input-group__append {
      border-color: #f56c6c !important;
    }
  }

  .error-message {
    margin: 0.25em 0 0 0.5em;
    color: #f56c6c;
    font-size: 0.8em;
  }
</style>

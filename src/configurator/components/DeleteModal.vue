<template>
  <div v-if="open">
    <div class="v-modal z-40" tabindex="0" @click="closeModal"></div>
    <div
      tabindex="-1"
      role="dialog"
      aria-modal="true"
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
          <el-button size="small" @click="closeModal">{{ $i18n(['modal', 'cancel']) }}</el-button>
          <el-button ref="delete" type="danger" icon="el-icon-delete" size="small" :disabled="disabled" @click="remove({ target, id })">{{ $i18n(['modal', 'delete']) }}</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from 'lodash';
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters('modal', ['target', 'id', 'type', 'visible']),
    ...mapGetters('settings', ['defaults']),

    title() {
      return this.$i18n([this.target, 'delete'])
    },

    message() {
      return this.disabled ? this.$i18n(['modal', 'id-assigned'], { id: this.id, type: this.target }) : this.$i18n([this.target, 'delete-message'], { id: this.id })
    },

    open() {
      return this.visible && this.type === 'delete'
    },

    disabled() {
      return this.id === get(this.defaults, this.target)
    },
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

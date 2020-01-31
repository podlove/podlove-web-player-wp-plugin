<template>
  <div v-if="open">
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
              <div class="el-message-box__message">{{ message }}</div>
            </div>
            <div class="el-message-box__input">
              <el-form @submit.native="submit">
                <el-input ref="input" :value="value" size="small" :class="{ invalid: error }" @input="updateCreateModalValue"></el-input>
                <div class="error-message" v-if="error">{{ error }}</div>
              </el-form>
            </div>
          </div>
          <div class="el-message-box__btns">
            <el-button size="small" @click="closeModal">Cancel</el-button>
            <el-button ref="submit" size="small" type="primary" :disabled="!value || !!error" @click="create">Add</el-button>
          </div>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { get } from 'lodash'

export default {
  computed: {
    ...mapGetters('modal', ['target', 'type', 'visible', 'error', 'value']),

    title() {
      switch (this.target) {
        case 'config':
          return 'Add Config'
        case 'theme':
          return 'Add Theme'
        case 'template':
          return 'Add Template'
        default:
          return null
      }
    },

    message() {
      switch (this.target) {
        case 'config':
          return 'Please set a config id'
        case 'theme':
          return 'Please set a theme id'
        case 'template':
          return 'Please set a template id'
        default:
          return null
      }
    },

    open() {
      return this.visible && this.type === 'create'
    }
  },

  created() {
    document.addEventListener('keyup', evt => evt.keyCode === 27 && this.visible && this.closeModal())
  },

  watch: {
    visible() {
      setTimeout(() => {
        const input = get(this.$refs, 'input.$el')
        this.open && input && this.$refs.input.$el.querySelector('input').focus()
      }, 10)
    }
  },

  methods: {
    ...mapActions('modal', ['updateCreateModalValue', 'closeModal']),
    ...mapActions(['create']),

    submit(event) {
      event.preventDefault()
      this.create()
    }
  }
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

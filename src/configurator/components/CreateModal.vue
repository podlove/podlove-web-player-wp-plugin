<template>
  <div v-if="open" class="create-modal">
    <div class="v-modal z-40" tabindex="0" @click="closeModal"></div>
    <div tabindex="-1" role="dialog" aria-modal="true" aria-label="Tip" class="el-message-box__wrapper z-50">
      <div class="el-message-box">
        <div class="el-message-box__header" v-if="title">
          <div class="el-message-box__title">{{ title }}</div>
          <button type="button" aria-label="Close" class="el-message-box__headerbtn" @click="closeModal">
            <i class="el-message-box__close el-icon-close"></i>
          </button>
        </div>
        <div class="el-message-box__content">
          <div class="el-message-box__input">
            <el-form @submit.native="submit">
              <form-element full :label="idLabel">
                <el-input
                  ref="input"
                  :value="id"
                  size="small"
                  :class="{ invalid: error }"
                  @input="updateCreateModalId"
                ></el-input>
                <div class="mt-1 mr-0 mb-2 ml-0 text-sm text-red-600" v-if="error">{{ error }}</div>
              </form-element>

              <form-element full :label="blueprintLabel">
                <el-select
                  :value="blueprint"
                  size="small"
                  @change="updateCreateModalBlueprint"
                >
                  <el-option
                    v-for="(item, index) in blueprintValues"
                    :value="item"
                    :key="`blueprint-${index}`"
                  ></el-option>
                </el-select>
                <ul class="mt-4 ml-6 italic list-disc" v-if="blueprintInfo">
                  <li v-for="info in blueprintInfo" :key="info">{{ info }}</li>
                </ul>
              </form-element>
            </el-form>
          </div>
        </div>
        <div class="el-message-box__btns">
          <el-button size="small" @click="closeModal">{{ $i18n(['modal', 'cancel']) }}</el-button>
          <el-button ref="submit" size="small" type="primary" :disabled="!id || !!error" @click="create">{{
            $i18n(['modal', 'add'])
          }}</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { get, isString } from 'lodash'
import FormElement from './FormElement'

export default {
  components: { FormElement },

  computed: {
    ...mapGetters('modal', ['target', 'type', 'visible', 'error', 'id', 'blueprint']),
    ...mapGetters('presets', ['configs', 'themes', 'templates']),

    title() {
      return this.$i18n([this.target, 'create'])
    },

    idLabel() {
      return this.$i18n([this.target, 'create-id'])
    },

    blueprintLabel() {
      return this.$i18n([this.target, 'create-blueprint'])
    },

    open() {
      return this.visible && this.type === 'create'
    },

    blueprintValues() {
      if (this.target === 'config') {
        return this.configs
      }

      if (this.target === 'theme') {
        return this.themes
      }

      if (this.target === 'template') {
        return this.templates
      }

      return []
    },

    blueprintInfo() {
      const info = this.$i18n([this.target, 'presets', this.blueprint])
      return info ? info.split(';') : null
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
    },
  },

  methods: {
    ...mapActions('modal', ['updateCreateModalId', 'updateCreateModalBlueprint', 'closeModal']),
    ...mapActions('lifecycle', ['create']),

    submit(event) {
      event.preventDefault()
      this.create()
    },
  },
}
</script>

<style lang="scss">
.create-modal {
  .invalid {
    input,
    .el-input-group__append {
      border-color: #f56c6c !important;
    }
  }
  .el-message-box {
    width: 430px;
  }
}
</style>

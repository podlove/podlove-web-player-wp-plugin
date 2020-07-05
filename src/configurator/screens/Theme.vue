<template>
  <div class="theme">
    <card :title="$i18n(['theme', 'colors'])">
      <div class="flex">
        <div>
          <form-element :label="$i18n(['theme', 'color-brand'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('brand')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'brand', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('brand')"
                size="small"
                @input="updateToken({ token: 'brand', color: $event })"
              ></el-input>
            </div>
          </form-element>

          <form-element :label="$i18n(['theme', 'color-brand-dark'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('brandDark')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'brandDark', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('brandDark')"
                size="small"
                @input="updateToken({ token: 'brandDark', color: $event })"
              ></el-input>
            </div>
          </form-element>

          <form-element :label="$i18n(['theme', 'color-brand-darkest'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('brandDarkest')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'brandDarkest', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('brandDarkest')"
                size="small"
                @input="updateToken({ token: 'brandDarkest', color: $event })"
              ></el-input>
            </div>
          </form-element>

          <form-element :label="$i18n(['theme', 'color-brand-lightest'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('brandLightest')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'brandLightest', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('brandLightest')"
                size="small"
                @input="updateToken({ token: 'brandLightest', color: $event })"
              ></el-input>
            </div>
          </form-element>
        </div>
        <div>
          <form-element :label="$i18n(['theme', 'color-shade-base'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('shadeBase')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'shadeBase', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('shadeBase')"
                size="small"
                @input="updateToken({ token: 'shadeBase', color: $event })"
              ></el-input>
            </div>
          </form-element>

          <form-element :label="$i18n(['theme', 'color-shade-dark'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('shadeDark')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'shadeDark', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('shadeDark')"
                size="small"
                @input="updateToken({ token: 'shadeDark', color: $event })"
              ></el-input>
            </div>
          </form-element>

          <form-element :label="$i18n(['theme', 'color-contrast'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('contrast')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'contrast', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('contrast')"
                size="small"
                @input="updateToken({ token: 'contrast', color: $event })"
              ></el-input>
            </div>
          </form-element>

          <form-element :label="$i18n(['theme', 'color-alt'])">
            <div class="color-picker">
              <el-color-picker
                :value="color('alt')"
                size="small"
                :predefine="predefinedColors"
                color-format="hex"
                @change="updateToken({ token: 'alt', color: $event })"
              ></el-color-picker>
              <el-input
                :value="color('alt')"
                size="small"
                @input="updateToken({ token: 'alt', color: $event })"
              ></el-input>
            </div>
          </form-element>
        </div>
      </div>
    </card>

    <card :title="$i18n(['theme', 'fonts'])">
      <form-element full>
        <el-select :value="selected" size="small" @change="selectFont">
          <el-option v-for="item in clients" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </form-element>

      <form-element full :label="$i18n(['theme', 'font-name'])" class="mb-4">
        <div class="input">
          <el-input :value="fontName" size="small" @input="updateFontName({ value: $event })"></el-input>
        </div>
      </form-element>

      <div class="mb-4">
        <form-element :label="$i18n(['theme', 'font-sources'])" full>
          <div class="font-input mb-2">
            <el-input
              :placeholder="$i18n(['theme', 'add-source'])"
              size="small"
              @keyup.enter.native="updateFontSource"
              :class="{ invalid: fonts[selected].error }"
              :value="fonts[selected].src"
              @input="stageFontSource({ value: $event })"
            >
              <el-button slot="append" icon="el-icon-plus" @click="updateFontSource"></el-button>
            </el-input>
            <div class="mt-1 mr-0 mb-2 ml-0 text-sm text-red-600" v-if="fonts[selected].error">{{ fonts[selected].error }}</div>
          </div>

          <div class="sources">
            <el-tag v-for="element in fontSources" class="truncate pr-4" :key="element" closable @close="removeFontSrc({ value: element })">
              {{ element }}
            </el-tag>
          </div>
        </form-element>
      </div>

      <form-element :label="$i18n(['theme', 'font-weight'])" class="mb-4" full>
        <div class="input">
          <el-input :value="fontWeight" size="small" @input="updateFontWeight({ value: $event })"></el-input>
        </div>
      </form-element>

      <form-element :label="$i18n(['theme', 'font-family'])" full>
        <div class="font-input mb-2">
          <el-input
            :placeholder="$i18n(['theme', 'font-add'])"
            size="small"
            @keyup.enter.native="addFontFamily"
            :value="fonts[selected].family"
            @input="stageFontFamily({ value: $event })"
          >
            <el-button slot="append" icon="el-icon-plus" @click="addFontFamily"></el-button>
          </el-input>
        </div>

        <draggable :list="fontFamily" @change="updateFontFamily" @remove="removeFontFamily" />
      </form-element>
    </card>
  </div>
</template>

<script>
import { get, values, uniq, clone } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { Card, FormElement, Draggable } from '../components'

export default {
  data() {
    return {
      clients: ['ci', 'regular', 'bold'],
    }
  },

  components: {
    Card,
    FormElement,
    Draggable,
  },

  computed: {
    ...mapGetters('themes', ['current', 'fonts']),

    selected() {
      return get(this.fonts, 'selected', null)
    },

    tokens() {
      return get(this.current, 'tokens', {})
    },

    fontName() {
      return get(this.current, ['fonts', this.selected, 'name'], '')
    },

    fontSources() {
      return get(this.current, ['fonts', this.selected, 'src'], 300)
    },

    fontWeight() {
      return get(this.current, ['fonts', this.selected, 'weight'], 300)
    },

    fontFamily() {
      return clone(get(this.current, ['fonts', this.selected, 'family'], []))
    },

    predefinedColors() {
      const colors = get(this.current, 'tokens')

      return uniq(values(colors) || [])
    },
  },

  methods: {
    ...mapActions('themes', [
      'updateToken',
      'stageFontSource',
      'updateFontName',
      'updateFontSource',
      'removeFontSrc',
      'updateFontWeight',
      'addFontFamily',
      'stageFontFamily',
      'updateFontFamily',
      'removeFontFamily',
      'selectFont',
    ]),

    color(id) {
      return get(this.tokens, id)
    },

    updateFont(element) {
      this.updateFontFamily({ value: element })
    },
  },
}
</script>

<style lang="scss">
.theme {
  .color-picker {
    display: flex;
    margin-bottom: 1em;

    .el-color-picker {
      margin-right: 1em;
    }
  }

  .font-input mb-2 {
    .invalid {
      input,
      .el-input-group__append {
        border-color: #f56c6c !important;
      }
    }
  }

  .sources {
    .el-tag {
      display: block;
      position: relative;
      cursor: move;
      margin-bottom: 0.25em;

      .el-icon-close {
        position: absolute;
        right: 5px;
        top: 50%;
        margin-top: -8px;
      }
    }
  }
}
</style>

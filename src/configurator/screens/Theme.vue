<template>
  <div class="theme">
    <card title="Colors">
      <div class="color-row">
        <div>
          <form-element label="Brand">
            <div class="color-picker">
              <el-color-picker
                :value="color('brand')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'brand', color: $event })"
              ></el-color-picker>
              <el-input :value="color('brand')" size="small"></el-input>
            </div>
          </form-element>

          <form-element label="Brand Dark">
            <div class="color-picker">
              <el-color-picker
                :value="color('brandDark')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'brandDark', color: $event })"
              ></el-color-picker>
              <el-input :value="color('brandDark')" size="small"></el-input>
            </div>
          </form-element>

          <form-element label="Brand Darkest">
            <div class="color-picker">
              <el-color-picker
                :value="color('brandDarkest')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'brandDarkest', color: $event })"
              ></el-color-picker>
              <el-input :value="color('brandDarkest')" size="small"></el-input>
            </div>
          </form-element>

          <form-element label="Brand Lightest">
            <div class="color-picker">
              <el-color-picker
                :value="color('brandLightest')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'brandLightest', color: $event })"
              ></el-color-picker>
              <el-input :value="color('brandLightest')" size="small"></el-input>
            </div>
          </form-element>
        </div>
        <div>
          <form-element label="Shade Base">
            <div class="color-picker">
              <el-color-picker
                :value="color('shadeBase')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'shadeBase', color: $event })"
              ></el-color-picker>
              <el-input :value="color('shadeBase')" size="small"></el-input>
            </div>
          </form-element>

          <form-element label="Shade Dark">
            <div class="color-picker">
              <el-color-picker
                :value="color('shadeDark')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'shadeDark', color: $event })"
              ></el-color-picker>
              <el-input :value="color('shadeDark')" size="small"></el-input>
            </div>
          </form-element>

          <form-element label="Contrast">
            <div class="color-picker">
              <el-color-picker
                :value="color('contrast')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'contrast', color: $event })"
              ></el-color-picker>
              <el-input :value="color('contrast')" size="small"></el-input>
            </div>
          </form-element>

          <form-element label="Alt">
            <div class="color-picker">
              <el-color-picker
                :value="color('alt')"
                size="small"
                :predefine="predefinedColors"
                show-alpha
                @change="updateToken({ token: 'alt', color: $event })"
              ></el-color-picker>
              <el-input :value="color('alt')" size="small"></el-input>
            </div>
          </form-element>
        </div>
      </div>
    </card>

    <card title="Fonts">
      <form-element full>
        <el-select placeholder="Add" :value="fonts.selected" size="small" @change="selectFont">
          <el-option v-for="item in clients" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </form-element>
      <div class="font-selector">
        <form-element label="Font Sources" full>
          <div class="font-input">
            <el-input
              placeholder="Add source"
              size="small"
              @keyup.enter.native="updateFontSource"
              :class="{ invalid: fonts.ci.error }"
              :value="fonts.ci.src"
              @input="stageFontSource({ value: $event })"
            >
              <el-button slot="append" icon="el-icon-plus" @click="updateFontSource"></el-button>
            </el-input>
            <div class="error-message" v-if="fonts.ci.error">{{ fonts.ci.error }}</div>
          </div>

          <div class="sources">
            <el-tag v-for="element in fontSources" :key="element" closable @close="removeFontSrc({ value: element })">
              {{ element }}
            </el-tag>
          </div>
        </form-element>
      </div>

      <form-element label="Font Weight" class="font-input">
        <el-input-number
          :value="fontWeight"
          size="small"
          type="number"
          :step="100"
          :min="100"
          :max="900"
          @input="updateFontWeight({ value: $event })"
        ></el-input-number>
      </form-element>

      <form-element label="Font Family" full>
        <div class="font-input">
          <el-input
            placeholder="Add source"
            size="small"
            @keyup.enter.native="addFontFamily"
            :value="fonts.ci.family"
            @input="stageFontFamily({ value: $event })"
          >
            <el-button slot="append" icon="el-icon-plus" @click="addFontFamily"></el-button>
          </el-input>
        </div>

        <draggable
          class="drag-list"
          :list="fontFamily"
          group="channels"
          @change="updateFontFamily({ value: fontFamily })"
        >
          <el-tag
            class="draggable-family"
            v-for="element in fontFamily"
            :key="element"
            closable
            @close="removeFontFamily({ value: element })"
          >
            {{ element }}
          </el-tag>
        </draggable>
      </form-element>
    </card>
  </div>
</template>

<script>
import { get, values, uniq, clone } from "lodash";
import { mapGetters, mapActions } from "vuex";
import Draggable from "vuedraggable";
import { Card, FormElement } from "../components";

export default {
  data() {
    return {
      clients: ["ci", "regular", "bold"]
    };
  },

  components: {
    Card,
    FormElement,
    Draggable
  },

  computed: {
    ...mapGetters(["themes", "routeId", "fonts"]),

    theme() {
      return get(this.themes, this.routeId);
    },

    tokens() {
      return get(this.theme, "tokens", {});
    },

    fontSources() {
      return get(this.theme, ["fonts", this.fonts.selected, "src"], 300);
    },

    fontWeight() {
      return get(this.theme, ["fonts", this.fonts.selected, "weight"], 300);
    },

    fontFamily() {
      return clone(get(this.theme, ["fonts", this.fonts.selected, "family"], []));
    },

    predefinedColors() {
      const colors = get(this.theme, "tokens");

      return uniq(values(colors) || []);
    }
  },

  methods: {
    ...mapActions([
      "updateToken",
      "stageFontSource",
      "updateFontSource",
      "removeFontSrc",
      "updateFontWeight",
      "addFontFamily",
      "stageFontFamily",
      "updateFontFamily",
      "removeFontFamily",
      "selectFont"
    ]),

    color(id) {
      return get(this.tokens, id);
    }
  }
};
</script>

<style lang="scss">
.color-row {
  display: flex;
}

.color-picker {
  display: flex;
  margin-bottom: 1em;

  .el-color-picker {
    margin-right: 1em;
  }
}

.font-selector {
  margin-bottom: 1em;
}

.font-input {
  margin-bottom: 0.5em;

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

.draggable-family {
  width: 100%;
  display: block;
  position: relative;
  cursor: move;
  margin-bottom: 0.5em;

  .el-icon-close {
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -8px;
  }
}
</style>

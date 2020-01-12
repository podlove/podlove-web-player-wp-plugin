<template>
  <div class="preview">
    <card title="Preview">
      <div class="preview-options">
        <form-element label="Config">
          <el-select
            class="select"
            :disabled="routeName === 'config'"
            :value="preview.config"
            @change="updatePreviewOption({ option: 'config', value: $value })"
            placeholder="Select Config"
            size="small"
          >
            <el-option v-for="item in configList" :key="`config-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element label="Theme">
          <el-select
            class="select"
            :disabled="routeName === 'theme'"
            :value="preview.theme"
            @change="updatePreviewOption({ option: 'theme', value: $value })"
            placeholder="Select Theme"
            size="small"
          >
            <el-option v-for="item in themeList" :key="`theme-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element label="Templates">
          <el-select
            class="select"
            :disabled="routeName === 'template'"
            :value="preview.template"
            @change="updatePreviewOption({ option: 'template', value: $event })"
            placeholder="Select Template"
            size="small"
          >
            <el-option v-for="item in templateList" :key="`template-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>

        <form-element label="Sizes">
          <el-select
            class="select"
            :value="preview.size"
            @change="updatePreviewOption({ option: 'size', value: $event })"
            placeholder="Select Template"
            size="small"
          >
            <el-option v-for="item in sizes" :key="`size-${item}`" :label="item" :value="item"> </el-option>
          </el-select>
        </form-element>
      </div>
      <player
        ref="player"
        :config="config"
        :template="template"
        :theme="theme"
        :size="preview.size"
        :key="configHash"
        @ready="connectPlayerStore"
      />
    </card>
  </div>
</template>

<script>
import Player from "./Player";
import { get } from "lodash";
import { mapGetters, mapActions } from "vuex";
import hash from "object-hash";
import { setTheme } from "@podlove/player-actions/theme";
import Card from "./Card";
import FormElement from "./FormElement";

export default {
  components: {
    Player,
    Card,
    FormElement
  },

  data() {
    return {
      store: {},
      sizes: ["mobile", "tablet", "desktop"]
    };
  },

  computed: {
    ...mapGetters([
      "routeName",
      "configList",
      "themeList",
      "templateList",
      "preview",
      "routeName",
      "routeId",
      "configs",
      "templates",
      "themes"
    ]),
    config() {
      return get(this.configs, this.preview.config, {});
    },
    template() {
      return get(this.templates, this.preview.template, "");
    },
    theme() {
      return get(this.themes, this.preview.theme, {});
    },
    configHash() {
      return hash({ ...this.config, template: this.template, size: this.preview.size });
    }
  },

  watch: {
    routeName() {
      this.updatePreviewOption({ option: this.routeName, value: this.routeId });
    },
    routeId() {
      this.updatePreviewOption({ option: this.routeName, value: this.routeId });
    },
    theme: {
      handler(val) {
        this.updateTheme(val);
      },
      deep: true
    }
  },

  mounted() {
    this.updatePreviewOption({ option: this.routeName, value: this.routeId });
  },

  methods: {
    ...mapActions(["updatePreviewOption"]),

    connectPlayerStore(store) {
      this.store = store;
    },

    updateTheme(theme) {
      if (!this.store.dispatch) {
        return;
      }

      this.store.dispatch(
        setTheme({
          version: 5,
          theme
        })
      );
    }
  }
};
</script>

<style lang="scss" scoped>
.preview-options {
  display: flex;
  margin-bottom: 2em;
}
</style>

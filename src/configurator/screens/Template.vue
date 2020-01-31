<template>
  <div class="template">
    <card title="Markup" class="fit">
      <prism-editor :code="current" language="html" :lineNumbers="true" @change="updateTemplate"></prism-editor>
    </card>
  </div>
</template>

<script>
import "prismjs";
import PrismEditor from 'vue-prism-editor'
import { get } from 'lodash';
import { mapGetters, mapActions } from 'vuex'
import { Card } from '../components';

export default {
  computed: mapGetters('templates', ['current']),

  components: {
    PrismEditor,
    Card
  },

  methods: mapActions('templates', ['updateTemplate'])
}
</script>

<style lang="scss">
  @import "~prismjs/themes/prism.css";
  @import "~vue-prism-editor/dist/VuePrismEditor.css";

  .fit {
    max-width: 1000px;
    .el-card__body {
      padding: 0;
    }
  }
</style>

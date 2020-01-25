<template>
  <div class="player">
    <div ref="player" v-html="template" :style="{ width: previewWidth }"></div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  props: {
    config: {
      type: Object,
      default: () => {}
    },
    theme: {
      type: Object,
      default: () => {}
    },
    template: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'desktop'
    }
  },

  data() {
    return {
      width: {
        mobile: 350,
        tablet: 600,
        desktop: 950,
      }
    }
  },

  computed: {
    ...mapGetters(['episode', 'source']),

    previewWidth() {
      const width = this.width[this.size];

      return `${width}px`
    }
  },

  mounted() {
    const player = document.createElement('script')
    player.setAttribute('src', this.source)
    document.head.appendChild(player)

    player.addEventListener('load', this.bootstrap.bind(this))
  },

  methods: {
    bootstrap() {
      window.podlovePlayer(this.$refs.player, this.episode, {
        ...this.config,
        theme: this.theme,
        base: '/wp-content/plugins/podlove-web-player-beta/web-player/',
        version: 5
      }).then(store => {
        this.$emit('ready', store)
      })
    }
  },

}
</script>

<style lang="scss" scoped>
  .player {
    overflow: auto;
  }
</style>

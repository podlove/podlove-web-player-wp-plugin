<template>
  <div class="overflow-auto">
    <div ref="player" v-html="template" :style="{ width: previewWidth }"></div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { episode } from '../assets'

export default {
  props: {
    config: {
      type: Object,
      default: () => {},
    },
    theme: {
      type: Object,
      default: () => {},
    },
    template: {
      type: String,
      default: '',
    },
    size: {
      type: Number,
      default: 950,
    },
  },

  data() {
    return {
      episode: episode({ title: 'Five hours?', duration: '01:30:32' }),
      playlist: [
        {
          title: 'Five hours?',
          active: true,
          duration: '01:30:32',
          config: episode({ title: 'Five hours?', duration: '01:30:32' }),
        },
        {
          title: 'These old Doomsday',
          duration: '02:10:45',
          config: episode({ title: 'These old Doomsday', duration: '02:10:45' }),
        },
                {
          title: 'Blame the wizards!',
          duration: '00:45:13',
          config: episode({ title: 'Blame the wizards!', duration: '00:45:13' }),
        },
                {
          title: 'Ah, computer dating',
          duration: '00:55:10',
          config: episode({ title: 'Ah, computer dating', duration: '00:55:10' }),
        },
      ],
    }
  },

  computed: {
    ...mapGetters('settings', ['source']),

    previewWidth() {
      return `${this.size}px`
    },
  },

  mounted() {
    const player = document.createElement('script')
    player.setAttribute('src', this.source + 'embed.js')
    document.head.appendChild(player)

    player.addEventListener('load', this.bootstrap.bind(this))
  },

  methods: {
    bootstrap() {
      window
        .podlovePlayer(this.$refs.player, this.episode, {
          ...this.config,
          theme: this.theme,
          base: window.PODLOVE_WEB_PLAYER.base + '/web-player/',
          version: 5,
          playlist: this.playlist,
        })
        .then(store => {
          this.$emit('ready', store)
        })
    },
  },
}
</script>

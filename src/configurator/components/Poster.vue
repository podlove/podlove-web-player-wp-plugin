<template>
  <el-input :value="poster" @change="value => setShow({ poster: value })"></el-input>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { get } from 'lodash/fp'
import { head } from 'lodash'

export default {
  computed: mapState({
    poster: get('config.show.poster')
  }),
  methods: mapActions(['setShow'])
}
</script>

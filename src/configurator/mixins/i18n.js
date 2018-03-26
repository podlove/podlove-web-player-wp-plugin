import { get } from 'lodash'

export default {
  methods: {
    $i18n (id) {
      return get(window.PODLOVE, ['i18n', id], id)
    }
  }
}

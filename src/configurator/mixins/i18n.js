import { get, template } from 'lodash'

export default {
  methods: {
    $i18n (id, params = {}) {
      const translation = template(get(window.PODLOVE_WEB_PLAYER, ['i18n'].concat(id), ''))

      return translation(params)
    }
  }
}

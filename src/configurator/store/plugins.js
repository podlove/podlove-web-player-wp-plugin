import { noop } from 'lodash'

export const webplayer = store => {
  let playerStore = {
    dispatch: noop
  }

  store.subscribe(({ type, payload }) => {
    const config = {
      show: {
        ...store.getters.meta.show,
        ...store.getters.config.show
      },
      poster: store.getters.config.poster,
      audio: store.getters.meta.audio,
      ...store.getters.meta.episode,
      chapters: store.getters.meta.chapters,
      visibleComponents: store.getters.visibleComponents,
      theme: store.getters.theme,
      tabs: store.getters.tabs
    }

    switch (type) {
      case 'loaded':
        window.podlovePlayer('#player', config).then(store => {
          playerStore = store
        })
        break
      case 'setTheme':
        playerStore.dispatch({ type: 'SET_THEME', payload: config.theme })
        break
      case 'simulateTheme':
        playerStore.dispatch({ type: 'SET_THEME', payload })
        break
      case 'setComponent':
      case 'setActiveTab':
      case 'setShow':
        playerStore.dispatch({ type: 'INIT', payload: config })
        break
    }
  })
}

export default [
  webplayer
]

import { get } from 'lodash'
import { type, loadScript } from './utils'
import logo from './logo'

const { useState, useEffect, useCallback } = wp.element
const { Notice } = wp.components
const { __ } = wp.i18n

const clearPlayer = (node) => {
  node.querySelectorAll('iframe').forEach((iframe) => {
    if (iframe.iFrameResizer) {
      iframe.iFrameResizer.removeListeners()
    }
  })
  node.innerHTML = ''
}

export default function Player({ attributes }) {
  const [node, setNode] = useState(null)
  const [plugin, setPlugin] = useState(null)
  const [error, setError] = useState(false)
  const playerRef = useCallback((element) => setNode(element), [])

  // Localized API configuration belongs to the admin window, not the canvas.
  const settings = window.PODLOVE_WEB_PLAYER

  useEffect(() => {
    const controller = new AbortController()
    fetch(settings.api.bootstrap, {
      headers: { 'X-WP-Nonce': settings.nonce },
      signal: controller.signal,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Could not load player configuration')
        }
        return response.json()
      })
      .then(setPlugin)
      .catch((error) => {
        if (error.name !== 'AbortError') {
          setError(true)
        }
      })
    return () => controller.abort()
  }, [settings])

  useEffect(() => {
    if (!node || !plugin) {
      return
    }

    let cancelled = false
    let container = null
    setError(false)
    const timer = setTimeout(async () => {
      try {
        const base = get(get(plugin, 'settings.source.items'), get(plugin, 'settings.source.selected'))
        const { ownerDocument } = node
        await loadScript(base + 'embed.js', 'podlovePlayer', ownerDocument)
        if (cancelled) {
          return
        }

        const routes = settings.embed
        const config = [routes.config, attributes.config, 'theme', attributes.theme].join('/')
        let episode
        switch (type(attributes)) {
          case 'post':
            episode = [routes.post, attributes.post].join('/')
            break
          case 'publisher':
            episode = [routes.publisher, attributes.publisher].join('/')
            break
          case 'custom':
            episode = attributes.episode
            break
        }

        container = ownerDocument.createElement('div')
        node.appendChild(container)
        container.innerHTML = get(plugin, ['templates', attributes.template], '')
        await ownerDocument.defaultView.podlovePlayer(container, episode, config)
        if (cancelled) {
          clearPlayer(container)
          container.remove()
        }
      } catch (error) {
        if (!cancelled) {
          setError(true)
        }
      }
    }, 600)

    return () => {
      cancelled = true
      clearTimeout(timer)
      if (container) {
        clearPlayer(container)
        container.remove()
      }
    }
  }, [node, plugin, attributes, settings])

  return (
    <div>
      {error && (
        <Notice status="error" isDismissible={false}>
          {__('The player preview could not be loaded.', 'podlove-web-player')}
        </Notice>
      )}
      <div className="podlove-web-player--preview">
        <div className="logo">
          {logo(50)}
          <span className="title">{__('Podlove Web Player', 'podlove-web-player')}</span>
        </div>
        <div className="player" ref={playerRef}></div>
      </div>
    </div>
  )
}

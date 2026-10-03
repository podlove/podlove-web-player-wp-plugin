import { isUndefined } from 'lodash'

// Each editor document needs its own copy of the DOM-dependent embed library.
const scripts = new WeakMap()

export const loadScript = (src, prop, ownerDocument) => {
  const view = ownerDocument.defaultView
  if (prop && view[prop]) {
    return Promise.resolve()
  }

  let pending = scripts.get(ownerDocument)
  if (!pending) {
    pending = new Map()
    scripts.set(ownerDocument, pending)
  }
  if (pending.has(src)) {
    return pending.get(src)
  }

  const promise = new Promise((resolve, reject) => {
    const script = ownerDocument.createElement('script')
    script.src = src
    script.onload = () => {
      if (!prop || view[prop]) {
        resolve()
      } else {
        pending.delete(src)
        script.remove()
        reject(new Error('Player script did not initialize'))
      }
    }
    script.onerror = () => {
      pending.delete(src)
      script.remove()
      reject(new Error('Could not load player script'))
    }
    ownerDocument.head.appendChild(script)
  })
  pending.set(src, promise)
  return promise
}

export const type = (attributes = {}) => {
  if (!isUndefined(attributes.post)) {
    return 'post'
  }

  if (!isUndefined(attributes.publisher)) {
    return 'publisher'
  }

  if (!isUndefined(attributes.episode)) {
    return 'custom'
  }

  return null
}

export const previewEpisode = (attributes, routes) => {
  const source = type(attributes)
  if (source === 'custom') {
    return attributes.episode
  }

  if (source === 'post' || source === 'publisher') {
    const id = attributes[source]
    if (/^\d+$/.test(id) && Number(id) > 0) {
      return [routes[source], id].join('/')
    }
  }

  return null
}

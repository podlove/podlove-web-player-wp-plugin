import { isUndefined } from 'lodash'

export const loadScript = (src, prop) =>
  new Promise((resolve, reject) => {
    // Function already loaded
    if (prop && window[prop]) {
      return resolve()
    }

    const script = document.createElement('script')
    script.src = src

    // if a global property is provided
    if (prop) {
      Object.defineProperty(window, prop, {
        configurable: true,
        set(v) {
          Object.defineProperty(window, prop, {
            configurable: true,
            enumerable: true,
            writable: true,
            value: v,
          })

          resolve()
        }
      })
    } else {
      script.onload = resolve
    }

    script.onerror = reject
    document.head.appendChild(script)
  })

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

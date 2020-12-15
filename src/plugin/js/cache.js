;(function() {
  window.podlovePlayerCache = {
    cache: {},
    add: () => {},
  }

  window.podlovePlayerCache.add = (responses = []) => {
    ;[].concat(responses).filter(Boolean).forEach(({ url, data }) => {
      window.podlovePlayerCache.cache[url] = data
    })
  }

  // XMLHTTPRequest Interceptor
  const xhrSendHandler = window.XMLHttpRequest.prototype.send
  const xhrOpenHandler = window.XMLHttpRequest.prototype.open

  window.XMLHttpRequest.prototype.open = function(_, url) {
    this.url = url
    xhrOpenHandler.apply(this, arguments)
  }

  window.XMLHttpRequest.prototype.send = function() {
    const cachedResponse = window.podlovePlayerCache.cache[this.url]

    if (typeof cachedResponse === 'undefined') {
      return xhrSendHandler.apply(this, arguments)
    }

    Object.defineProperty(this, 'responseText', {
      get: () => JSON.stringify(cachedResponse),
      configurable: true,
    })

    Object.defineProperty(this, 'status', {
      get: () => 200,
      configurable: true,
    })

    this.getAllResponseHeaders = () => 'Content-Type: application/json'

    return this.dispatchEvent(new CustomEvent('load'))
  }
})()

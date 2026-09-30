const assert = require('node:assert/strict')
const { test } = require('node:test')
const { readFileSync } = require('node:fs')
const { resolve } = require('node:path')
const { transformSync } = require('@babel/core')

const source = readFileSync(resolve(__dirname, '../../src/block/components/utils.js'), 'utf8')
const { code } = transformSync(source, {
  babelrc: false,
  configFile: false,
  plugins: ['@babel/plugin-transform-modules-commonjs'],
})
const moduleResult = { exports: {} }
new Function('require', 'module', 'exports', code)(require, moduleResult, moduleResult.exports)
const { loadScript } = moduleResult.exports

function documentFixture() {
  const nodes = []
  return {
    nodes,
    defaultView: {},
    createElement: () => ({
      remove() {
        this.removed = true
      },
    }),
    head: { appendChild: (script) => nodes.push(script) },
  }
}

test('concurrent blocks share one pending script in the same document', async () => {
  const doc = documentFixture()
  const first = loadScript('/embed.js', 'podlovePlayer', doc)
  const second = loadScript('/embed.js', 'podlovePlayer', doc)
  assert.equal(first, second)
  assert.equal(doc.nodes.length, 1)
  doc.defaultView.podlovePlayer = () => {}
  doc.nodes[0].onload()
  await Promise.all([first, second])
  await loadScript('/embed.js', 'podlovePlayer', doc)
  assert.equal(doc.nodes.length, 1)
})

test('each iframe gets its own player library', async () => {
  const parent = documentFixture()
  const canvas = documentFixture()
  parent.defaultView.podlovePlayer = () => {}
  await loadScript('/embed.js', 'podlovePlayer', parent)
  const pending = loadScript('/embed.js', 'podlovePlayer', canvas)
  assert.equal(parent.nodes.length, 0)
  assert.equal(canvas.nodes.length, 1)
  canvas.defaultView.podlovePlayer = () => {}
  canvas.nodes[0].onload()
  await pending
})

test('failed loads can be retried', async () => {
  const doc = documentFixture()
  const failed = loadScript('/embed.js', 'podlovePlayer', doc)
  doc.nodes[0].onerror()
  await assert.rejects(failed, /Could not load/)
  assert.equal(doc.nodes[0].removed, true)
  const retry = loadScript('/embed.js', 'podlovePlayer', doc)
  assert.equal(doc.nodes.length, 2)
  doc.defaultView.podlovePlayer = () => {}
  doc.nodes[1].onload()
  await retry
})

test('a script without the expected global rejects and allows retry', async () => {
  const doc = documentFixture()
  const failed = loadScript('/embed.js', 'podlovePlayer', doc)
  doc.nodes[0].onload()
  await assert.rejects(failed, /did not initialize/)
  const retry = loadScript('/embed.js', 'podlovePlayer', doc)
  doc.defaultView.podlovePlayer = () => {}
  doc.nodes[1].onload()
  await retry
})

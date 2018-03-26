export const mergeState = (state, changeSet) => {
  Object.keys(changeSet).forEach(key => {
    state[key] = changeSet[key]
  })
}

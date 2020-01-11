/* global PODLOVE */

import Vue from "vue";
import { get, pick } from "lodash";
import { Loading, Notification } from "element-ui";

export default {
  boot({ commit }) {
    const initializing = Loading.service({ fullscreen: true, text: PODLOVE.i18n.message_initializing });

    Vue.http
      .get(PODLOVE.api)
      .then(({ data }) => commit("setState", data))
      .catch(async err => {
        const error = err.json ? await err.json() : {};
        console.warn(err);

        Notification.error({
          offset: 30,
          title: PODLOVE.i18n.error_load_config,
          message: get(error, "message")
        });
      })
      .finally(() => {
        initializing.close();
        commit("loaded");
      });
  },

  save({ getters }) {
    Vue.http
      .post(PODLOVE.api, { visibleComponents, tabs, theme, enclosure, show })
      .catch(err => {
        err.json().then(result => {
          Notification.error({
            offset: 30,
            title: PODLOVE.i18n.error_save_config,
            message: get(result, "message", err)
          });
        });
      })
      .finally(() => saving.close());
  },

  // Config
  updateChannels({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("updateChannels", { id: getters.routeId, channels: payload });
  },

  removeChannel({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }

    const channels = get(getters.configs, [getters.routeId, "share", "channels"], []).filter(
      channel => channel !== payload
    );

    commit("updateChannels", { id: getters.routeId, channels });
  },

  addChannel({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }

    const channels = get(getters.configs, [getters.routeId, "share", "channels"], []);

    commit("updateChannels", { id: getters.routeId, channels: [payload, ...channels] });
  },

  updateSharePlaytime({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("updateSharePlaytime", { id: getters.routeId, sharePlaytime: payload });
  },

  updateEmbedPlayer({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("updateEmbedPlayer", { id: getters.routeId, embed: payload });
  },

  addClient({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }

    const clients = get(getters.configs, [getters.routeId, "subscribe-button", "clients"], []);

    commit("updateClients", { id: getters.routeId, clients: [pick(payload, ["id", "service"]), ...clients] });
  },

  removeClient({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }
    const stagedClient = getters.stagedClient;
    const clients = get(getters.configs, [getters.routeId, "subscribe-button", "clients"], []).filter(
      client => client.id !== payload.id
    );
    commit("updateClients", { id: getters.routeId, clients });

    if (stagedClient.id === payload.id) {
      commit("stageClient", { id: null });
    }
  },

  updateClients({ getters, commit }, payload = []) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("updateClients", { id: getters.routeId, clients: payload.map(client => pick(client, ["id", "service"])) });
  },

  updateClientService({ getters, commit }, payload) {
    if (getters.routeName !== "config") {
      return;
    }
    const stagedClient = getters.stagedClient;
    const updatedClient = {
      ...stagedClient,
      service: payload
    };
    commit("stageClient", updatedClient);

    const clients = get(getters.configs, [getters.routeId, "subscribe-button", "clients"], []).map(client =>
      client.id === updatedClient.id ? updatedClient : client
    );

    commit("updateClients", { id: getters.routeId, clients: clients.map(client => pick(client, ["id", "service"])) });
  },

  stageClient({ getters, commit }, client) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("stageClient", client);
  },

  updateFeed({ getters, commit }, feed) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("updateFeed", { id: getters.routeId, feed });
  },

  selectActiveTab({ getters, commit }, tab) {
    if (getters.routeName !== "config") {
      return;
    }

    commit("setActiveTab", { id: getters.routeId, tab });
  },

  // Theme
  updateToken({ getters, commit }, { token, color }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const tokens = get(getters.themes, [getters.routeId, "tokens"], {});

    commit("updateTokens", {
      id: getters.routeId,
      tokens: {
        ...tokens,
        [token]: color
      }
    });
  },

  selectFont({ getters, commit }, font) {
    if (getters.routeName !== "theme") {
      return;
    }

    commit("selectFont", { font });
  },

  stageFontSource({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')

    commit("stageFontSource", {
      font,
      value
    })
  },

  updateFontSource({ getters, commit }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')
    const payload = get(getters.fonts, [font, 'src'])

    if (!payload) {
      return;
    }

    commit("setFontSourceError", { font, value: payload })

    const error = get(getters.fonts, [font, 'error'])

    if (error) {
      return;
    }

    const sources = get(getters.themes, [getters.routeId, 'fonts', font, 'src'], [])

    if (sources.includes(payload)) {
      return;
    }

    commit("updateFontSource", { id: getters.routeId, font, value: [payload, ...sources] })
  },

  removeFontSrc({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')

    const sources = get(getters.themes, [getters.routeId, 'fonts', font, 'src'], []).filter(src => src !== value)

    commit("updateFontSource", { id: getters.routeId, font, value: sources })
  },

  updateFontWeight({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')

    commit("updateFontWeight", { id: getters.routeId, font, value })
  },

  addFontFamily({ getters, commit }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')
    const payload = get(getters.fonts, [font, 'family'])

    if (!payload) {
      return;
    }

    const family = get(getters.themes, [getters.routeId, 'fonts', font, 'family'], [])

    if (family.includes(payload)) {
      return;
    }

    commit("updateFontFamily", { id: getters.routeId, font, value: [payload, ...family] })
  },

  stageFontFamily({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')

    commit("stageFontFamily", {
      font,
      value
    })
  },

  updateFontFamily({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')

    commit("updateFontFamily", { id: getters.routeId, font, value })
  },

  removeFontFamily({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ['selected'], 'ci')
    const family = get(getters.themes, [getters.routeId, 'fonts', font, 'family'], []).filter(item => item !== value)

    commit("updateFontFamily", { id: getters.routeId, font, value: family })
  },

  // Preview
  updatePreviewOption({ commit }, { option, value }) {
    commit("setPreviewOption", { option, value });
  }
};

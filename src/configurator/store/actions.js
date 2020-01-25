/* global PODLOVE */
import { get, pick } from "lodash";
import { request } from "../lib";
import router from '../router'

export default {
  boot({ commit }) {
    request
      .read(PODLOVE.api.bootstrap, {
        loading: PODLOVE.i18n.message_initializing,
        error: PODLOVE.i18n.error_load_config
      })
      .then(data => commit("setState", data))
      .finally(() => commit("loaded"));
  },

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

    const font = get(getters.fonts, ["selected"], "ci");

    commit("stageFontSource", {
      font,
      value
    });
  },

  updateFontSource({ getters, commit }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");
    const payload = get(getters.fonts, [font, "src"]);

    if (!payload) {
      return;
    }

    commit("setFontSourceError", { font, value: payload });

    const error = get(getters.fonts, [font, "error"]);

    if (error) {
      return;
    }

    const sources = get(getters.themes, [getters.routeId, "fonts", font, "src"], []);

    if (sources.includes(payload)) {
      return;
    }

    commit("updateFontSource", { id: getters.routeId, font, value: [payload, ...sources] });
  },

  removeFontSrc({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");

    const sources = get(getters.themes, [getters.routeId, "fonts", font, "src"], []).filter(src => src !== value);

    commit("updateFontSource", { id: getters.routeId, font, value: sources });
  },

  updateFontWeight({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");

    commit("updateFontWeight", { id: getters.routeId, font, value });
  },

  addFontFamily({ getters, commit }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");
    const payload = get(getters.fonts, [font, "family"]);

    if (!payload) {
      return;
    }

    const family = get(getters.themes, [getters.routeId, "fonts", font, "family"], []);

    if (family.includes(payload)) {
      return;
    }

    commit("updateFontFamily", { id: getters.routeId, font, value: [payload, ...family] });
  },

  stageFontFamily({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");

    commit("stageFontFamily", {
      font,
      value
    });
  },

  updateFontFamily({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");

    commit("updateFontFamily", { id: getters.routeId, font, value });
  },

  removeFontFamily({ getters, commit }, { value }) {
    if (getters.routeName !== "theme") {
      return;
    }

    const font = get(getters.fonts, ["selected"], "ci");
    const family = get(getters.themes, [getters.routeId, "fonts", font, "family"], []).filter(item => item !== value);

    commit("updateFontFamily", { id: getters.routeId, font, value: family });
  },

  // Template
  updateTemplate({ getters, commit }, value) {
    if (getters.routeName !== "template") {
      return;
    }

    commit("updateTemplate", { id: getters.routeId, value });
  },

  // Preview
  updatePreviewOption({ commit }, { option, value }) {
    commit("setPreviewOption", { option, value });
  },

  // Create Modal
  updateCreateModalValue({ commit }, value) {
    commit("updateModalValue", { value });
  },

  closeModal({ commit }) {
    commit("updateModalVisibility", { value: false, type: null, target: null });
  },

  showCreateModal({ commit }, target) {
    commit("updateModalVisibility", { value: true, target, type: 'create' });
  },

  confirmCreateModal({ commit, getters }) {
    const target = getters.modal.target;
    const id = getters.modal.value;

    switch (target) {
      case "config": {
        const copy = get(getters.configs, "default", {});

        request
          .create(`${PODLOVE.api.config}/${id}`, copy, {
            loading: PODLOVE.i18n.message_creating,
            error: PODLOVE.i18n.error_save_config
          })
          .catch(console.warn)
          .then(config => {
            commit("updateConfig", { id, config });
            commit("updateModalVisibility", { value: false, type: null, target: null });
            router.push({ name: 'config', params: { id } })
          })

        break
      }

      case "theme": {
        const copy = get(getters.themes, "default", {});

        request
          .create(`${PODLOVE.api.theme}/${id}`, copy, {
            loading: PODLOVE.i18n.message_creating,
            error: PODLOVE.i18n.error_save_theme
          })
          .catch(console.warn)
          .then(theme => {
            commit("updateTheme", { id, theme });
            commit("updateModalVisibility", { value: false, type: null, target: null });
            router.push({ name: 'theme', params: { id } })
          })

          break
      }

      case "template": {
        const template = get(getters.templates, "default", {});

        request
          .create(`${PODLOVE.api.template}/${id}`, { template }, {
            loading: PODLOVE.i18n.message_creating,
            error: PODLOVE.i18n.error_save_template
          })
          .catch(console.warn)
          .then(template => {
            commit("updateTemplate", { id, template });
            commit("updateModalVisibility", { value: false, type: null, target: null });
            router.push({ name: 'template', params: { id } })
          })

        break
      }
    }
  },

  // Delete Modal
  showDeleteModal({ commit }, { target, id }) {
    commit("updateModalVisibility", { value: true, target, type: 'delete', id });
  },


  // Save Handling
  save({ commit, getters }) {
    const id = getters.routeId;
    const type = getters.routeName;

    switch (type) {
      case "config": {
        const payload = get(getters.configs, id, {});

        request
          .create(`${PODLOVE.api.config}/${id}`, payload, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_save_config
          })
          .catch(console.warn)
          .then(config => {
            commit("updateConfig", { id, config });
          })

          break
      }

      case "theme": {
        const payload = get(getters.themes, id, {});

        request
          .create(`${PODLOVE.api.theme}/${id}`, payload, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_save_theme
          })
          .catch(console.warn)
          .then(theme => {
            commit("updateTheme", { id, theme });
          })

        break
      }

      case "template": {
        const payload = get(getters.templates, id, {});

        request
          .create(`${PODLOVE.api.template}/${id}`, payload, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_save_template
          })
          .catch(console.warn)
          .then(template => {
            commit("updateTemplate", { id, template });
          })

          break
      }
    }
  },

  // Delete
  remove({ commit }, { type, id }) {
    switch (type) {
      case "config": {
        request
          .remove(`${PODLOVE.api.template}/${id}`, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_delete_config
          })
          .catch(console.warn)
          .then(() => {
            commit("removeConfig", { id });
            commit("updateModalVisibility", { value: false, type: null, target: null });
            router.push({ name: 'config', params: { id: 'default' } })
          })
        break
      }
      case "theme": {
        request
          .remove(`${PODLOVE.api.theme}/${id}`, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_delete_theme
          })
          .catch(console.warn)
          .then(() => {
            commit("removeTheme", { id });
            commit("updateModalVisibility", { value: false, type: null, target: null });
            router.push({ name: 'theme', params: { id: 'default' } })
          })
        break
      }
      case "template": {
        request
          .remove(`${PODLOVE.api.template}/${id}`, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_delete_template
          })
          .catch(console.warn)
          .then(() => {
            commit("removeTemplate", { id });
            commit("updateModalVisibility", { value: false, type: null, target: null });
            router.push({ name: 'template', params: { id: 'default' } })
          })
        break
      }
    }
  }
};

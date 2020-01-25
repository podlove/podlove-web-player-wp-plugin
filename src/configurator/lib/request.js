/* global PODLOVE */

import Vue from "vue";
import { get } from "lodash";
import { Loading, Notification } from "element-ui";

const loading = text => Loading.service({ fullscreen: true, text });

export const read = (url, messages) => {
  const initializing = loading(messages.loading);

  return Vue.http
    .get(url)
    .then(({ data }) => data)
    .catch(async err => {
      const error = err.json ? await err.json() : {};
      const message = prop(error, "message")
      console.warn(err);

      Notification.error({
        offset: 30,
        title: messages.error,
        message
      });

      throw new Error(message)
    })
    .finally(() => initializing.close());
};

export const create = (url, payload, messages) => {
  const saving = loading(messages.loading);

  return Vue.http
    .post(url, payload)
    .then(({ data }) => data)
    .catch(async err => {
      const error = err.json ? await err.json() : {};
      const message = get(error, "message")

      Notification.error({
        offset: 30,
        title: messages.error,
        message
      });

      throw new Error(message)
    })
    .finally(() => saving.close());
};

export const remove = (url, messages) => {
  const removing = loading(messages.loading);

  return Vue.http
    .delete(url)
    .then(({ data }) => data)
    .catch(async err => {
      const error = err.json ? await err.json() : {};
      const message = get(error, "message")

      Notification.error({
        offset: 30,
        title: messages.error,
        message
      });

      throw new Error(message)
    })
    .finally(() => removing.close());
}

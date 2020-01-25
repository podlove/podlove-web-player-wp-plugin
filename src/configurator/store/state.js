import { get } from "lodash";
import podcastClients from "@podlove/clients";
import poster from "../assets/cover.png";

const episode = {
  show: {
    title: "Futurama",
    subtitle: `Intergalactic conspiracies and other strange stuff`,
    summary: `Hatched from Matt Groening's brain, Futurama follows pizza guy Philip J. Fry, who reawakens in 31st century New New York after a cryonics lab accident.`,
    poster,
    url: "http://fillerama.io/"
  },

  episode: {
    title: "Five hours?",
    subtitle: `Why am I sticky and naked? Did I miss something fun? In your time, yes, but nowadays shut up! Besides, these are adult stemcells, harvested from perfectly healthy adults whom I killed for their stemcells.`,
    summary: `Ah, computer dating. It's like pimping, but you rarely have to use the phrase "upside your head." Who am I making this out to? Okay, I like a challenge. As an interesting side note, as a head without a body, I envy the dead.`,
    publicationDate: "2016-02-11T03:13:55+00:00",
    duration: "01:30:32"
  },

  contributors: [
    {
      avatar: "static/example/fry.jpg",
      name: "Philip J. Fry"
    },
    {
      avatar: "static/example/farnsworth.png",
      name: "Professor Farnsworth"
    },
    {
      avatar: "static/example/leela.jpg",
      name: "Turanga Leela"
    }
  ],

  chapters: [
    { start: "00:00:00", title: "With gusto." },
    { start: "00:01:39", title: "Good news" },
    { start: "00:04:58", title: "You stole the atom" },
    { start: "00:18:37", title: `Oh, I don't have time for this` },
    { start: "00:33:40", title: "Her company is big and evil!" },
    { start: "00:35:37", title: "Have you ever tried just turning off the TV" },
    { start: "01:17:26", title: "Hello, little man" },
    { start: "01:24:55", title: "Take me to your leader!" }
  ],

  audio: [
    {
      url: "http://techslides.com/demos/samples/sample.m4a",
      mimeType: "audio/mp4",
      size: 93260000,
      title: "Audio MP4"
    },
    {
      url: "http://techslides.com/demos/samples/sample.mp3",
      mimeType: "audio/mp3",
      size: 14665000,
      title: "Audio MP3"
    },
    {
      url: "http://techslides.com/demos/samples/sample.ogg",
      mimeType: "audio/ogg",
      size: 94400000,
      title: "Audio Ogg"
    }
  ]
};

const channels = ["facebook", "twitter", "whats-app", "linkedin", "pinterest", "xing", "mail", "link"];

const configs = {};

const stagedClient = {
  id: null
};

const templates = {};

const themes = {};

const tabs = [null, "shownotes", "chapters", "transcripts", "share", "files", "playlist"];

const clients = Object.values(
  podcastClients().reduce((result, item) => {
    const existing = get(result, item.id, {});

    return {
      ...result,
      [item.id]: {
        id: item.id,
        icon: item.icon,
        platforms: [...(existing.platforms ? existing.platforms : []), item.platform],
        title: item.title,
        serviceScheme: existing.serviceScheme || item.type === "service" ? item.scheme : null,
        service: null
      }
    };
  }, {})
);

const preview = {
  config: "default",
  theme: "default",
  template: "default",
  size: "desktop"
};

const fonts = {
  selected: "ci",
  ci: {
    src: null,
    family: null,
    error: null
  },
  regular: {
    src: null,
    family: null,
    error: null
  },
  bold: {
    src: null,
    family: null,
    error: null
  }
};

const modal = {
  visible: false,
  target: null,
  value: null,
  error: null,
  id: null
};

const settings = {
  source: '/wp-content/plugins/podlove-web-player-beta/web-player/embed.js'
}

export default {
  episode,
  settings,
  configs,
  templates,
  themes,
  channels,
  clients,
  stagedClient,
  tabs,
  loaded: false,
  modal,
  preview,
  fonts,
  settings
};

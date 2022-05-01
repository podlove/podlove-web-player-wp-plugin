<template>
  <div class="config">
    <card :title="$i18n(['config', 'active-tab'])">
      <form-element>
        <el-select
          :placeholder="$i18n(['config', 'select-tab'])"
          :value="activeTab"
          size="small"
          @change="selectActiveTab"
        >
          <el-option
            v-for="item in tabs"
            :key="`tab-${item}`"
            :label="item === null ? $i18n(['config', 'no-default-tab']) : item"
            :value="item"
          ></el-option>
        </el-select>
      </form-element>
    </card>

    <card :title="$i18n(['config', 'share-tab'])">
      <div class="flex">
        <div class="row">
          <form-element :label="$i18n(['config', 'channels'])">
            <el-select
              :placeholder="$i18n(['config', 'channels-add'])"
              value=""
              size="small"
              @change="addChannel"
              :disabled="availableChannels.length === 0"
            >
              <el-option
                v-for="item in availableChannels"
                :key="`channel-${item}`"
                :label="item"
                :value="item"
              ></el-option>
            </el-select>
          </form-element>

          <form-element>
            <draggable
              :list="selectedChannels"
              @change="updateChannels"
              @remove="removeChannel"
            />
          </form-element>
        </div>
        <div class="row">
          <form-element :label="$i18n(['config', 'share-options'])">
            <el-switch
              :value="sharePlaytime"
              :active-text="$i18n(['config', 'share-playtime'])"
              @change="updateSharePlaytime"
            ></el-switch>
            <el-switch
              :value="embedPlayer"
              :active-text="$i18n(['config', 'embed-player'])"
              @change="updateEmbedPlayer"
            ></el-switch>
          </form-element>
        </div>
      </div>
    </card>

    <card :title="$i18n(['config', 'subscribe-button'])">
      <form-element :label="$i18n(['config', 'feed'])" :full="true">
        <el-input
          size="small"
          :placeholder="$i18n(['config', 'rss-feed'])"
          :value="feed"
          @input="updateFeed"
          clearable
        ></el-input>
      </form-element>

      <div class="flex">
        <form-element :label="$i18n(['config', 'clients'])">
          <el-select
            :placeholder="$i18n(['config', 'client-add'])"
            value=""
            :disabled="false"
            size="small"
            @change="addClient"
          >
            <el-option
              v-for="item in availableClients"
              :key="item.id"
              :label="item.title"
              :value="item"
            ></el-option>
          </el-select>
          <draggable
            :list="selectedClients"
            @change="updateClients"
            :selected="stagedClient.id"
            @click="stageClient"
            @remove="removeClient"
            :type="(element) => (element.serviceScheme ? 'success' : '')"
            :title="(element) => element.title"
            :id="(element) => element.id"
          />
        </form-element>
        <div v-if="stagedClient.id && !stagedClient.id.startsWith('custom')">
          <form-element
            :label="$i18n(['config', 'client-supported-plattforms'])"
            class="h-16"
          >
            <el-tag
              v-for="platform in stagedClient.platforms"
              :key="platform"
              size="small"
              type="info"
              class="mr-2"
              >{{ platform }}</el-tag
            >
          </form-element>

          <form-element>
            <el-tooltip
              v-if="stagedClient.tooltip"
              :content="stagedClient.tooltip"
              placement="top-start"
            >
              <h4 class="item-title">
                <span>{{ stagedClient.label }}</span
                ><i class="el-icon-info ml-1" />
              </h4>
            </el-tooltip>

            <h4 class="item-title mb-1" v-else>
              <span>{{ stagedClient.label }}</span>
            </h4>

            <el-input
              size="small"
              :placeholder="stagedClient.label"
              :value="stagedClient.service"
              @input="updateClientService"
              clearable
            >
            </el-input>
          </form-element>
        </div>
        <div v-if="stagedClient.id && stagedClient.id.startsWith('custom')">
          <form-element>
            <div class="mb-1">
              <h4 class="item-title mb-1">
                <span>{{ $i18n(['config', 'client-title']) }}</span>
              </h4>
              <el-input
                size="small"
                :placeholder="$i18n(['config', 'client-title'])"
                :value="stagedClient.title"
                @input="updateCustomClient({ title: $event })"
                clearable
              >
              </el-input>
            </div>
            <div class="mb-1">
              <h4 class="item-title mb-1">
                <span>{{ $i18n(['config', 'client-icon']) }}</span>
              </h4>
              <el-input
                size="small"
                :placeholder="$i18n(['config', 'client-icon'])"
                :value="stagedClient.icon"
                @input="updateCustomClient({ icon: $event })"
                clearable
              >
              </el-input>
            </div>
            <div class="mb-1">
              <h4 class="item-title mb-1">
                <span>{{ $i18n(['config', 'client-link']) }}</span>
              </h4>
              <el-input
                size="small"
                :placeholder="$i18n(['config', 'client-link'])"
                :value="stagedClient.link"
                @input="updateCustomClient({ link: $event })"
                clearable
              >
              </el-input>
            </div>
          </form-element>
        </div>
      </div>
    </card>

    <card :title="$i18n(['config', 'related-episodes', 'title'])">
      <form-element
        :label="$i18n(['config', 'related-episodes', 'source'])"
        :full="true"
      >
        <el-select
          :value="relatedEpisodes.source"
          :disabled="false"
          size="small"
          @change="updateSource"
        >
          <el-option
            value="disabled"
            :label="
              $i18n([
                'config',
                'related-episodes',
                'sources',
                'disabled',
                'title',
              ])
            "
          ></el-option>
          <el-option
            value="show"
            :label="
              $i18n(['config', 'related-episodes', 'sources', 'show', 'title'])
            "
          ></el-option>
          <el-option
            value="podcast"
            :label="
              $i18n([
                'config',
                'related-episodes',
                'sources',
                'podcast',
                'title',
              ])
            "
          ></el-option>
        </el-select>
      </form-element>
      <form-element
        v-if="relatedEpisodes.source === 'show'"
        :label="
          $i18n(['config', 'related-episodes', 'sources', 'show', 'label'])
        "
        :full="true"
      >
        <el-select
          :value="relatedEpisodes.value"
          :disabled="false"
          size="small"
          @change="updateSourceShow"
        >
          <el-option
            v-for="item in shows"
            :key="item.slug"
            :label="item.name"
            :value="item.slug"
          ></el-option>
        </el-select>
      </form-element>
      <div class="related-episodes-description">
        {{
          $i18n([
            'config',
            'related-episodes',
            'sources',
            relatedEpisodes.source,
            'description',
          ])
        }}
      </div>
    </card>
  </div>
</template>

<script>
import { get, uniq } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { Card, FormElement, Draggable } from '../components'

export default {
  components: {
    Draggable,
    Card,
    FormElement,
  },

  computed: {
    ...mapGetters('configs', [
      'current',
      'channels',
      'clients',
      'stagedClient',
      'tabs',
      'relatedEpisodes',
    ]),
    ...mapGetters('shows', ['shows']),

    selectedChannels() {
      return uniq(get(this.current, 'share.channels', []))
    },

    availableChannels() {
      return uniq(this.channels || []).filter(
        (channel) => !this.selectedChannels.includes(channel)
      )
    },

    sharePlaytime() {
      return get(this.current, 'share.sharePlaytime')
    },

    embedPlayer() {
      return !!get(this.current, 'share.outlet')
    },

    feed() {
      return get(this.current, ['subscribe-button', 'feed'])
    },

    selectedClients() {
      return get(this.current, ['subscribe-button', 'clients'], []).map(
        (item) => ({
          ...(this.clients || []).find((client) => client.id === item.id),
          ...item,
        })
      )
    },

    activeTab() {
      return get(this.current, 'activeTab')
    },

    availableClients() {
      return (this.clients || []).filter(
        (client) =>
          this.selectedClients.findIndex(
            (selected) => selected.id === client.id
          ) === -1
      )
    },
  },

  methods: {
    ...mapActions('configs', [
      'updateChannels',
      'removeChannel',
      'addChannel',
      'updateSharePlaytime',
      'updateEmbedPlayer',
      'addClient',
      'removeClient',
      'updateClients',
      'updateClientService',
      'stageClient',
      'updateFeed',
      'selectActiveTab',
      'updateSource',
      'updateSourceShow',
      'updateCustomClient',
    ]),
  },
}
</script>

<style lang="scss">
.related-episodes-description {
  font-style: italic;
  color: grey;
  padding: 0.25em;
}
</style>

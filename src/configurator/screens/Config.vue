<template>
  <div class="config">
    <card title="Active Tab">
      <form-element>
        <el-select placeholder="Select Tab" :value="activeTab" size="small" @change="selectActiveTab">
          <el-option v-for="item in tabs" :key="`tab-${item}`" :label="item" :value="item"></el-option>
        </el-select>
      </form-element>
    </card>

    <card title="Share Tab">
      <div class="flex">
        <div class="row">
          <form-element label="Channels">
            <el-select
              placeholder="Add"
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
            <draggable :list="selectedChannels" @change="updateChannels" @remove="removeChannel" />
          </form-element>
        </div>
        <div class="row">
          <form-element label="Options">
            <el-switch :value="sharePlaytime" active-text="Share Playtime" @change="updateSharePlaytime"></el-switch>
            <el-switch :value="embedPlayer" active-text="Embed Player" @change="updateEmbedPlayer"></el-switch>
          </form-element>
        </div>
      </div>
    </card>

    <card title="Subscribe Button">
      <form-element label="Feed" :full="true">
        <el-input size="small" placeholder="RSS Feed" :value="feed" @input="updateFeed" clearable></el-input>
      </form-element>

      <div class="flex">
        <form-element label="Clients">
          <el-select placeholder="Add" value="" :disabled="false" size="small" @change="addClient">
            <el-option v-for="item in availableClients" :key="item.id" :label="item.title" :value="item"></el-option>
          </el-select>
          <draggable
            :list="selectedClients"
            @change="updateClients"
            :selected="stagedClient.id"
            @click="stageClient"
            @remove="removeClient"
            :type="element => (element.serviceScheme ? 'success' : '')"
            :title="element => element.title"
            :id="element => element.id"
          />
        </form-element>
        <div v-if="stagedClient.id">
          <form-element label="Supported Platforms" class="h-16">
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
            <el-tooltip slot="label" :content="stagedClient.serviceScheme('[service-id]')" placement="top-start">
              <h4 class="item-title"><span>Service Id</span><i class="el-icon-info ml-1" /></h4>
            </el-tooltip>

            <el-input
              size="small"
              placeholder="Service Id"
              :value="stagedClient.service"
              @input="updateClientService"
              clearable
            >
            </el-input>
          </form-element>
        </div>
      </div>
    </card>
  </div>
</template>

<script>
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { Card, FormElement, Draggable } from '../components'

export default {
  components: {
    Draggable,
    Card,
    FormElement,
  },

  computed: {
    ...mapGetters('configs', ['current', 'channels', 'clients', 'stagedClient', 'tabs']),

    selectedChannels() {
      return get(this.current, 'share.channels', [])
    },

    availableChannels() {
      return (this.channels || []).filter(channel => !this.selectedChannels.includes(channel))
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
      return get(this.current, ['subscribe-button', 'clients'], []).map(item => ({
        ...(this.clients || []).find(client => client.id === item.id),
        ...item,
      }))
    },

    activeTab() {
      return get(this.current, 'activeTab')
    },

    availableClients() {
      return (this.clients || []).filter(
        client => this.selectedClients.findIndex(selected => selected.id === client.id) === -1
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
    ]),
  },
}
</script>

<style lang="scss"></style>

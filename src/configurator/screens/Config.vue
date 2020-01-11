<template>
  <div class="config">
    <card title="Active Tab">
      <form-element>
        <el-select
          placeholder="Select Tab"
          :value="activeTab"
          size="small"
          @change="selectActiveTab"
        >
          <el-option v-for="item in tabs" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </form-element>
    </card>

    <card title="Share Tab">
      <div class="column">
        <div class="row">
          <form-element label="Channels">
            <el-select
                placeholder="Add"
                :value="activeTab"
                size="small"
                @change="selectActiveTab"
              >
                <el-option v-for="item in availableChannels" :key="item" :label="item" :value="item"></el-option>
              </el-select>
          </form-element>

          <form-element>
            <draggable
                class="drag-list"
                :list="selectedChannels"
                group="channels"
                @change="updateChannels(selectedChannels)"
              >
                <el-tag
                  class="draggable"
                  v-for="element in selectedChannels"
                  :key="element"
                  closable
                  @close="removeChannel(element)"
                >
                  {{ element }}
                </el-tag>
            </draggable>
          </form-element>
        </div>
        <div class="row">
          <form-element label="Options">
            <el-switch
              :value="sharePlaytime"
              active-text="Share Playtime"
              @change="updateSharePlaytime"
            ></el-switch>
            <el-switch
              :value="embedPlayer"
              active-text="Embed Player"
              @change="updateEmbedPlayer"
            ></el-switch>
          </form-element>
        </div>
      </div>
    </card>

    <card title="Subscribe Button">
      <form-element label="Feed" :full="true">
        <el-input
          size="small"
          placeholder="Service Id"
          :value="feed"
          @input="updateFeed"
          clearable
        ></el-input>
      </form-element>

      <form-element label="Clients">
        <el-select
            placeholder="Add"
            value=""
            :disabled="false"
            size="small"
            @change="addClient"
          >
            <el-option v-for="item in availableClients" :key="item.id" :label="item.title" :value="item"></el-option>
          </el-select>
          <draggable
            class="drag-list"
            :list="selectedClients"
            group="channels"
            @change="updateClients(selectedClients)"
          >
            <el-tag
              class="draggable"
              v-for="element in selectedClients"
              :key="element.id"
              closable
              :hit="stagedClient.id === element.id"
              :type="element.serviceScheme ? 'success' : ''"
              @click="stageClient(element)"
              @close="removeClient(element)"
            >
              {{ element.title }}
            </el-tag>
          </draggable>
      </form-element>
      <div v-if="stagedClient.id">
        <form-element label="Supported Platforms">
          <el-tag v-for="platform in stagedClient.platforms" :key="platform" size="small" type="info" class="platform">{{ platform }}</el-tag>
        </form-element>

        <form-element>
          <el-tooltip slot="label" :content="stagedClient.serviceScheme('[service-id]')" placement="top-start">
            <h4 class="item-title"><span>Service Id</span><i class="el-icon-info" /></h4>
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
    </card>
  </div>
</template>

<script>
import Draggable from "vuedraggable";
import { get } from "lodash";
import { mapGetters, mapActions } from "vuex";
import { Card, FormElement } from '../components';

export default {
  components: {
    Draggable,
    Card,
    FormElement
  },

  computed: {
    ...mapGetters(["routeId", "configs", "channels", "clients", "stagedClient", "tabs"]),

    config() {
      return get(this.configs, this.routeId, {});
    },

    selectedChannels() {
      return get(this.config, "share.channels", []);
    },

    availableChannels() {
      return (this.channels || []).filter(channel => !this.selectedChannels.includes(channel));
    },

    sharePlaytime() {
      return get(this.config, "share.sharePlaytime");
    },

    embedPlayer() {
      return !!get(this.config, "share.outlet");
    },

    feed() {
      return get(this.config, ["subscribe-button", "feed"]);
    },

    selectedClients() {
      return get(this.config, ["subscribe-button", "clients"], []).map(item => ({
        ...(this.clients || []).find(client => client.id === item.id),
        ...item
      }));
    },

    activeTab() {
      return get(this.config, 'activeTab')
    },

    availableClients() {
      return (this.clients || []).filter(client => this.selectedClients.findIndex(selected => selected.id === client.id) === -1);
    }
  },

  methods: {
    ...mapActions(["updateChannels", "removeChannel", "addChannel", "updateSharePlaytime", "updateEmbedPlayer", "addClient", "removeClient", "updateClients", "updateClientService", "stageClient", "updateFeed", "selectActiveTab"])
  }
};
</script>

<style lang="scss">
.draggable {
  width: 100%;
  display: block;
  position: relative;
  cursor: move;
  margin-bottom: 0.5em;

  .el-icon-close {
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -8px;
  }
}

.column {
  display: flex;
}

.platform {
  margin-right: 0.5em;
}

.drag-list {
  width: 100%;
}
</style>

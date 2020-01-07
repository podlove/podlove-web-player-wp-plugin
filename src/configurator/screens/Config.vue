<template>
  <div class="config">
    <el-card class="config-card">
      <h4 class="card-title" slot="header">Active Tab</h4>
       <el-select
            placeholder="Select Tab"
            :value="activeTab"
            size="small"
            class="select input"
            @change="selectActiveTab"
          >
            <el-option v-for="item in tabs" :key="item" :label="item" :value="item"></el-option>
          </el-select>
    </el-card>
    <el-card class="config-card">
      <h4 class="card-title" slot="header">Share Tab</h4>
      <div class="card-content">
        <div class="item">
          <h4 class="item-title">Channels</h4>
          <el-select
            placeholder="Add"
            :value="activeTab"
            size="small"
            class="select input"
            @change="selectActiveTab"
          >
            <el-option v-for="item in availableChannels" :key="item" :label="item" :value="item"></el-option>
          </el-select>
          <draggable
            class="drag-list"
            :list="selectedChannels"
            group="channels"
            @change="updateChannels(selectedChannels)"
          >
            <el-tag
              class="draggable input"
              v-for="element in selectedChannels"
              :key="element"
              closable
              @close="removeChannel(element)"
            >
              {{ element }}
            </el-tag>
          </draggable>
        </div>
        <div class="item">
          <h4 class="item-title">Options</h4>
          <el-switch
            class="input"
            :value="sharePlaytime"
            active-text="Share Playtime"
            @change="updateSharePlaytime"
          ></el-switch>
          <el-switch
            class="input"
            :value="embedPlayer"
            active-text="Embed Player"
            @change="updateEmbedPlayer"
          ></el-switch>
        </div>
      </div>
    </el-card>
    <el-card class="config-card">
      <h4 class="card-title" slot="header">Subscribe Button</h4>
      <div class="item-full">
        <h4 class="item-title">Feed</h4>
        <el-input
          size="small"
          class="input"
          placeholder="Service Id"
          :value="feed"
          @input="updateFeed"
          clearable
        ></el-input>
      </div>
      <div class="card-content">
        <div class="item">
          <h4 class="item-title">Clients</h4>
          <el-select
            placeholder="Add"
            value=""
            :disabled="false"
            size="small"
            class="select input"
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
              class="draggable input"
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
        </div>
        <div class="item" v-if="stagedClient.id">
          <h4 class="item-title">Supported Platforms</h4>
          <div class="input">
            <el-tag v-for="platform in stagedClient.platforms" :key="platform" size="small" type="info" class="platform">{{ platform }}</el-tag>
          </div>

          <div v-if="stagedClient.serviceScheme">
            <el-tooltip class="item" :content="stagedClient.serviceScheme('[service-id]')" placement="top-start">
            <h4 class="item-title input"><span>Service Id</span><i class="el-icon-info" /></h4>
            </el-tooltip>
            <el-input
              size="small"
              class="input"
              placeholder="Service Id"
              :value="stagedClient.service"
              @input="updateClientService"
              clearable
            >
            </el-input>
          </div>
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
import Draggable from "vuedraggable";
import { get } from "lodash";
import { mapGetters, mapActions } from "vuex";

export default {
  components: {
    Draggable
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
.config-card {
  width: 100%;
  margin-bottom: 2em;

  .card-title {
    margin: 0;
  }
}

.card-content {
  display: flex;
}

.item-title {
  margin: 0 0 0.5em 0;

  .el-icon-info {
    margin-left: 0.25em;
  }
}

.draggable {
  width: 100%;
  display: block;
  position: relative;
  cursor: move;

  .el-icon-close {
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -8px;
  }
}

.item {
  width: 200px;
  margin-right: 2em;
}

.item-full {
  width: 400px;
  margin-bottom: 2em;
}

.input {
  margin-bottom: 0.5em;
  width: 100%;
  min-height: 32px;
  display: flex;
  align-items: center;
}

.select {
  input {
    background: #fff;
    border: 1px solid #dcdfe6;
  }
}

.platform {
  margin-right: 0.5em;
}
</style>

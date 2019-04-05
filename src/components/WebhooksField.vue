<template>
  <k-field
    class="pju-webhooks"
    :class="{ monochrome: monochrome }"
    :label='label'
  >

    <WebhooksStatus
      :status="status"
      :hookUpdated="hookUpdatedLive"
      :hookName="hook.name"
      :labels="labels"
    />

    <k-button
      class="pju-webhooks--btn"
      icon="upload"
      type="submit"
      @click="triggerHook"
      :disabled="ctaDisabled"
    >
      {{ statusCta }}
    </k-button>

  </k-field>

</template>

<script>
import WebhooksStatus from './WebhooksStatus.vue';
import { request } from '../helpers/request.js';

export default {
  components: {
    WebhooksStatus
  },
  props: {
    statusInitial: Object,
    label: String,
    name: String,
    hook: {
      type: Object,
      required: true
    },
    endpoint: {
      type: String,
      required: true
    },
    siteModified: Number,
    hookUpdated: Number,
    labels: {
      type: Object,
      required: true
    },
    debug: Boolean,
    monochrome:  Boolean,
    payload: String
  },
  data() {
    return {
      statusLive: this.statusInitial,
      timer: null,
      hookUpdatedLive: this.hookUpdated,
      siteModifiedLive: this.siteModified
    }
  },
  computed: {
    statusCta() {
      if (!this.labels[this.status]) return 'Run now';

      return this.labels[this.status].cta;
    },
    ctaDisabled() {
      return ['hooksEmpty', 'hookNotfound', 'hookNoUrl'].includes(this.status);
    },
    status() {
      return this.siteModifiedLive > this.hookUpdatedLive ? 'outdated' : this.statusLive
    }
  },
  methods: {
    triggerHook() {
      const url = this.hook.url;

      const success = (http) => {
        const response = JSON.parse(http.response);

        this.setStatus('progress', response);
        this.log(`hook ${this.hook.name} started`);
      };
      const error = (http) => {
        this.setStatus('error', http.response);
        this.log('could not reach webhook URL', true);
      };

      request(url, this.hook.method, success, error, this.hook.payload);
    },
    getStatus() {
      const url = `/${this.endpoint}/${this.hook.name}/status`;

      const success = (http) => {
        const response = JSON.parse(http.response);

        if (response && response.status !== this.statusLive) {
          this.statusLive = response.status;
          this.updateTime();
        }
      };
      const error = () => this.log('there was an error with checking the status :(', true);

      request(url, 'GET', success, error);
    },
    getSiteModified() {
      const url = `/${this.endpoint}/site-modified`;

      const success = (http) => {
        const response = JSON.parse(http.response);

        if (response && response.modified) {
          this.siteModifiedLive = response.modified;
        }
      };
      const error = () => this.log('could not get the time the site was last modified :(', true);

      request(url, 'GET', success, error);
    },
    setStatus(status, payload = null) {
      this.statusLive = status;
      this.updateTime();

      const url = `/${this.endpoint}/${this.hook.name}/${status}`;

      const success = (http) => this.log(http.response);
      const error = () => this.log('there was an error with updating the status :(', true);

      request(url, 'POST', success, error, payload);
    },
    updateTime() {
      // if we set the status to someting else than success, we set the new time
      if (this.statusLive !== 'success') {
        this.hookUpdatedLive = Date.now() / 1000;
      }
    },
    log(message, error = false) {
      if (!this.debug) return;

      const logger = error ? console.warn : console.log;

      logger(message);
    }
  },
  watch: {
    status: {
      immediate: true,
      handler(newVal) {
        if (newVal === 'progress') {
          // Start listening if the webhook ran successfully or if there where errors
          this.timer = setInterval(this.getStatus, 1000);
        } else if (newVal === 'success' || newVal === 'error') {
          // Start listening if there are new changes
          this.timer = setInterval(this.getSiteModified, 1000);
        } else {
          clearInterval(this.timer);
        }
      }
    }
  }
};
</script>

<style lang="scss">
.pju-webhooks {
  .visuallyhidden {
    position: absolute;
    border: 0;
    clip: rect(0 0 0 0);
    clip-path: inset(50%);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    width: 1px;
    white-space: nowrap;
  }
}

.pju-webhooks--btn {
  display: block;
  color: #fff;
  background: #2d2f36;
  padding: 0.75em 1.5em 0.875em;
  border-radius: 6px;
}
</style>

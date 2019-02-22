<template>
  <k-field
    class="pju-webhooks"
    :label='label'
  >

    <WebhooksStatus
      :status="status"
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
    initialStatus: Object,
    label: String,
    name: String,
    hook: Object,
    deployed: String,
    changed: String,
    labels: Object
  },
  data() {
    return {
      status: this.initialStatus.status,
      timer: null
    }
  },
  computed: {
    statusCta() {
      if (!this.labels[this.status]) return 'Run now';

      return this.labels[this.status].cta;
    },
    ctaDisabled() {
      return ['hooksEmpty', 'hookNotfound', 'hookNoUrl'].includes(this.status);
    }
  },
  methods: {
    triggerHook() {
      this.setStatus('progress');

      const url = this.hook.url;
      const success = () => console.info('Webhook triggered');
      const error = () => {
        console.info('Could not reach webhook URL');
        this.setStatus('error');
      };

      request(url, 'POST', success, error);
    },
    getStatus() {
      const url = `/webhooks/${this.hook.name}/status`;
      const success = (http) => {
        const response = JSON.parse(http.response);

        if (response && response.status !== this.status) {
          this.status = response.status;
        }
      };
      const error = () => console.info('There was an error with checking the status :(');

      request(url, 'GET', success, error);
    },
    setStatus(status) {
      this.status = status;

      const url = `/webhooks/${this.hook.name}/${status}`;
      const success = () => console.info('Webhook status successfully updated');
      const error = () => console.info('There was an error with updating the status :(');

      request(url, 'GET', success, error);
    }
  },
  watch: {
    status: {
      immediate: true,
      handler(newVal) {
        if (newVal === 'progress') {
          this.timer = setInterval(this.getStatus, 1000);
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

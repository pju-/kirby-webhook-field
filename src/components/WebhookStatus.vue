<template>
  <k-box
    class="pju-webhook--status"
    :class="[ statusClass, iconClass ]"
  >

    <span class="visuallyhidden">Status</span>

    <k-icon class="pju-webhook--status--icon" :type="icon" />

    <div class="pju-webhook--status--label">

      <p class="pju-webhook--status--name">
        {{ statusName }}
      </p>

      <p class="pju-webhook--status--description">
        <small>
          {{ statusText }}
        </small>
      </p>

      <p class="pju-webhook--status--description">
        <small>
          {{ updatedText }}
        </small>
      </p>

    </div>

  </k-box>
</template>

<script>
export default {
  props: {
    status: String,
    hookName: String,
    hookUpdated: Number,
    labels: Object
  },
  computed: {
    icon() {
      let iconName;

      switch (this.status) {
        case 'success':
          iconName = 'check';
          break;
        case 'progress':
          iconName = 'loader';
          break;
        case 'error':
          iconName = 'cancel';
          break;
        case 'new':
          iconName = 'bolt';
          break;
        case 'outdated':
          iconName = 'alert';
          break;
        default:
          iconName = 'cancel';
      }

      return iconName;
    },
    statusName() {
      if (!this.labels[this.status]) return this.status;

      return this.labels[this.status].name;
    },
    statusText() {
      if (!this.labels[this.status]) return '';

      return this.labels[this.status].text;
    },
    statusClass() {
      return `status-${this.status}`;
    },
    iconClass() {
      return `icon-${this.icon}`;
    },
    updatedText() {
      const date = new Date(this.hookUpdated * 1000);

      const options = {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric'
      };

      if (typeof window === "undefined" || !window.Intl || typeof window.Intl !== "object") {
        return '';
      }

      return new Intl.DateTimeFormat(undefined, options).format(date);
    }
  }
};
</script>

<style lang="scss">
@keyframes pju-webhook--anim-spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.pju-webhook--status {
  display: flex;
  margin-top: 1rem;
  margin-bottom: 2rem;
}

.pju-webhook--status--icon {
  margin-right: 1rem;

  svg {
    width: 3rem;
    height: 3rem;
  }

  .pju-webhook:not(.monochrome) .icon-cancel & {
    color: #c82829;
  }

  .pju-webhook .icon-loader & {
    color: #4271ae;

    svg {
      animation: 1s pju-webhook--anim-spin infinite linear;
    }
  }

  .pju-webhook:not(.monochrome) .icon-check & {
    color: #5d800d;
  }

  .pju-webhook:not(.monochrome) .icon-alert & {
    color: #f5871f;
  }
}

.pju-webhook--status--name {
  margin-bottom: 0.5em;
}

.pju-webhook--status--description {
  color: #777;
}
</style>

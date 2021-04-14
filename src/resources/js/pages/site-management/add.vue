<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('add_configurations')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("site.add.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="config.displayName"
              size="6"
              :label="$t('site.add.field.displayName.title')"
              :placeholder="$t('site.add.field.displayName.placeholder')"
              required
              :alert="errors.displayName"
            ></badaso-text>
            <badaso-text
              v-model="config.key"
              size="6"
              :label="$t('site.add.field.key.title')"
              required
              :placeholder="$t('site.add.field.key.placeholder')"
              :alert="errors.key"
            ></badaso-text>
            <badaso-select
              v-model="config.type"
              size="6"
              :label="$t('site.add.field.type.title')"
              :placeholder="$t('site.add.field.type.placeholder')"
              :items="componentList"
              :alert="errors.type"
            ></badaso-select>
            <badaso-select
              v-model="config.group"
              size="6"
              :label="$t('site.add.field.group.title')"
              :placeholder="$t('site.add.field.group.placeholder')"
              :items="groupList"
              :alert="errors.group"
            ></badaso-select>
            <vs-col vs-lg="12">
              <label for="" class="vs-input--label">{{
                $t("site.add.field.options.title")
              }}</label>
              <badaso-code-editor
                v-model="config.details"
                :alert="errors.details"
              >
              </badaso-code-editor>
            </vs-col>
            <vs-col vs-lg="12">
              <p>{{ $t("site.add.field.options.description") }}</p>
              <pre
                >{{ example }}
              </pre>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t("site.add.button") }}
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "SiteManagementAdd",
  components: {},
  data: () => ({
    errors: {},
    config: {
      displayName: "",
      key: "",
      type: "",
      group: "",
      details: "",
    },
    example: {
      items: [
        {
          label: "This is label",
          value: "this_is_value",
        },
      ],
    },
  }),
  computed: {
    componentList: {
      get() {
        return this.$store.getters["badaso/getComponent"];
      },
    },
    groupList: {
      get() {
        return this.$store.getters["badaso/getSiteGroup"];
      },
    },
  },
  methods: {
    submitForm() {
      this.$openLoader();
      this.$api.badasoConfiguration
        .add(this.$caseConvert.snake(this.config))
        .then((response) => {
          this.$closeLoader();
          this.$router.push({ name: "SiteManagementBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>

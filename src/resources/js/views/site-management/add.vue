<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_configurations')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('site.add.title') }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="config.displayName"
              size="6"
              :label="$t('site.add.field.displayName.title')"
              :placeholder="$t('site.add.field.displayName.placeholder')"
              required
            ></badaso-text>
            <badaso-text
              v-model="config.key"
              size="6"
              :label="$t('site.add.field.key.title')"
              required
              :placeholder="$t('site.add.field.key.placeholder')"
            ></badaso-text>
            <badaso-select
              v-model="config.type"
              size="6"
              :label="$t('site.add.field.type.title')"
              :placeholder="$t('site.add.field.type.placeholder')"
              :items="componentList"
            ></badaso-select>
            <badaso-select
              v-model="config.group"
              size="6"
              :label="$t('site.add.field.group.title')"
              :placeholder="$t('site.add.field.group.placeholder')"
              :items="groupList"
            ></badaso-select>
            <vs-col vs-lg="12">
              <label for="" class="vs-input--label">{{ $t('site.add.field.options.title') }}</label>
              <badaso-code-editor v-model="config.options">
              </badaso-code-editor>
            </vs-col>
            <vs-col vs-lg="12">
              <p>{{ $t('site.add.field.options.description') }}</p>
              <pre>{{ $t('site.add.field.options.example') }}</pre>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t('site.add.button') }}
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoText from "../../components/BadasoText";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoCodeEditor from "../../components/BadasoCodeEditor";

export default {
  name: "Browse",
  components: {
    BadasoBreadcrumb,
    BadasoText,
    BadasoSelect,
    BadasoCodeEditor,
  },
  data: () => ({
    config: {
      displayName: '',
      key: '',
      type: '',
      group: '',
      options: '',
    },
  }),
  computed: {
    componentList: {
      get() {
        return this.$store.getters.getComponent
      }
    },
    groupList: {
      get() {
        return this.$store.getters.getSiteGroup
      }
    },
  },
  methods: {
    submitForm() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.configuration.add(this.$caseConvert.snake(this.config))
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({name: "SiteBrowse"})
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({title: this.$t('alert.danger'),text:error.message,color:'danger'})
        })
    },
  },
};
</script>
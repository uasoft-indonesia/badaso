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
            <h3>Add Site Configuration</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="config.displayName"
              size="6"
              label="Display Name"
              placeholder="Example: Display Name"
              required
            ></badaso-text>
            <badaso-text
              v-model="config.key"
              size="6"
              label="Key"
              required
              placeholder="Example: key_sample"
            ></badaso-text>
            <badaso-select
              v-model="config.type"
              size="6"
              label="Type"
              placeholder="Type"
              :items="componentList"
            ></badaso-select>
            <badaso-select
              v-model="config.group"
              size="6"
              label="Group"
              placeholder="Group"
              :items="groupList"
            ></badaso-select>
            <vs-col vs-lg="12">
              <label for="" class="vs-input--label">Options</label>
              <badaso-code-editor v-model="config.options">
              </badaso-code-editor>
            </vs-col>
            <vs-col vs-lg="12">
              <p>Options is required for Checkbox, Radio, Select, Select-multiple. Example: </p>
              <pre>[{"label":"This is label","value":"this_is_value"}]</pre>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Save
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
          this.$vs.notify({title:'Danger',text:error.message,color:'danger'})
        })
    },
  },
};
</script>
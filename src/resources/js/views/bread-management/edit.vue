<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add BREAD for {{ $route.params.tableName }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="dataBread.name"
              size="6"
              label="Table Name"
              placeholder="Table Name"
              required
              readonly
            ></badaso-text>
            <badaso-switch
              size="3"
              v-model="dataBread.generatePermissions"
              label="Generate Permissions"
            ></badaso-switch>
            <badaso-switch
              size="3"
              v-model="dataBread.serverSide"
              label="Server Side"
            ></badaso-switch>
          </vs-row>
          <vs-row>
            <badaso-text
              v-model="dataBread.displayNameSingular"
              size="6"
              label="Display Name(Singular)"
              required
              placeholder="Display Name(Singular)"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.displayNamePlural"
              size="6"
              label="Display Name(Plural)"
              required
              placeholder="Display Name(Plural)"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.slug"
              size="6"
              label="URL Slug (must be unique)"
              required
              placeholder="URL Slug (must be unique)"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.icon"
              size="6"
              label="Icon"
              placeholder="Icon"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.modelName"
              size="6"
              label="Model Name"
              placeholder="Model Name"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.controller"
              size="6"
              label="Controller Name"
              placeholder="Controller Name"
            ></badaso-text>
            <badaso-select
              v-model="dataBread.orderColumn"
              size="3"
              label="Order Column"
              placeholder="Order Column"
              :items="dataBread.rows"
            ></badaso-select>
            <badaso-select
              v-model="dataBread.orderDisplayColumn"
              size="3"
              label="Order Display Column"
              placeholder="Order Display Column"
              :items="dataBread.rows"
            ></badaso-select>
            <badaso-select
              v-model="dataBread.orderDirection"
              size="3"
              label="Order Direction"
              placeholder="Order Direction"
              :items="orderDirections"
            ></badaso-select>
            <badaso-select
              v-model="dataBread.defaultServerSideSearchField"
              size="3"
              label="Default Server Side Search Field"
              placeholder="Default Server Side Search Field"
              :items="dataBread.rows"
            ></badaso-select>
            <badaso-textarea
              size="12"
              label="Description"
              placeholder="Description"
              v-model="dataBread.description"
            >
            </badaso-textarea>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add BREAD Fields for {{ $route.params.tableName }}</h3>
          </div>
          <vs-row>
            <vs-col col-lg="12" style="overflow-x: auto">
              <table class="table">
                <thead>
                  <th style="width: 1%; word-wrap: nowrap;"></th>
                  <th style="width: 1%; word-wrap: nowrap;">Field</th>
                  <th style="width: 1%; word-wrap: nowrap;">Visibility</th>
                  <th style="width: 1%; word-wrap: nowrap;">Input Type</th>
                  <th style="width: 200px;">Display Name</th>
                  <th>Optional Details</th>
                </thead>
                <draggable v-model="dataBread.rows" tag="tbody">
                  <tr :key="index" v-for="(field, index) in dataBread.rows">
                    <td>
                      <vs-icon
                        icon="drag_indicator"
                        class="drag_icon"
                      ></vs-icon>
                    </td>
                    <td :data="field.field">
                      <strong>{{ field.field }}</strong>
                      <br />
                      <span style="white-space: nowrap">
                        Type: {{ field.type }}
                      </span>
                      <br />
                      <span style="white-space: nowrap">
                        Required: <span v-if="field.required">Yes</span
                        ><span v-else>No</span>
                      </span>
                    </td>
                    <td>
                      <vs-checkbox
                        v-model="field.browse"
                        class="mb-1"
                        style="justify-content: start;"
                        >Browse</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.read"
                        class="mb-1"
                        style="justify-content: start;"
                        >Read</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.edit"
                        class="mb-1"
                        style="justify-content: start;"
                        >Edit</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.add"
                        class="mb-1"
                        style="justify-content: start;"
                        >Add</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.delete"
                        class="mb-1"
                        style="justify-content: start;"
                        >Delete</vs-checkbox
                      >
                    </td>
                    <td>
                      <vs-select class="selectExample" v-model="field.type">
                        <vs-select-item
                          :key="index"
                          :value="item.value"
                          :text="item.label"
                          v-for="(item, index) in componentList"
                        />
                      </vs-select>
                    </td>
                    <td>
                      <vs-input
                        class="inputx"
                        placeholder="Display Name"
                        v-model="field.displayName"
                      />
                    </td>
                    <td>
                      <badaso-code-editor v-model="field.details">
                      </badaso-code-editor>
                    </td>
                  </tr>
                </draggable>
              </table>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row>
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
import draggable from "vuedraggable";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoText from "../../components/BadasoText";
import BadasoSwitch from "../../components/BadasoSwitch";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoCodeEditor from "../../components/BadasoCodeEditor";
import BadasoTextarea from "../../components/BadasoTextarea";

export default {
  name: "Browse",
  components: {
    draggable,
    BadasoBreadcrumb,
    BadasoText,
    BadasoSwitch,
    BadasoSelect,
    BadasoCodeEditor,
    BadasoTextarea,
  },
  data: () => ({
    tableColumns: [],
    orderDirections: [
      {
        label: "Ascending",
        value: "asc",
      },
      {
        label: "Descending",
        value: "desc",
      },
    ],
    dataBread: {
      name: "",
      slug: "",
      displayNameSingular: "",
      displayNamePlural: "",
      icon: "",
      modelName: "",
      policyName: "",
      description: "",
      generatePermissions: true,
      serverSide: false,
      details: "",
      controller: "",
      rows: [],
    },
  }),
  computed: {
    componentList: {
      get() {
        return this.$store.getters.getComponent
      }
    },
  },
  mounted() {
    this.dataBread.name = this.$route.params.tableName;
    this.dataBread.displayNameSingular = this.$helper.generateDisplayName(
      this.$route.params.tableName
    );
    this.dataBread.displayNamePlural =
      this.$helper.generateDisplayName(this.$route.params.tableName) + "s";
    this.dataBread.slug = this.$helper.generateSlug(
      this.$route.params.tableName
    );
    this.getTableDetail();
  },
  methods: {
    submitForm() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .edit(this.$caseConvert.snake(this.dataBread))
        .then((response) => {
          this.$vs.loading.close();
          this.$store.commit("FETCH_MENU");
          this.$router.push({name: "BreadBrowse"})
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({title:'Danger',text:error.message,color:'danger'})
        });
    },
    getTableDetail() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .read({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          let dataBread = response.data;
          this.dataBread = dataBread
          this.dataBread.icon = dataBread.icon ? dataBread.icon : "",
          this.dataBread.modelName = dataBread.modelName ? dataBread.modelName : "",
          this.dataBread.policyName = dataBread.policyName ? dataBread.policyName : "",
          this.dataBread.description = dataBread.description ? dataBread.description : "",
          this.dataBread.generatePermissions = dataBread.generatePermissions === 1,
          this.dataBread.serverSide = dataBread.serverSide === 1,
          this.dataBread.controller = dataBread.controller ? dataBread.controller : "",
          this.dataBread.rows = dataBread.dataRows.map((field) => {
            return {
              label: field.field,
              value: field.field,
              field: field.field,
              type: field.type,
              displayName: field.displayName,
              required: field.required === 1,
              browse: field.browse === 1,
              read: field.read === 1,
              edit: field.edit === 1,
              add: field.add === 1,
              delete: field.delete === 1,
              details: field.details,
              order: field.order,
            };
          });
          this.$vs.loading.close();
        })
        .catch((error) => {
          this.$vs.loading.close();
        });
    },
  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}</style
>s

<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_bread')">
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
              :alert="errors.name"
            ></badaso-text>
            <badaso-switch
              size="3"
              v-model="dataBread.generatePermissions"
              label="Generate Permissions"
              :alert="errors.generatePermissions"
            ></badaso-switch>
            <badaso-switch
              size="3"
              v-model="dataBread.serverSide"
              label="Server Side"
              :alert="errors.serverSide"
            ></badaso-switch>
          </vs-row>
          <vs-row>
            <badaso-text
              v-model="dataBread.displayNameSingular"
              size="6"
              label="Display Name(Singular)"
              required
              placeholder="Display Name(Singular)"
              :alert="errors.displayNameSingular"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.displayNamePlural"
              size="6"
              label="Display Name(Plural)"
              placeholder="Display Name(Plural)"
              :alert="errors.displayNamePlural"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.slug"
              size="6"
              label="URL Slug (must be unique)"
              placeholder="URL Slug (must be unique)"
              :alert="errors.slug"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.icon"
              size="6"
              label="Icon"
              placeholder="Icon"
              :alert="errors.icon"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.modelName"
              size="6"
              label="Model Name"
              placeholder="Model Name"
              :alert="errors.modelName"
            ></badaso-text>
            <badaso-text
              v-model="dataBread.controller"
              size="6"
              label="Controller Name"
              placeholder="Controller Name"
              :alert="errors.controller"
            ></badaso-text>
            <badaso-select
              v-model="dataBread.orderColumn"
              size="4"
              label="Order Column"
              placeholder="Order Column"
              :items="fieldList"
              :alert="errors.orderColumn"
            ></badaso-select>
            <badaso-select
              v-model="dataBread.orderDisplayColumn"
              size="4"
              label="Order Display Column"
              placeholder="Order Display Column"
              :items="fieldList"
              :alert="errors.orderDisplayColumn"
              additionalInfo="<p class='text-muted'>Order Column will be filled with numbers to sort data if this field is set</p>"
            ></badaso-select>
            <badaso-select
              v-model="dataBread.orderDirection"
              size="4"
              label="Order Direction"
              placeholder="Order Direction"
              :items="orderDirections"
              :alert="errors.orderDirection"
            ></badaso-select>
            <badaso-hidden
              v-model="dataBread.defaultServerSideSearchField"
              size="3"
              label="Default Server Side Search Field"
              placeholder="Default Server Side Search Field"
              :items="fieldList"
              :alert="errors.defaultServerSideSearchField"
            ></badaso-hidden>
            <badaso-textarea
              size="12"
              label="Description"
              placeholder="Description"
              v-model="dataBread.description"
              :alert="errors.description"
            >
            </badaso-textarea>
          </vs-row>
        </vs-card>
      </vs-col>
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
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>
                You're not allowed to add BREAD
              </h3>
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
import BadasoHidden from '../../components/BadasoHidden.vue';

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
    BadasoHidden,
  },
  data: () => ({
    errors: {},
    breadcrumb: [],
    errors: {},
    fieldList: [],
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
      orderColumn: "",
      orderDisplayColumn: "",
      orderDirection: "",
      rows: [],
    },
  }),
  computed: {
    componentList: {
      get() {
        return this.$store.getters.getComponent;
      },
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
      this.errors = {}
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .add(this.$caseConvert.snake(this.dataBread))
        .then((response) => {
          this.$vs.loading.close();
          this.$store.commit("FETCH_MENU");
          this.$store.commit("FETCH_USER");
          this.$router.push({ name: "BreadBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    getTableDetail() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .readTable({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          let fieldList = response.data.tableFields;
          this.tableColumns = fieldList;
          this.fieldList = fieldList.map((field) => {
            return {
              label: field.name,
              value: field.name,
            };
          });
          this.dataBread.rows = fieldList.map((field) => {
            return {
              field: field.name,
              type: this.$helper.mapFieldType(field.type),
              displayName: this.$helper.generateDisplayName(field.name),
              required: field.isNotNull,
              browse: true,
              read: true,
              edit: false,
              add: false,
              delete: false,
              details: "{}",
              order: 1,
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
}
</style>

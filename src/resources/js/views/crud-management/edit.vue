<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('edit_crud_data')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Edit CRUD for {{ $route.params.tableName }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="crudData.name"
              size="6"
              label="Table Name"
              placeholder="Table Name"
              required
              readonly
            ></badaso-text>
            <badaso-switch
              size="3"
              v-model="crudData.generatePermissions"
              label="Generate Permissions"
              :alert="errors.generatePermissions"
            ></badaso-switch>
            <badaso-switch
              size="3"
              v-model="crudData.serverSide"
              label="Server Side"
              :alert="errors.serverSide"
            ></badaso-switch>
          </vs-row>
          <vs-row>
            <badaso-text
              v-model="crudData.displayNameSingular"
              size="6"
              label="Display Name(Singular)"
              required
              placeholder="Display Name(Singular)"
              :alert="errors.displayNameSingular"
            ></badaso-text>
            <badaso-text
              v-model="crudData.displayNamePlural"
              size="6"
              label="Display Name(Plural)"
              placeholder="Display Name(Plural)"
              :alert="errors.displayNamePlural"
            ></badaso-text>
            <badaso-text
              v-model="crudData.slug"
              size="6"
              label="URL Slug (must be unique)"
              required
              placeholder="URL Slug (must be unique)"
              :alert="errors.slug"
            ></badaso-text>
            <badaso-text
              v-model="crudData.icon"
              size="6"
              label="Icon"
              placeholder="Icon"
              :alert="errors.icon"
            ></badaso-text>
            <badaso-text
              v-model="crudData.modelName"
              size="6"
              label="Model Name"
              placeholder="Model Name"
              :alert="errors.modelName"
            ></badaso-text>
            <badaso-text
              v-model="crudData.controller"
              size="6"
              label="Controller Name"
              placeholder="Controller Name"
              :alert="errors.controller"
            ></badaso-text>
            <badaso-select
              v-model="crudData.orderColumn"
              size="4"
              label="Order Column"
              placeholder="Order Column"
              :items="crudData.rows"
              :alert="errors.orderColumn"
            ></badaso-select>
            <badaso-select
              v-model="crudData.orderDisplayColumn"
              size="4"
              label="Order Display Column"
              placeholder="Order Display Column"
              :items="crudData.rows"
              :alert="errors.orderDisplayColumn"
              additionalInfo="<p class='text-muted'>Order Column will be filled with numbers to sort data if this field is set</p>"
            ></badaso-select>
            <badaso-select
              v-model="crudData.orderDirection"
              size="4"
              label="Order Direction"
              placeholder="Order Direction"
              :items="orderDirections"
              :alert="errors.orderDirection"
            ></badaso-select>
            <badaso-hidden
              v-model="crudData.defaultServerSideSearchField"
              size="3"
              label="Default Server Side Search Field"
              placeholder="Default Server Side Search Field"
              :items="crudData.rows"
              :alert="errors.defaultServerSideSearchField"
            ></badaso-hidden>
            <badaso-textarea
              size="12"
              label="Description"
              placeholder="Description"
              v-model="crudData.description"
              :alert="errors.description"
            >
            </badaso-textarea>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add CRUD Fields for {{ $route.params.tableName }}</h3>
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
                <draggable v-model="crudData.rows" tag="tbody">
                  <tr :key="index" v-for="(field, index) in crudData.rows">
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
                      <badaso-code-editor
                        v-model="field.details"
                        v-if="field.type !== 'relation'"
                      >
                      </badaso-code-editor>
                      <vs-button
                        color="primary"
                        type="relief"
                        @click.stop
                        @click="openRelationSetup(field)"
                        v-else
                        >Set Relation</vs-button
                      >
                      <vs-popup
                        class="holamundo"
                        title="Set Relation"
                        :active.sync="field.setRelation"
                      >
                        <vs-row>
                          <badaso-select
                            size="12"
                            v-model="relation.relationType"
                            :items="relationTypes"
                            label="Relation Type"
                          ></badaso-select>
                          <vs-col vs-lg="12" class="mb-3">
                            <vs-select
                              v-model="relation.destinationTable"
                              label="Destination Table"
                              width="100%"
                              @input="changeTable"
                            >
                              <vs-select-item
                                :key="index"
                                :value="item.value ? item.value : item"
                                :text="item.label ? item.label : item"
                                v-for="(item, index) in destinationTables"
                              />
                            </vs-select>
                          </vs-col>
                          <badaso-select
                            size="12"
                            v-model="relation.destinationTableColumn"
                            :items="destinationTableColumns"
                            label="Destination Column"
                          ></badaso-select>
                          <badaso-select
                            size="12"
                            v-model="relation.destinationTableDisplayColumn"
                            :items="destinationTableColumns"
                            label="Destination Column To Display"
                          ></badaso-select>
                        </vs-row>
                        <vs-row vs-type="flex" vs-justify="space-between">
                          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                            <vs-button color="primary" @click="saveRelation(field)"
                              >Save</vs-button
                            >
                          </vs-col>
                          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                            <vs-button color="danger"  @click="field.setRelation = false"
                              >Cancel</vs-button
                            >
                          </vs-col>
                        </vs-row>
                      </vs-popup>
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
              <h3>You're not allowed to edit CRUD</h3>
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
import BadasoHidden from "../../components/BadasoHidden";

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
    details: "",
    tableColumns: [],
    orderDirections: [
      {
        label: "Ascending",
        value: "ASC",
      },
      {
        label: "Descending",
        value: "DESC",
      },
    ],
    crudData: {
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
    relationTypes: [],
    destinationTables: [],
    destinationTableColumns: [],
    relation: {}
  }),
  computed: {
    componentList: {
      get() {
        return this.$store.getters.getComponent;
      },
    },
  },
  mounted() {
    this.crudData.name = this.$route.params.tableName;
    this.crudData.displayNameSingular = this.$helper.generateDisplayName(
      this.$route.params.tableName
    );
    this.crudData.displayNamePlural =
      this.$helper.generateDisplayName(this.$route.params.tableName) + "s";
    this.crudData.slug = this.$helper.generateSlug(
      this.$route.params.tableName
    );
    this.getTableDetail();
    this.getRelationTypes();
    this.getDestinationTables();
  },
  methods: {
    openRelationSetup(field) {
      field.setRelation = true;
      this.relation = {
        relationType: field.relationType ? field.relationType : '',
        destinationTable: field.destinationTable ? field.destinationTable : '',
        destinationTableColumn: field.destinationTableColumn ? field.destinationTableColumn : '',
        destinationTableDisplayColumn: field.destinationTableDisplayColumn ? field.destinationTableDisplayColumn : '',
      }
      if (field.destinationTable !== '') {
        this.getDestinationTableColumns(field.destinationTable);
      }
    },
    changeTable(table) {
      if (table) {
        this.relation.destinationTableColumn = '';
        this.relation.destinationTableDisplayColumn = '';
        this.getDestinationTableColumns(table)
      }
    },
    saveRelation(field){
      field.relationType = this.relation.relationType;
      field.destinationTable = this.relation.destinationTable;
      field.destinationTableColumn = this.relation.destinationTableColumn;
      field.destinationTableDisplayColumn = this.relation.destinationTableDisplayColumn;
      this.relation = {};
      field.setRelation = false;
    },
    submitForm() {
      this.errors = {};
      this.$vs.loading({
        type: "sound",
      });
      this.$api.crud
        .edit(this.crudData)
        .then((response) => {
          this.$vs.loading.close();
          this.$store.commit("FETCH_MENU");
          this.$store.commit("FETCH_USER");
          this.$router.push({ name: "CRUDManagementBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors;
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
      this.$api.crud
        .read({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          let crudData = { ...response.data.crud };
          crudData = crudData;
          crudData.icon = crudData.icon ? crudData.icon : "";
          crudData.modelName = crudData.modelName ? crudData.modelName : "";
          crudData.policyName = crudData.policyName
            ? crudData.policyName
            : "";
          crudData.description = crudData.description
            ? crudData.description
            : "";
          crudData.generatePermissions = crudData.generatePermissions === 1;
          crudData.serverSide = crudData.serverSide === 1;
          crudData.controller = crudData.controller
            ? crudData.controller
            : "";
          crudData.orderColumn = crudData.orderColumn
            ? crudData.orderColumn
            : "";
          crudData.orderDisplayColumn = crudData.orderDisplayColumn
            ? crudData.orderDisplayColumn
            : "";
          crudData.orderDirection = crudData.orderDirection
            ? crudData.orderDirection
            : "";
          let dataRows = [...crudData.dataRows];
          crudData.rows = dataRows.map((field) => {
            return {
              label: field.field,
              value: field.field,
              field: field.field,
              type: this.$helper.mapFieldType(field.type),
              displayName: field.displayName,
              required: field.required === 1,
              browse: field.browse === 1,
              read: field.read === 1,
              edit: field.edit === 1,
              add: field.add === 1,
              delete: field.delete === 1,
              details: field.details,
              relationType: field.relation ? field.relation.relationType : '',
              destinationTable: field.relation
                ? field.relation.destinationTable
                : '',
              destinationTableColumn: field.relation
                ? field.relation.destinationTableColumn
                : '',
              destinationTableDisplayColumn: field.relation
                ? field.relation.destinationTableDisplayColumn
                : '',
              order: field.order,
              setRelation: false,
            };
          });
          this.crudData = JSON.parse(JSON.stringify(crudData));
          this.$vs.loading.close();
        })
        .catch((error) => {
          this.$vs.loading.close();
        });
    },
    getRelationTypes() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.data
        .tableRelations()
        .then((response) => {
          this.$vs.loading.close();
          this.relationTypes = response.data.tableRelations;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    getDestinationTables() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.table
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.destinationTables = response.data.tables;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    changeTable(table, field) {
      field.destinationTableColumn = '';
      field.destinationTableDisplayColumn = '';
      this.getDestinationTableColumns(table)
    },
    getDestinationTableColumns(table) {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.table
        .read({
          table
        })
        .then((response) => {
          this.$vs.loading.close();
          this.destinationTableColumns = response.data.tableFields;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
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

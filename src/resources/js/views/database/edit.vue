<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('edit_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('database.edit.title') }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="databaseData.table.modifiedName"
              size="6"
              :label="$t('database.edit.field.table')"
              :placeholder="$t('database.edit.field.table')"
              required
              :alert="errors.table"
            >
            </badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('database.edit.row.title') }}</h3>
          </div>
          <vs-row>
            <vs-col col-lg="12" style="overflow-x: auto">
              <table class="table">
                <thead>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldName') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldType') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldLength') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldDefault') }}</th>
                  <th style="min-width: 100px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldNull') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldIndex') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldAttribute') }}</th>
                  <th style="min-width: 100px; word-wrap: nowrap;">{{ $t('database.edit.row.field.fieldIncrement') }}</th>
                  <th style="min-width: 150px; word-wrap: nowrap;">{{ $t('database.edit.row.field.action') }}</th>
                </thead>
                <tr :key="index" v-for="(item, index) in databaseData.fields.currentFields" >
                  <td>
                    {{item.fieldName}}
                  </td>
                  <td>
                    {{item.fieldType}}
                  </td>
                  <td>
                    {{item.fieldLength}}
                  </td>
                  <td>
                    {{item.fieldDefault}}
                  </td>
                  <td>
                    {{item.fieldNull}}
                  </td>
                  <td>
                    {{item.fieldIndex}}
                  </td>
                  <td>
                    {{item.fieldAttribute}}
                  </td>
                  <td>
                    {{item.fieldIncrement}}
                  </td>
                  <td>
                    <vs-button color="success" type="relief" @click="editField(item, index)">
                      <vs-icon icon="edit"></vs-icon>
                    </vs-button>
                    <vs-button color="danger" type="relief" @click="dropField(index)">
                      <vs-icon icon="delete"></vs-icon>
                    </vs-button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <vs-input
                      class="inputx"
                      :placeholder="$t('database.edit.row.field.fieldName')"
                      v-model="fields.fieldName"
                      :alert="errors.fieldName"
                    />
                  </td>
                  <td>
                    <vs-select class="selectExample" v-model="fields.fieldType" :alert="errors.fieldType">
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldTypeList"
                      />
                    </vs-select>
                    <span style="color: rgba(var(--vs-danger),1)" v-if="errors.fieldType">{{ errors.fieldType }}</span>
                  </td>
                  <td>
                    <vs-input
                      class="inputx"
                      :placeholder="$t('database.edit.row.field.fieldLength')"
                      v-model="fields.fieldLength"
                      :alert="errors.fieldLength"
                    />
                    <span style="color: rgba(var(--vs-danger),1)" v-if="errors.fieldLength">{{ errors.fieldLength }}</span>
                  </td>
                  <td>
                    <vs-select class="selectExample mb-2" v-model="fields.fieldDefault" :alert="errors.fieldDefault">
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldDefaultList"
                      />
                    </vs-select>
                    <vs-input
                      v-if="fields.fieldDefault == 'as_defined'"
                      class="inputx"
                      :placeholder="$t('database.add.row.field.fieldDefault')"
                      v-model="fields.asDefined"
                      :alert="errors.asDefined"
                    />
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldNull"
                      class="mb-1"
                      style="justify-content: start;"
                      :alert="errors.fieldNull"
                      ></vs-checkbox
                    >
                  </td>
                  <td>
                    <vs-select class="selectExample" v-model="fields.fieldIndex" :alert="errors.fieldIndex">
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldIndexList"
                      />
                    </vs-select>
                  </td>
                  <td>
                    <vs-select class="selectExample" v-model="fields.fieldAttribute" :alert="errors.fieldAttribute">
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldAttributeList"
                      />
                    </vs-select>
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldIncrement"
                      class="mb-1"
                      style="justify-content: start;"
                      :alert="errors.fieldIncrement"
                      ></vs-checkbox>
                  </td>
                  <td>
                    <vs-button color="primary" type="relief" @click="setField()">
                      {{ $t('database.edit.row.field.add') }}
                    </vs-button>
                  </td>
                </tr>
              </table>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm()">
                <vs-icon icon="save"></vs-icon> {{ $t('database.edit.button') }}
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
              <h3>{{ $t('database.warning.notAllowed') }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-prompt
      color="success"
      @accept="saveEdit()"
      :is-valid="validEdit"
      :active.sync="editDialog"
      title="Edit"
      :accept-text="$t('action.delete.accept')"
      :cancel-text="$t('action.delete.cancel')">
      <div class="con-exemple-prompt">
        <vs-row>
          <vs-col vs-lg="6">
            <vs-input
              class="inputx mb-2"
              :label="$t('database.edit.row.field.fieldName')"
              :placeholder="$t('database.edit.row.field.fieldName')"
              v-model="edit.fieldName"
              :alert="errors.fieldName"
            />
          </vs-col>

          <vs-col vs-lg="6">
            <vs-input
              class="inputx mb-2"
              :placeholder="$t('database.edit.row.field.fieldLength')"
              :label="$t('database.edit.row.field.fieldLength')"
              v-model="edit.fieldLength"
              :alert="errors.fieldLength"
            />
          </vs-col>

          <vs-col vs-lg="12">
            <vs-select class="selectExample mb-2" :label="$t('database.edit.row.field.fieldType')" v-model="edit.fieldType" :alert="errors.fieldType">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldTypeList"
              />
            </vs-select>
          </vs-col>

          <vs-col vs-lg="12">
            <vs-select class="selectExample mb-2" :label="$t('database.edit.row.field.fieldDefault')" v-model="edit.fieldDefault" :alert="errors.fieldDefault">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldDefaultList"
              />
            </vs-select>
            <vs-input
              v-if="edit.fieldDefault == 'as_defined'"
              class="inputx mb-2"
              v-model="edit.asDefined"
              :alert="errors.asDefined"
            />
          </vs-col>

          <vs-col vs-lg="12">
            <vs-select class="selectExample mb-2" :label="$t('database.edit.row.field.fieldIndex')" v-model="edit.fieldIndex" :alert="errors.fieldIndex">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldIndexList"
              />
            </vs-select>
          </vs-col>

          <vs-col vs-lg="6">
            <vs-checkbox
              v-model="edit.fieldNull"
              class="mb-4 mt-4"
              style="justify-content: start;"
              :alert="errors.fieldNull"
              >
              {{ $t('database.edit.row.field.fieldNull') }}
            </vs-checkbox>
          </vs-col>

          <vs-col vs-lg="6">
            <vs-checkbox
              v-model="edit.fieldIncrement"
              class="mb-4 mt-4"
              style="justify-content: start;"
              :alert="errors.fieldIncrement"
              >{{ $t('database.edit.row.field.fieldIncrement') }}</vs-checkbox>
          </vs-col>
        
          <vs-col vs-lg="12">
            <vs-select class="selectExample mb-2" :label="$t('database.edit.row.field.fieldAttribute')" v-model="edit.fieldAttribute" :alert="errors.fieldAttribute">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldAttributeList"
              />
            </vs-select>
          </vs-col>
        </vs-row>
      </div>
    </vs-prompt>
  </div>
</template>
<script>
import draggable from "vuedraggable";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoText from "../../components/BadasoText";
import BadasoSwitch from "../../components/BadasoSwitch";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoHidden from '../../components/BadasoHidden.vue';

export default {
  name: "Browse",
  components: {
    draggable,
    BadasoBreadcrumb,
    BadasoText,
    BadasoSwitch,
    BadasoSelect,
    BadasoHidden,
  },
  data: () => ({
    errors: {},
    breadcrumb: [],
    fields: {
      fieldName: "",
      fieldType: "",
      fieldLength: "",
      fieldDefault: null,
      fieldNull: false,
      fieldIndex: null,
      fieldAttribute: null,
      fieldIncrement: false,
      asDefined: null,
    },
    databaseData: {
      table: {
        currentName: "",
        modifiedName: ""
      },
      fields: {
        currentFields: [],
        modifiedFields: []
      }
    },
    editDialog: false,
    deleteDialog: false,
    willEdit: "",
    willDelete: "",
    edit: {
      fieldName: "",
      fieldType: "",
      fieldLength: null,
      fieldDefault: null,
      fieldNull: false,
      fieldIndex: null,
      fieldAttribute: null,
      fieldIncrement: false,
      asDefined: null,
    },
  }),
  computed: {
    fieldTypeList: {
      get() {
        return this.$helper.getMigrationTypeList();
      },
    },
    fieldIndexList: {
      get() {
        return this.$helper.getMigrationIndexList();
      }
    },
    fieldDefaultList: {
      get() {
        return this.$helper.getMigrationDefaultList();
      }
    },
    fieldAttributeList: {
      get() {
        return this.$helper.getMigrationAttributeList();
      }
    },
    validEdit() {
      return (this.edit.fieldName !== null && this.edit.fieldType !== null)
    }
  },
  mounted() {
    this.getInfoTable();
  },
  methods: {
    getInfoTable() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.database
      .read({
        table: this.$route.params.tableName,
      })
      .then((response) => {
        let data = response.data["\u0000*\u0000items"];
        for (const [key, column] of Object.entries(data)) {
          this.databaseData.fields.currentFields.push({
            fieldName: column.name,
            fieldType: column.type,
            fieldLength: column.type == 'decimal' || column.type == 'double' || column.type == 'float' ? column.precision + ',' + column.scale : column.length,
            fieldDefault: column.default,
            fieldNull: column.notnull ? false : true,
            fieldIndex: Object.keys(column.indexes).length > 0 ? Object.keys(column.indexes).toString().toLowerCase() : null,
            fieldAttribute: column.unsigned == true ? 'unsigned' : null,
            fieldIncrement: column.autoincrement
          })
        }

        this.databaseData.table.currentName = this.$route.params.tableName
        this.databaseData.table.modifiedName = this.$route.params.tableName

        console.log(this.databaseData);
        this.$vs.loading.close();
      })
      .catch((error) => {
        this.$vs.loading.close();
        this.$vs.notify({
          title: this.$t('alert.danger'),
          text: error.message,
          color: "danger",
        });
      });
    },

    submitForm() {
      if (this.databaseData.table.modifiedName == '' || this.databaseData.table.modifiedName == undefined) {
        this.errors['tableName'] = this.$t('database.edit.error.tableName');
      } else {
        this.$vs.loading({
          type: "sound",
        });

        console.log(this.databaseData);

        this.$api.database
          .edit(this.databaseData)
          .then((response) => {
            this.$vs.loading.close();
            this.$vs.notify({
              title: this.$t('alert.success'),
              text: response.message,
              color: "success",
            });
            this.$store.commit("FETCH_MENU");
            this.$store.commit("FETCH_USER");
            this.$router.push({ name: "DatabaseBrowse" });
          })
          .catch((error) => {
            this.errors = error.error
            this.$vs.loading.close();
            this.$vs.notify({
              title: this.$t('alert.danger'),
              text: error.message,
              color: "danger",
            });
          });
      }
    },

    setField() {
      let errorCount = this.validate()

      if (errorCount == 0) {
        this.errors = {}

        this.databaseData.fields.modifiedFields.push({
          modifyType: "CREATE",
          ...this.fields
        })

        this.databaseData.fields.currentFields.push({
          ...this.fields
        })
        this.clearInput()
      }
    },

    clearInput() {
      this.fields.fieldName = ''
      this.fields.fieldType = ''
      this.fields.fieldLength = null
      this.fields.fieldDefault = null
      this.fields.asDefined = null
      this.fields.fieldNull = false
      this.fields.fieldIndex = null
      this.fields.fieldAttribute = null
      this.fields.fieldIncrement = false
    },

    validate() {
      var error = 0;

      if (this.fields.fieldName == "" || this.fields.fieldName == undefined) {
        this.errors['fieldName'] = this.$t('database.add.error.fieldName');
        error++
      } 
      
      if (this.fields.fieldType == "" || this.fields.fieldType == undefined) {
        this.errors['fieldType'] = this.$t('database.add.error.fieldType');
        error++
      }

      if ((this.fields.fieldLength == null || this.fields.fieldLength == "" || this.fields.fieldLength == undefined) && (this.fields.fieldType == "varchar" || this.fields.fieldType == "char")) {
        this.errors['fieldLength'] = this.$t('database.add.error.fieldLength');
        error++
      }

      return error
    },

    editField(item, index) {
      this.editDialog = true
      this.edit = {
        ...item
      },

      this.willEdit = index
    },

    saveEdit() {
      let isFieldModified = 0;

      for (const [index, field] of Object.entries(Object.entries(this.databaseData.fields.currentFields[this.willEdit]))) {
        console.log(field[1]);
        console.log(this.edit[field[0]]);
        if(field[1] !== this.edit[field[0]]) {
          isFieldModified++
        }
      }

      if (isFieldModified > 0) {
        if (this.databaseData.fields.currentFields[this.willEdit].fieldName !== this.edit.fieldName) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "RENAME",
            current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
            new: this.edit.fieldName
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldType !== this.edit.fieldType) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_TYPE",
            fieldType: this.databaseData.fields.currentFields[this.willEdit].fieldLength,
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldLength !== this.edit.fieldLength) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_LENGTH",
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
              new: this.edit.fieldName
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
            fieldLength: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldLength,
              new: this.edit.fieldLength,
            },
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldDefault !== this.edit.fieldDefault) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_DEFAULT",
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
              new: this.edit.fieldName
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
            fieldDefault: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldDefault,
              new: this.edit.fieldDefault
            },
            asDefined: {
              current: this.databaseData.fields.currentFields[this.willEdit].asDefined,
              new: this.edit.asDefined
            },
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldNull !== this.edit.fieldNull) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_NULL",
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
              new: this.edit.fieldName
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
            fieldNull: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldNull,
              new: this.edit.fieldNull
            },
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldIndex !== this.edit.fieldIndex) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_INDEX",
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
              new: this.edit.fieldName
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
            fieldIndex: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldIndex,
              new: this.edit.fieldIndex
            },
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldAttribute !== this.edit.fieldAttribute) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_ATTRIBUTE",
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
              new: this.edit.fieldName
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
            fieldAttribute: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldAttribute,
              new: this.edit.fieldAttribute
            },
          })
        }

        if (this.databaseData.fields.currentFields[this.willEdit].fieldIncrement !== this.edit.fieldIncrement) {
          this.databaseData.fields.modifiedFields.push({
            modifyType: "UPDATE_INCREMENT",
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldName,
              new: this.edit.fieldName
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldType,
              new: this.edit.fieldType
            },
            fieldIncrement: {
              current: this.databaseData.fields.currentFields[this.willEdit].fieldIncrement,
              new: this.edit.fieldIncrement
            },
          })
        }
      }

      this.databaseData.fields.currentFields[this.willEdit] = {
        ...this.edit
      }
    },

    dropField(index) {
      this.deleteDialog = true
      this.willDelete = index
    },

  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}
.vs-select--options {
  z-index: 99999 !important;
}
</style>

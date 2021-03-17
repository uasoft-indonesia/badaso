<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('database.add.title') }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="databaseData.table"
              size="6"
              :label="$t('database.add.field.table')"
              :placeholder="$t('database.add.field.table')"
              required
              :alert="errors.name"
            >
            </badaso-text>
          </vs-row>
          <vs-row>
            <span style="color: rgba(var(--vs-danger),1); padding: 0 15px" v-if="errors.tableName">{{ errors.tableName }}</span>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('database.add.row.title') }}</h3>
          </div>
          <vs-row>
            <vs-col col-lg="12" style="overflow-x: auto">
              <table class="table">
                <thead>
                  <th style="word-wrap: nowrap;"></th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldName') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldType') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldLength') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldDefault') }}</th>
                  <th style="min-width: 50px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldNull') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldIndex') }}</th>
                  <th style="min-width: 200px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldAttribute') }}</th>
                  <th style="min-width: 100px; word-wrap: nowrap;">{{ $t('database.add.row.field.fieldIncrement') }}</th>
                  <th></th>
                </thead>
                <draggable v-model="databaseData.rows" tag="tbody">
                  <tr :key="index" v-for="(item, index) in databaseData.rows" >
                    <td>
                      <vs-icon
                        icon="drag_indicator"
                        class="drag_icon"
                      ></vs-icon>
                    </td>
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
                  </tr>
                </draggable>
                <tr>
                  <td colspan="2">
                    <vs-input
                      class="inputx"
                      :placeholder="$t('database.add.row.field.fieldName')"
                      v-model="fields.fieldName"
                    />
                    <span style="color: rgba(var(--vs-danger),1)" v-if="errors.fieldName">{{ errors.fieldName }}</span>
                  </td>
                  <td>
                    <vs-select class="selectExample" v-model="fields.fieldType">
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
                      :placeholder="$t('database.add.row.field.fieldLength')"
                      v-model="fields.fieldLength"
                    />
                    <span style="color: rgba(var(--vs-danger),1)" v-if="errors.fieldLength">{{ errors.fieldLength }}</span>
                  </td>
                  <td>
                    <vs-select class="selectExample mb-2" v-model="fields.fieldDefault">
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
                    />
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldNull"
                      class="mb-1"
                      style="justify-content: start;"
                      ></vs-checkbox>
                  </td>
                  <td>
                    <vs-select class="selectExample" v-model="fields.fieldIndex">
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldIndexList"
                      />
                    </vs-select>
                  </td>
                  <td>
                    <vs-select class="selectExample" v-model="fields.fieldAttribute">
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
                      ></vs-checkbox>
                  </td>
                  <td>
                    <vs-button color="primary" type="relief" @click="setField()">
                      {{ $t('database.add.row.field.add') }}
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
                <vs-icon icon="save"></vs-icon> {{ $t('database.add.button') }}
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
    errors: {
      fieldName: "",
      fieldType: "",
      tableName: "",
      fieldLength: ""
    },
    breadcrumb: [],
    fields: {
      fieldName: "",
      fieldType: "",
      fieldNull: false,
      fieldIncrement: false,
      fieldLength: null,
      fieldDefault: null,
      fieldIndex: null,
      fieldAttribute: null,
      asDefined: null
    },
    databaseData: {
      table: "",
      rows: []
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
  },
  methods: {
    submitForm() {
      if (this.databaseData.table == '' || this.databaseData.table == undefined) {
        this.errors['tableName'] = this.$t('database.add.error.tableName');
      } else {
        this.$vs.loading({
          type: "sound",
        });
        this.$api.database
          .add(this.databaseData)
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
        this.errors['fieldName'] = null
        this.errors['fieldType'] = null
        this.errors['fieldLength'] = null

        this.databaseData.rows.push({
          fieldName: this.fields.fieldName,
          fieldType: this.fields.fieldType,
          fieldLength: this.fields.fieldLength ? this.fields.fieldLength : null,
          fieldDefault: this.fields.fieldDefault == 'as_defined' ? this.fields.asDefined ? this.fields.asDefined : null : null,
          fieldNull: this.fields.fieldNull,
          fieldIndex: this.fields.fieldIndex ? this.fields.fieldIndex : null,
          fieldAttribute: this.fields.fieldAttribute,
          fieldIncrement: this.fields.fieldIncrement
        });
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
    }
  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}
</style>

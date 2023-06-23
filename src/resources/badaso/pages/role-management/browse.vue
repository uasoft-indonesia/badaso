<template>
  <div>
    <badaso-breadcrumb-hover full>
      <template slot="action">
        <download-excel
          :data="roles"
          :fields="fieldsForExcel"
          :worksheet="'Role Management'"
          :name="'Role Management ' + '.xls'"
          class="crud-generated__excel-button"
        >
          <badaso-dropdown-item icon="file_upload">
            {{ $t("action.exportToExcel") }}
          </badaso-dropdown-item>
        </download-excel>
        <badaso-dropdown-item icon="file_upload" @click="generatePdf">
          {{ $t("action.exportToPdf") }}
        </badaso-dropdown-item>
        <badaso-dropdown-item icon="add" :to="{ name: 'RoleManagementAdd' }">
          {{ $t("action.add") }}
        </badaso-dropdown-item>
        <badaso-dropdown-item
          icon="delete_sweep"
          v-if="selected.length > 0 && $helper.isAllowed('delete_roles')"
          @click.stop
          @click="confirmDeleteMultiple"
        >
          {{ $t("action.bulkDelete") }}
        </badaso-dropdown-item>
      </template>
    </badaso-breadcrumb-hover>
    <vs-row v-if="$helper.isAllowed('browse_roles')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("role.title") }}</h3>
          </div>
          <div>
            <badaso-table
              multiple
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="roles"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('role.footer.descriptionTitle')"
              :description-connector="$t('role.footer.descriptionConnector')"
              :description-body="$t('role.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="name"> {{ $t("role.header.name") }} </vs-th>
                <vs-th sort-key="displayName">
                  {{ $t("role.header.displayName") }}
                </vs-th>
                <vs-th sort-key="description">
                  {{ $t("role.header.description") }}
                </vs-th>
                <vs-th> {{ $t("role.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].name">
                    {{ data[indextr].name }}
                  </vs-td>
                  <vs-td :data="data[indextr].displayName">
                    {{ data[indextr].displayName }}
                  </vs-td>
                  <vs-td :data="data[indextr].description">
                    {{ data[indextr].description }}
                  </vs-td>
                  <vs-td class="badaso-table__td">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="visibility"
                          :to="{
                            name: 'RoleManagementRead',
                            params: { id: data[indextr].id },
                          }"
                        >
                          Detail
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="list"
                          :to="{
                            name: 'RoleManagementPermissions',
                            params: { id: data[indextr].id },
                          }"
                        >
                          Permission
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="edit"
                          :to="{
                            name: 'RoleManagementEdit',
                            params: { id: data[indextr].id },
                          }"
                          v-if="$helper.isAllowed('edit_roles')"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="confirmDelete(data[indextr].id)"
                          v-if="$helper.isAllowed('delete_roles')"
                        >
                          Delete
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </badaso-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("role.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import downloadExcel from "vue-json-excel";
import jsPDF from "jspdf";
import "jspdf-autotable";
export default {
  name: "RoleManagementBrowse",
  components: { downloadExcel },
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    roles: [],
    willDeleteId: null,
    fieldsForExcel: {},
    fieldsForPdf: [],
    dataType: {
      fields: ["id", "name", "display_name", "description"],
    },
  }),
  mounted() {
    this.getRoleList();
  },
  methods: {
    confirmDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteRole,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.bulkDeleteRole,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    getRoleList() {
      this.$openLoader();
      this.$api.badasoRole
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.selected = [];
          this.roles = response.data.roles;
          this.prepareExcelExporter();
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteRole() {
      this.$openLoader();
      this.$api.badasoRole
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$closeLoader();
          this.getRoleList();
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    bulkDeleteRole() {
      const ids = this.selected.map((item) => item.id);
      this.$openLoader();
      this.$api.badasoRole
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$closeLoader();
          this.getRoleList();
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    prepareExcelExporter() {
      for (const iterator of this.dataType.fields) {
        let field = iterator;
        if (field.includes("_")) {
          field = field.split("_");
          field =
            field[0].charAt(0).toUpperCase() +
            field[0].slice(1) +
            " " +
            field[1].charAt(0).toUpperCase() +
            field[1].slice(1);
        }

        field = field.charAt(0).toUpperCase() + field.slice(1);

        this.fieldsForExcel[field] =
          this.$caseConvert.stringSnakeToCamel(iterator);
      }

      for (let iterator of this.dataType.fields) {
        if (iterator.includes("_")) {
          iterator = iterator.split("_");
          iterator =
            iterator[0] +
            " " +
            iterator[1].charAt(0).toUpperCase() +
            iterator[1].slice(1);
        }

        const string = this.$caseConvert.stringSnakeToCamel(iterator);
        this.fieldsForPdf.push(
          string.charAt(0).toUpperCase() + string.slice(1)
        );
      }
    },
    generatePdf() {
      let data = this.roles;

      const fields = [];

      for (const iterator in this.dataType.fields) {
        const string = this.$caseConvert.stringSnakeToCamel(
          this.dataType.fields[iterator]
        );
        fields.push(string);
      }

      data.map((value) => {
        for (const iterator in value) {
          if (!fields.includes(iterator)) {
            delete value[iterator];
          }
        }
        return value;
      });
      const result = data.map(Object.values);

      // eslint-disable-next-line new-cap
      const doc = new jsPDF("l");

      // Dynamic table title
      doc.setFont("helvetica", "bold");
      doc.setFontSize(28);
      doc.text(this.$t("role.title"), 149, 20, "center");

      // Data table
      doc.autoTable({
        head: [this.fieldsForPdf],
        body: result,
        startY: 30,
        // Default for all columns
        styles: { valign: "middle" },
        headStyles: { fillColor: [6, 187, 211] },
        // Override the default above for the text column
        columnStyles: { text: { cellWidth: "wrap" } },
      });

      // Output Table title and data table in new tab
      const output = doc.output("blob");
      data = window.URL.createObjectURL(output);
      window.open(data, "_blank");

      setTimeout(function () {
        // For Firefox it is necessary to delay revoking the ObjectURL
        window.URL.revokeObjectURL(data);
      }, 100);
    },
  },
};
</script>

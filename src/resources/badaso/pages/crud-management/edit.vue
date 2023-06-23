<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_crud_data')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>
              {{
                $t("crud.edit.title.table", {
                  tableName: $route.params.tableName,
                })
              }}
            </h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="crudData.name"
              size="6"
              :label="$t('crud.edit.field.tableName.title')"
              :placeholder="$t('crud.edit.field.tableName.title')"
              required
              readonly
            ></badaso-text>
            <badaso-text
              v-model="crudData.displayNameSingular"
              size="6"
              :label="$t('crud.edit.field.displayNameSingular.title')"
              required
              :placeholder="
                $t('crud.edit.field.displayNameSingular.placeholder')
              "
              :alert="errors.displayNameSingular"
            ></badaso-text>
            <badaso-text
              v-model="crudData.displayNamePlural"
              size="6"
              :label="$t('crud.edit.field.displayNamePlural.title')"
              :placeholder="$t('crud.edit.field.displayNamePlural.placeholder')"
              :alert="errors.displayNamePlural"
            ></badaso-text>
            <badaso-text
              v-model="crudData.slug"
              size="6"
              :label="$t('crud.edit.field.urlSlug.title')"
              :placeholder="$t('crud.edit.field.urlSlug.placeholder')"
              required
              :alert="errors.slug"
              readonly
            ></badaso-text>
            <badaso-text
              v-model="crudData.icon"
              size="6"
              :label="$t('crud.edit.field.icon.title')"
              :placeholder="$t('crud.edit.field.icon.placeholder')"
              :additionalInfo="
                $t('menu.builder.popup.edit.field.icon.description')
              "
              :alert="errors.icon"
            ></badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <badaso-collapse>
          <badaso-collapse-item>
            <h3 slot="header">{{ $t("crud.edit.title.advance") }}</h3>
            <vs-row>
              <badaso-text
                v-model="crudData.modelName"
                size="6"
                :label="$t('crud.edit.field.modelName.title')"
                :placeholder="$t('crud.edit.field.modelName.placeholder')"
                :alert="errors.modelName"
                :tooltip="$t('crud.help.modelName')"
              ></badaso-text>
              <badaso-text
                v-model="crudData.controller"
                size="6"
                :label="$t('crud.edit.field.controllerName.title')"
                :placeholder="$t('crud.edit.field.controllerName.placeholder')"
                :alert="errors.controller"
                :tooltip="$t('crud.help.controllerName')"
              ></badaso-text>
              <badaso-switch
                size="4"
                v-model="crudData.generatePermissions"
                :label="$t('crud.edit.field.generatePermissions')"
                :alert="errors.generatePermissions"
                :tooltip="$t('crud.help.generatePermissions')"
              ></badaso-switch>
              <badaso-switch
                size="4"
                v-model="crudData.serverSide"
                :label="$t('crud.edit.field.serverSide')"
                :alert="errors.serverSide"
                :tooltip="$t('crud.help.serverSide')"
              ></badaso-switch>
              <badaso-switch
                size="4"
                v-model="crudData.createSoftDelete"
                :label="$t('crud.add.field.createSoftDelete')"
                :alert="errors.createSoftDelete"
                :tooltip="$t('crud.help.createSoftDelete')"
              ></badaso-switch>
              <vs-col vs-lg="6" />
              <badaso-select
                v-model="crudData.orderColumn"
                size="4"
                :label="$t('crud.edit.field.orderColumn.title')"
                :placeholder="$t('crud.edit.field.orderColumn.placeholder')"
                :items="crudData.rows"
                :alert="errors.orderColumn"
              ></badaso-select>
              <badaso-select
                v-model="crudData.orderDisplayColumn"
                size="4"
                :label="$t('crud.edit.field.orderDisplayColumn.title')"
                :placeholder="
                  $t('crud.edit.field.orderDisplayColumn.placeholder')
                "
                :items="crudData.rows"
                :alert="errors.orderDisplayColumn"
                :additionalInfo="
                  $t('crud.edit.field.orderDisplayColumn.description')
                "
              ></badaso-select>
              <badaso-select
                v-model="crudData.orderDirection"
                size="4"
                :label="$t('crud.edit.field.orderDirection.title')"
                :placeholder="$t('crud.edit.field.orderDirection.placeholder')"
                :items="orderDirections"
                :alert="errors.orderDirection"
              ></badaso-select>
              <badaso-hidden
                v-model="crudData.defaultServerSideSearchField"
                size="3"
                :label="
                  $t('crud.edit.field.defaultServerSideSearchField.title')
                "
                :placeholder="
                  $t('crud.edit.field.defaultServerSideSearchField.placeholder')
                "
                :items="crudData.rows"
                :alert="errors.defaultServerSideSearchField"
              ></badaso-hidden>
              <vs-col class="crud-management__notification-title">
                <label class="crud-management__label"
                  >{{ $t("crud.edit.field.activeEventNotification.title") }}
                  <vs-tooltip
                    :text="$t('crud.help.activeEventNotificationTitle')"
                  >
                    <vs-icon
                      icon="help_outline"
                      size="16px"
                      color="#A5A5A5"
                    ></vs-icon>
                  </vs-tooltip>
                </label>
              </vs-col>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                    v-model="onCreate"
                    >{{
                      $t(
                        "crud.edit.field.activeEventNotification.label.onCreate"
                      )
                    }}</vs-checkbox
                  >
                </vs-col>
                <vs-col v-if="onCreate">
                  <badaso-text
                    v-model="onCreateTitle"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onCreateTitle'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onCreateTitle'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                  <badaso-text
                    v-model="onCreateMessage"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onCreateMessage'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onCreateMessage'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                </vs-col>
              </vs-row>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                    v-model="onRead"
                    >{{
                      $t("crud.edit.field.activeEventNotification.label.onRead")
                    }}</vs-checkbox
                  >
                </vs-col>
                <vs-col v-if="onRead">
                  <badaso-text
                    v-model="onReadTitle"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onReadTitle'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onReadTitle'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                  <badaso-text
                    v-model="onReadMessage"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onReadMessage'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onReadMessage'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                </vs-col>
              </vs-row>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                    v-model="onUpdate"
                    >{{
                      $t(
                        "crud.edit.field.activeEventNotification.label.onUpdate"
                      )
                    }}</vs-checkbox
                  >
                </vs-col>
                <vs-col v-if="onUpdate">
                  <badaso-text
                    v-model="onUpdateTitle"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onUpdateTitle'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onUpdateTitle'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                  <badaso-text
                    v-model="onUpdateMessage"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onUpdateMessage'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onUpdateMessage'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                </vs-col>
              </vs-row>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                    v-model="onDelete"
                    >{{
                      $t(
                        "crud.edit.field.activeEventNotification.label.onDelete"
                      )
                    }}</vs-checkbox
                  >
                </vs-col>
                <vs-col v-if="onDelete">
                  <badaso-text
                    v-model="onDeleteTitle"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onDeleteTitle'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onDeleteTitle'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                  <badaso-text
                    v-model="onDeleteMessage"
                    :label="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onDeleteMessage'
                      )
                    "
                    :placeholder="
                      $t(
                        'crud.edit.field.activeEventNotification.label.onDeleteMessage'
                      )
                    "
                    :additionalInfo="null"
                    :alert="errors.icon"
                  ></badaso-text>
                </vs-col>
              </vs-row>

              <badaso-textarea
                size="12"
                :label="$t('crud.edit.field.description.title')"
                :placeholder="$t('crud.edit.field.description.placeholder')"
                v-model="crudData.description"
                :alert="errors.description"
              >
              </badaso-textarea>
            </vs-row>
          </badaso-collapse-item>
        </badaso-collapse>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>
              {{
                $t("crud.edit.title.field", {
                  tableName: $route.params.tableName,
                })
              }}
            </h3>
          </div>
          <vs-row class="hide-for-mobile">
            <vs-col col-lg="12">
              <table class="badaso-table">
                <thead>
                  <th class="badaso-table__th"></th>
                  <th class="badaso-table__th">
                    {{ $t("crud.edit.header.field") }}
                  </th>
                  <th class="badaso-table__th">
                    {{ $t("crud.edit.header.visibility") }}
                  </th>
                  <th class="badaso-table__th badaso-table__th--sm">
                    {{ $t("crud.edit.header.inputType") }}
                  </th>
                  <th class="badaso-table__th--sm">
                    {{ $t("crud.edit.header.displayName") }}
                  </th>
                  <th class="badaso-table__th--sm">
                    {{ $t("crud.edit.header.optionalDetails") }}
                  </th>
                </thead>
                <draggable v-model="crudData.rows" tag="tbody">
                  <tr :key="index" v-for="(field, index) in crudData.rows">
                    <td>
                      <vs-icon
                        icon="drag_indicator"
                        class="is-draggable"
                      ></vs-icon>
                    </td>
                    <td :data="field.field">
                      <strong>{{ field.field }}</strong>
                      <br />
                      <span>
                        {{ $t("crud.edit.body.type") }} {{ field.type }}
                      </span>
                      <br />
                      <span>
                        {{ $t("crud.edit.body.required.title") }}
                        <span v-if="field.required">{{
                          $t("crud.edit.body.required.yes")
                        }}</span
                        ><span v-else>{{
                          $t("crud.edit.body.required.no")
                        }}</span>
                      </span>
                      <br />
                      <span
                        class="is-error"
                        v-for="err in errors[`rows.${index}.field`]"
                        :key="err"
                        >{{ err }}</span
                      >
                    </td>
                    <td>
                      <vs-checkbox
                        v-model="field.browse"
                        class="crud-management__field-visibility"
                        >{{ $t("crud.edit.body.browse") }}</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.read"
                        class="crud-management__field-visibility"
                        >{{ $t("crud.edit.body.read") }}</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.edit"
                        class="crud-management__field-visibility"
                        >{{ $t("crud.edit.body.edit") }}</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.add"
                        class="crud-management__field-visibility"
                        >{{ $t("crud.edit.body.add") }}</vs-checkbox
                      >
                      <vs-checkbox
                        v-model="field.delete"
                        class="crud-management__field-visibility"
                        >{{ $t("crud.edit.body.delete") }}</vs-checkbox
                      >
                    </td>
                    <td>
                      <badaso-select
                        size="12"
                        v-model="field.type"
                        :items="componentList"
                        :alert="errors[`rows.${index}.type`]"
                      ></badaso-select>
                    </td>
                    <td>
                      <badaso-text
                        :placeholder="$t('crud.add.body.displayName')"
                        v-model="field.displayName"
                        :alert="errors[`rows.${index}.displayName`]"
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
                        v-if="field.type === 'relation'"
                        >{{ $t("crud.edit.body.setRelation") }}</vs-button
                      >
                      <vs-popup
                        :title="$t('crud.edit.body.setRelation')"
                        :active.sync="field.setRelation"
                      >
                        <vs-row>
                          <badaso-select
                            size="12"
                            v-model="relation.relationType"
                            :items="
                              field.relationType
                                ? relationOtherTypes
                                : relationTypes
                            "
                            :label="$t('crud.edit.body.relationType')"
                          ></badaso-select>
                          <vs-col
                            vs-lg="12"
                            class="crud-management__relation-destination"
                          >
                            <vs-select
                              :label="$t('crud.edit.body.destinationTable')"
                              width="100%"
                              v-model="relation.destinationTable"
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
                            :label="$t('crud.edit.body.destinationTableColumn')"
                          ></badaso-select>
                          <badaso-select
                            size="12"
                            v-model="relation.destinationTableDisplayColumn"
                            :items="destinationTableColumns"
                            :label="
                              $t('crud.edit.body.destinationTableDisplayColumn')
                            "
                          ></badaso-select>
                        </vs-row>
                        <vs-row vs-type="flex" vs-justify="space-between">
                          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                            <vs-button
                              class="crud-management__button--block"
                              color="danger"
                              @click="field.setRelation = false"
                              type="relief"
                              >{{
                                $t("crud.edit.body.cancelRelation")
                              }}</vs-button
                            >
                          </vs-col>
                          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                            <vs-button
                              class="crud-management__button--block"
                              color="primary"
                              @click="saveRelation(field)"
                              type="relief"
                              >{{
                                $t("crud.edit.body.saveRelation")
                              }}</vs-button
                            >
                          </vs-col>
                        </vs-row>
                      </vs-popup>
                      <vs-button
                        color="danger"
                        type="relief"
                        @click="dropItemOtherRelation(index)"
                        v-if="
                          field.relationType == 'belongs_to_many' ||
                          field.relationType == 'has_one' ||
                          field.relationType == 'has_many'
                        "
                      >
                        <vs-icon icon="delete"></vs-icon>
                      </vs-button>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <vs-button
                      size="12"
                      color="primary"
                      type="relief"
                      @click.stop
                      @click="openRelationSetupManytomany()"
                      >{{ $t("crud.edit.body.setOtherRelation") }}</vs-button
                    >
                    <vs-popup
                      :title="$t('crud.edit.body.setOtherRelation')"
                      :active.sync="setOtherRelation"
                    >
                      <vs-row>
                        <badaso-select
                          size="12"
                          v-model="otherRelation.relationType"
                          :items="relationOtherTypes"
                          :label="$t('crud.edit.body.relationType')"
                        ></badaso-select>
                        <vs-col
                          vs-lg="12"
                          class="crud-management__relation-destination"
                        >
                          <vs-select
                            :label="$t('crud.edit.body.destinationTable')"
                            width="100%"
                            v-model="otherRelation.destinationTable"
                            @input="changeTableManytomany"
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
                          v-model="otherRelation.destinationTableColumn"
                          :items="destinationTableColumns"
                          :label="$t('crud.edit.body.destinationTableColumn')"
                        ></badaso-select>
                        <badaso-select
                          size="12"
                          v-model="otherRelation.destinationTableDisplayColumn"
                          :items="destinationTableColumns"
                          :label="
                            $t('crud.edit.body.destinationTableDisplayColumn')
                          "
                        ></badaso-select>
                        <vs-col vs-lg="14">
                          <badaso-collapse>
                            <badaso-collapse-item>
                              <h3 slot="header">
                                {{ $t("crud.add.title.advance") }}
                              </h3>
                              <vs-row>
                                <vs-col
                                  vs-lg="12"
                                  class="crud-management__relation-destination"
                                >
                                  <vs-select
                                    :label="
                                      $t(
                                        'crud.add.body.destinationTableManytomany'
                                      )
                                    "
                                    width="100%"
                                    v-model="
                                      relationManytomanyAdvance.destinationTableManytomany
                                    "
                                  >
                                    <vs-select-item
                                      :key="index"
                                      :value="item.value ? item.value : item"
                                      :text="item.label ? item.label : item"
                                      v-for="(item, index) in destinationTables"
                                    />
                                  </vs-select>
                                </vs-col>
                              </vs-row>
                            </badaso-collapse-item>
                          </badaso-collapse>
                        </vs-col>
                      </vs-row>
                      <vs-row vs-type="flex" vs-justify="space-between">
                        <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                          <vs-button
                            class="crud-management__button--block"
                            color="danger"
                            @click="cancelRelationManytomany"
                            type="relief"
                            >{{
                              $t("crud.edit.body.cancelRelation")
                            }}</vs-button
                          >
                        </vs-col>
                        <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                          <vs-button
                            class="crud-management__button--block"
                            color="primary"
                            @click="saveRelationManytomany()"
                            type="relief"
                            >{{ $t("crud.edit.body.saveRelation") }}</vs-button
                          >
                        </vs-col>
                      </vs-row>
                    </vs-popup>
                  </tr>
                </draggable>
              </table>
            </vs-col>
          </vs-row>
          <vs-row class="show-for-mobile">
            <vs-col col-lg="12">
              <draggable v-model="crudData.rows" tag="div">
                <vs-row
                  :key="index"
                  v-for="(field, index) in crudData.rows"
                  class="crud-management__mobile-row"
                >
                  <vs-col col-lg="12" class="mb-2">
                    <table class="badaso-table">
                      <tr>
                        <td>{{ $t("crud.add.header.field") }}</td>
                        <td>
                          <strong>{{ field.field }}</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>{{ $t("crud.add.body.type") }}</td>
                        <td>{{ field.type }}</td>
                      </tr>
                      <tr>
                        <td>{{ $t("crud.add.body.required.title") }}</td>
                        <td>
                          <span v-if="field.required">{{
                            $t("crud.add.body.required.yes")
                          }}</span
                          ><span v-else>{{
                            $t("crud.add.body.required.no")
                          }}</span>
                        </td>
                      </tr>
                      <tr v-if="errors[`rows.${index}.field`]">
                        <td colspan="2">
                          <span
                            class="is-error"
                            v-for="err in errors[`rows.${index}.field`]"
                            :key="err"
                            >{{ err }}</span
                          >
                        </td>
                      </tr>
                      <tr>
                        <td>{{ $t("crud.add.header.visibility") }}</td>
                        <td>
                          <vs-checkbox
                            v-model="field.browse"
                            class="crud-management__field-visibility"
                            >{{ $t("crud.add.body.browse") }}</vs-checkbox
                          >
                          <vs-checkbox
                            v-model="field.read"
                            class="crud-management__field-visibility"
                            >{{ $t("crud.add.body.read") }}</vs-checkbox
                          >
                          <vs-checkbox
                            v-model="field.edit"
                            class="crud-management__field-visibility"
                            >{{ $t("crud.add.body.edit") }}</vs-checkbox
                          >
                          <vs-checkbox
                            v-model="field.add"
                            class="crud-management__field-visibility"
                            >{{ $t("crud.add.body.add") }}</vs-checkbox
                          >
                          <vs-checkbox
                            v-model="field.delete"
                            class="crud-management__field-visibility"
                            >{{ $t("crud.add.body.delete") }}</vs-checkbox
                          >
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          {{ $t("crud.add.header.inputType") }}
                          <badaso-select
                            size="12"
                            v-model="field.type"
                            :items="componentList"
                            :alert="errors[`rows.${index}.type`]"
                          ></badaso-select>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          {{ $t("crud.add.header.displayName") }}
                          <badaso-text
                            :placeholder="$t('crud.add.body.displayName')"
                            v-model="field.displayName"
                            :alert="errors[`rows.${index}.displayName`]"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          {{ $t("crud.add.header.optionalDetails") }}
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
                            v-else-if="field.relationType !== 'belongs_to_many'"
                            >{{ $t("crud.add.body.setRelation") }}</vs-button
                          >
                          <vs-popup
                            class="holamundo"
                            :title="$t('crud.add.body.setRelation')"
                            :active.sync="field.setRelation"
                          >
                            <vs-row>
                              <badaso-select
                                size="12"
                                v-model="relation.relationType"
                                :items="relationTypes"
                                :label="$t('crud.add.body.relationType')"
                              ></badaso-select>
                              <vs-col
                                vs-lg="12"
                                class="crud-management__relation-destination"
                              >
                                <vs-select
                                  :label="$t('crud.add.body.destinationTable')"
                                  width="100%"
                                  v-model="relation.destinationTable"
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
                                :label="
                                  $t('crud.add.body.destinationTableColumn')
                                "
                              ></badaso-select>
                              <badaso-select
                                size="12"
                                v-model="relation.destinationTableDisplayColumn"
                                :items="destinationTableColumns"
                                :label="
                                  $t(
                                    'crud.add.body.destinationTableDisplayColumn'
                                  )
                                "
                              ></badaso-select>
                            </vs-row>
                            <vs-row vs-type="flex" vs-justify="space-between">
                              <vs-col
                                vs-lg="2"
                                vs-type="flex"
                                vs-align="flex-end"
                              >
                                <vs-button
                                  color="primary"
                                  @click="saveRelation(field)"
                                  >{{
                                    $t("crud.add.body.saveRelation")
                                  }}</vs-button
                                >
                              </vs-col>
                              <vs-col
                                vs-lg="2"
                                vs-type="flex"
                                vs-align="flex-end"
                              >
                                <vs-button
                                  color="danger"
                                  @click="field.setRelation = false"
                                  >{{
                                    $t("crud.add.body.cancelRelation")
                                  }}</vs-button
                                >
                              </vs-col>
                            </vs-row>
                          </vs-popup>
                        </td>
                      </tr>
                    </table>
                  </vs-col>
                </vs-row>
                <vs-button
                  size="12"
                  color="primary"
                  type="relief"
                  @click.stop
                  @click="openRelationSetupManytomany()"
                  >{{ $t("crud.edit.body.setOtherRelation") }}</vs-button
                >
                <vs-popup
                  class="holamundo"
                  :title="$t('crud.add.body.setRelation')"
                  :active.sync="setOtherRelation"
                >
                  <vs-row>
                    <badaso-select
                      size="12"
                      v-model="otherRelation.relationType"
                      :items="relationOtherTypes"
                      :label="$t('crud.add.body.relationType')"
                    >
                    </badaso-select>
                    <vs-col
                      vs-lg="12"
                      class="crud-management__relation-destination"
                    >
                      <vs-select
                        :label="$t('crud.add.body.destinationTable')"
                        width="100%"
                        v-model="otherRelation.destinationTable"
                        @input="changeTableManytomany"
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
                      v-model="otherRelation.destinationTableColumn"
                      :items="destinationTableColumns"
                      :label="$t('crud.add.body.destinationTableColumn')"
                    ></badaso-select>
                    <badaso-select
                      size="12"
                      v-model="otherRelation.destinationTableDisplayColumn"
                      :items="destinationTableColumns"
                      :label="$t('crud.add.body.destinationTableDisplayColumn')"
                    ></badaso-select>
                    <vs-col vs-lg="14">
                      <badaso-collapse>
                        <badaso-collapse-item>
                          <h3 slot="header">
                            {{ $t("crud.add.title.advance") }}
                          </h3>
                          <vs-row>
                            <vs-col
                              vs-lg="12"
                              class="crud-management__relation-destination"
                            >
                              <vs-select
                                :label="
                                  $t('crud.add.body.destinationTableManytomany')
                                "
                                width="100%"
                                v-model="
                                  relationManytomanyAdvance.destinationTableManytomany
                                "
                              >
                                <vs-select-item
                                  :key="index"
                                  :value="item.value ? item.value : item"
                                  :text="item.label ? item.label : item"
                                  v-for="(item, index) in destinationTables"
                                />
                              </vs-select>
                            </vs-col>
                          </vs-row>
                        </badaso-collapse-item>
                      </badaso-collapse>
                    </vs-col>
                  </vs-row>
                  <vs-row vs-type="flex" vs-justify="space-between">
                    <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                      <vs-button
                        color="primary"
                        @click="saveRelationManytomany()"
                        >{{ $t("crud.add.body.saveRelation") }}</vs-button
                      >
                    </vs-col>
                    <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                      <vs-button
                        color="danger"
                        @click="cancelRelationManytomany"
                        >{{ $t("crud.add.body.cancelRelation") }}</vs-button
                      >
                    </vs-col>
                  </vs-row>
                </vs-popup>
              </draggable>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t("crud.edit.button") }}
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
              <h3>{{ $t("crud.warning.notAllowed") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
  name: "CrudManagementEdit",
  components: {
    draggable,
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
      createSoftDelete: false,
      serverSide: false,
      details: "",
      controller: "",
      orderColumn: "",
      orderDisplayColumn: "",
      orderDirection: "",
      notification: [],
      rows: [],
    },
    setOtherRelation: false,
    relationTypes: [],
    relationOtherTypes: [],
    destinationTables: [],
    destinationTableColumns: [],
    relation: {},
    otherRelation: {
      relationType: "",
      destinationTable: "",
      destinationTableColumn: "",
      destinationTableDisplayColumn: "",
    },
    relationManytomanyAdvance: {
      destinationTableManytomany: "",
    },
    onCreate: false,
    onCreateTitle: "",
    onCreateMessage: "",
    onRead: false,
    onReadTitle: "",
    onReadMessage: "",
    onUpdate: false,
    onUpdateTitle: "",
    onUpdateMessage: "",
    onDelete: false,
    onDeleteTitle: "",
    onDeleteMessage: "",
  }),
  computed: {
    componentList: {
      get() {
        return this.$store.getters["badaso/getComponent"];
      },
    },
  },
  mounted() {
    this.orderDirections = [
      {
        label: this.$t("crud.edit.field.orderDirection.value.ascending"),
        value: "ASC",
      },
      {
        label: this.$t("crud.edit.field.orderDirection.value.descending"),
        value: "DESC",
      },
    ];
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
        relationType: field.relationType ? field.relationType : "",
        destinationTable: field.destinationTable ? field.destinationTable : "",
        destinationTableColumn: field.destinationTableColumn
          ? field.destinationTableColumn
          : "",
        destinationTableDisplayColumn: field.destinationTableDisplayColumn
          ? field.destinationTableDisplayColumn
          : "",
        destinationTableDisplayMoreColumn:
          field.destinationTableDisplayMoreColumn
            ? field.destinationTableDisplayMoreColumn
            : "",
      };
      if (field.destinationTable !== "") {
        this.getDestinationTableColumns(field.destinationTable);
      }
    },
    changeTable(table) {
      if (table) {
        this.relation.destinationTableColumn = "";
        this.relation.destinationTableDisplayColumn = "";
        this.getDestinationTableColumns(table);
      }
    },
    saveRelation(field) {
      field.relationType = this.relation.relationType;
      field.destinationTable = this.relation.destinationTable;
      field.destinationTableColumn = this.relation.destinationTableColumn;
      field.destinationTableDisplayColumn =
        this.relation.destinationTableDisplayColumn;
      this.relation = {};
      field.setRelation = false;
    },
    openRelationSetupManytomany() {
      this.setOtherRelation = true;
      this.otherRelation = {
        relationType: this.otherRelation.relationType
          ? this.otherRelation.relationType
          : "",
        destinationTable: this.otherRelation.destinationTable
          ? this.otherRelation.destinationTable
          : "",
        destinationTableColumn: this.otherRelation.destinationTableColumn
          ? this.otherRelation.destinationTableColumn
          : "",
        destinationTableDisplayColumn: this.otherRelation
          .destinationTableDisplayColumn
          ? this.otherRelation.destinationTableDisplayColumn
          : "",
      };
      if (this.otherRelation.destinationTable !== "") {
        this.getDestinationTableColumns(this.otherRelation.destinationTable);
      }
    },
    changeTableManytomany(table) {
      if (table) {
        this.otherRelation.destinationTableColumn = "";
        this.otherRelation.destinationTableDisplayColumn = "";
        this.otherRelation.destinationTableDisplayMoreColumn = "";
        this.getDestinationTableColumns(table);
      }
    },
    saveRelationManytomany() {
      let fieldName = this.relationManytomanyAdvance.destinationTableManytomany
        ? this.relationManytomanyAdvance.destinationTableManytomany
        : this.crudData.name +
          "_" +
          this.otherRelation.destinationTable +
          "_relations";
      let displayName =
        this.crudData.name + " " + this.otherRelation.destinationTable;

      if (this.otherRelation.relationType != "belongs_to_many") {
        fieldName = this.otherRelation.destinationTable;
        displayName = this.otherRelation.destinationTable;
      }

      const existKeyAutomatic = (obj) => obj.field === fieldName;

      if (
        !this.crudData.dataRows.some(existKeyAutomatic) &&
        this.otherRelation.relationType != ""
      ) {
        this.crudData.dataRows.push({
          field: fieldName,
          type: "relation",
          displayName: this.$helper.generateDisplayName(displayName),
          required: 0,
          browse: 1,
          read: 1,
          edit: 1,
          add: 1,
          delete: 1,
          details: "{}",
          order: 1,
          relation: this.otherRelation,
          setRelation: false,
        });
        this.crudData.rows.push({
          field: fieldName,
          type: "relation",
          displayName: this.$helper.generateDisplayName(displayName),
          required: 0,
          browse: 1,
          read: 1,
          edit: 1,
          add: 1,
          delete: 1,
          details: "{}",
          order: 1,
          relationType: this.otherRelation.relationType
            ? this.otherRelation.relationType
            : "",
          destinationTable: this.otherRelation.destinationTable
            ? this.otherRelation.destinationTable
            : "",
          destinationTableColumn: this.otherRelation.destinationTableColumn
            ? this.otherRelation.destinationTableColumn
            : "",
          destinationTableDisplayColumn: this.otherRelation
            .destinationTableDisplayColumn
            ? this.otherRelation.destinationTableDisplayColumn
            : "",
          setRelation: false,
        });
      }
      this.setOtherRelation = false;
      this.otherRelation = {};
      this.relationManytomanyAdvance.destinationTableManytomany = "";
    },
    cancelRelationManytomany() {
      this.setOtherRelation = false;
      this.otherRelation.relationType = "";
      this.otherRelation.destinationTable = "";
      this.otherRelation.destinationTableColumn = "";
      this.otherRelation.destinationTableDisplayColumn = "";
      this.relationManytomanyAdvance.destinationTableManytomany = "";
    },
    dataNotificationEventHandle() {
      if (!Array.isArray(this.crudData.notification)) return;

      this.crudData.notification = this.crudData.notification.map(
        (item, index) => {
          const { event } = item;
          const notificationMessageTitle = this[`${event}Title`];
          const notificationMessage = this[`${event}Message`];

          if (
            notificationMessageTitle != null &&
            notificationMessageTitle != ""
          ) {
            item.notificationMessageTitle = notificationMessageTitle;
          }
          if (notificationMessage != null && notificationMessage != "") {
            item.notificationMessage = notificationMessage;
          }

          return item;
        }
      );
    },
    submitForm() {
      this.dataNotificationEventHandle();
      this.errors = {};
      this.$openLoader();
      this.$api.badasoCrud
        .edit(this.crudData)
        .then((response) => {
          this.$closeLoader();
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_USER");
          this.$router.push({ name: "CrudManagementBrowse" });
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
    getTableDetail() {
      this.$openLoader();
      this.$api.badasoCrud
        .read({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          const crudData = { ...response.data.crud };
          crudData.icon = crudData.icon ? crudData.icon : "";
          crudData.modelName = crudData.modelName ? crudData.modelName : "";
          crudData.policyName = crudData.policyName ? crudData.policyName : "";
          crudData.description = crudData.description
            ? crudData.description
            : "";
          crudData.generatePermissions = crudData.generatePermissions == 1;
          crudData.serverSide = crudData.serverSide == 1;
          crudData.controller = crudData.controller ? crudData.controller : "";
          crudData.orderColumn = crudData.orderColumn
            ? crudData.orderColumn
            : "";
          crudData.orderDisplayColumn = crudData.orderDisplayColumn
            ? crudData.orderDisplayColumn
            : "";
          crudData.orderDirection = crudData.orderDirection
            ? crudData.orderDirection
            : "";
          crudData.createSoftDelete = crudData.isSoftDelete
            ? crudData.isSoftDelete
            : false;
          const dataRows = [...crudData.dataRows];
          crudData.rows = dataRows.map((field) => {
            return {
              label: field.field,
              value: field.field,
              field: field.field,
              type: field.type,
              displayName: field.displayName,
              required: field.required == 1,
              browse: field.browse == 1,
              read: field.read == 1,
              edit: field.edit == 1,
              add: field.add == 1,
              delete: field.delete == 1,
              details: field.details,
              relationType: field.relation ? field.relation.relationType : "",
              destinationTable: field.relation
                ? field.relation.destinationTable
                : "",
              destinationTableColumn: field.relation
                ? field.relation.destinationTableColumn
                : "",
              destinationTableDisplayColumn: field.relation
                ? field.relation.destinationTableDisplayColumn
                : "",
              order: field.order,
              setRelation: false,
            };
          });

          const notification = JSON.parse(crudData.notification);
          for (const key in notification) {
            const {
              event,
              notification_message_title: notificationMessageTitle,
              notification_message: notificationMessage,
            } = notification[key];
            this[event] = true;
            if (notificationMessageTitle)
              this[event + "Title"] = notificationMessageTitle;
            if (notificationMessage)
              this[event + "Message"] = notificationMessage;
          }

          this.crudData = { ...crudData };
          this.crudData.notification = notification;
          this.$closeLoader();
        })
        .catch(() => {
          this.$closeLoader();
        });
    },
    getRelationTypes() {
      this.$openLoader();
      this.$api.badasoData
        .tableRelations()
        .then((response) => {
          this.$closeLoader();
          const tableRelations = response.data.tableRelations;
          for (const tableRelation of tableRelations) {
            if (tableRelation.value == "belongs_to") {
              this.relationTypes.push({
                label: tableRelation.label,
                value: tableRelation.value,
              });
            } else {
              this.relationOtherTypes.push({
                label: tableRelation.label,
                value: tableRelation.value,
              });
            }
          }
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
    getDestinationTables() {
      this.$openLoader();
      this.$api.badasoTable
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.destinationTables = response.data.tables;
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
    getDestinationTableColumns(table) {
      this.$openLoader();
      this.$api.badasoTable
        .read({
          table,
        })
        .then((response) => {
          this.$closeLoader();
          this.destinationTableColumns = response.data.tableFields;
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
    onCheckBoxNotificationOnEvent() {
      const notification = [];

      if (this.onCreate)
        notification.push({
          event: "onCreate",
          notificationMessageTitle: null,
          notificationMessage: null,
        });
      if (this.onRead)
        notification.push({
          event: "onRead",
          notificationMessageTitle: null,
          notificationMessage: null,
        });
      if (this.onUpdate)
        notification.push({
          event: "onUpdate",
          notificationMessageTitle: null,
          notificationMessage: null,
        });
      if (this.onDelete)
        notification.push({
          event: "onDelete",
          notificationMessageTitle: null,
          notificationMessage: null,
        });

      this.crudData.notification = notification;
    },
    dropItemOtherRelation(key) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: () => {
          this.$delete(this.crudData.rows, key);
          this.$delete(this.crudData.dataRows, key);
        },
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
  },
};
</script>

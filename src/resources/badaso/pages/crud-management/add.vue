<template>
  <div>
    <badaso-breadcrumb-row />
    <vs-row v-if="$helper.isAllowed('add_crud_data')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>
              {{
                $t("crud.add.title.table", {
                  tableName: $route.params.tableName,
                })
              }}
            </h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="crudData.name"
              size="6"
              :label="$t('crud.add.field.tableName.title')"
              :placeholder="$t('crud.add.field.tableName.title')"
              required
              readonly
              :alert="errors.name"
            />
            <badaso-text
              v-model="crudData.displayNameSingular"
              size="6"
              :label="$t('crud.add.field.displayNameSingular.title')"
              required
              :placeholder="
                $t('crud.add.field.displayNameSingular.placeholder')
              "
              :alert="errors.displayNameSingular"
            />
            <badaso-text
              v-model="crudData.displayNamePlural"
              size="6"
              :label="$t('crud.add.field.displayNamePlural.title')"
              :placeholder="$t('crud.add.field.displayNamePlural.placeholder')"
              :alert="errors.displayNamePlural"
            />
            <badaso-text
              v-model="crudData.slug"
              size="6"
              :label="$t('crud.add.field.urlSlug.title')"
              :placeholder="$t('crud.add.field.urlSlug.placeholder')"
              :alert="errors.slug"
            />
            <badaso-text
              v-model="crudData.icon"
              size="6"
              :label="$t('crud.add.field.icon.title')"
              :placeholder="$t('crud.add.field.icon.placeholder')"
              :additional-info="
                $t('menu.builder.popup.edit.field.icon.description')
              "
              :alert="errors.icon"
            />
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <badaso-collapse>
          <badaso-collapse-item>
            <h3 slot="header">
              {{ $t("crud.add.title.advance") }}
            </h3>
            <vs-row>
              <badaso-text
                v-model="crudData.modelName"
                size="6"
                :label="$t('crud.add.field.modelName.title')"
                :placeholder="$t('crud.add.field.modelName.placeholder')"
                :alert="errors.modelName"
                :tooltip="$t('crud.help.modelName')"
              />
              <badaso-text
                v-model="crudData.controller"
                size="6"
                :label="$t('crud.add.field.controllerName.title')"
                :placeholder="$t('crud.add.field.controllerName.placeholder')"
                :alert="errors.controller"
                :tooltip="$t('crud.help.controllerName')"
              />
              <badaso-switch
                v-model="crudData.generatePermissions"
                size="4"
                :label="$t('crud.add.field.generatePermissions')"
                :alert="errors.generatePermissions"
                :tooltip="$t('crud.help.generatePermissions')"
              />
              <badaso-switch
                v-model="crudData.serverSide"
                size="4"
                :label="$t('crud.add.field.serverSide')"
                :alert="errors.serverSide"
                :tooltip="$t('crud.help.serverSide')"
              />
              <badaso-switch
                v-model="crudData.createSoftDelete"
                size="4"
                :label="$t('crud.add.field.createSoftDelete')"
                :alert="errors.createSoftDelete"
                :tooltip="$t('crud.help.createSoftDelete')"
              />
              <badaso-select
                v-model="crudData.orderColumn"
                size="4"
                :label="$t('crud.add.field.orderColumn.title')"
                :placeholder="$t('crud.add.field.orderColumn.placeholder')"
                :items="fieldList"
                :alert="errors.orderColumn"
              />
              <badaso-select
                v-model="crudData.orderDisplayColumn"
                size="4"
                :label="$t('crud.add.field.orderDisplayColumn.title')"
                :placeholder="
                  $t('crud.add.field.orderDisplayColumn.placeholder')
                "
                :items="fieldList"
                :alert="errors.orderDisplayColumn"
                :additional-info="
                  $t('crud.add.field.orderDisplayColumn.description')
                "
              />
              <badaso-select
                v-model="crudData.orderDirection"
                size="4"
                :label="$t('crud.add.field.orderDirection.title')"
                :placeholder="$t('crud.add.field.orderDirection.placeholder')"
                :items="orderDirections"
                :alert="errors.orderDirection"
              />
              <badaso-hidden
                v-model="crudData.defaultServerSideSearchField"
                size="3"
                :label="$t('crud.add.field.defaultServerSideSearchField.title')"
                :placeholder="
                  $t('crud.add.field.defaultServerSideSearchField.placeholder')
                "
                :items="fieldList"
                :alert="errors.defaultServerSideSearchField"
              />
              <vs-col class="crud-management__notification-title">
                <label class="crud-management__label"
                  >{{ $t("crud.edit.field.activeEventNotification.title") }}
                  <vs-tooltip
                    :text="$t('crud.help.activeEventNotificationTitle')"
                  >
                    <vs-icon icon="help_outline" size="16px" color="#A5A5A5" />
                  </vs-tooltip>
                </label>
              </vs-col>
              <vs-row>
                <vs-col>
                  <vs-checkbox
                    v-model="onCreate"
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                  >
                    {{
                      $t(
                        "crud.edit.field.activeEventNotification.label.onCreate"
                      )
                    }}
                  </vs-checkbox>
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
                </vs-col>
              </vs-row>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    v-model="onRead"
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                  >
                    {{
                      $t("crud.edit.field.activeEventNotification.label.onRead")
                    }}
                  </vs-checkbox>
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
                </vs-col>
              </vs-row>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    v-model="onUpdate"
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                  >
                    {{
                      $t(
                        "crud.edit.field.activeEventNotification.label.onUpdate"
                      )
                    }}
                  </vs-checkbox>
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
                </vs-col>
              </vs-row>

              <vs-row>
                <vs-col>
                  <vs-checkbox
                    v-model="onDelete"
                    class="crud-management__notification-item"
                    @change="onCheckBoxNotificationOnEvent"
                  >
                    {{
                      $t(
                        "crud.edit.field.activeEventNotification.label.onDelete"
                      )
                    }}
                  </vs-checkbox>
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
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
                    :additional-info="null"
                    :alert="errors.icon"
                  />
                </vs-col>
              </vs-row>

              <badaso-textarea
                v-model="crudData.description"
                size="12"
                :label="$t('crud.add.field.description.title')"
                :placeholder="$t('crud.add.field.description.placeholder')"
                :alert="errors.description"
              />
            </vs-row>
          </badaso-collapse-item>
        </badaso-collapse>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>
              {{
                $t("crud.add.title.field", {
                  tableName: $route.params.tableName,
                })
              }}
            </h3>
          </div>
          <vs-row class="hide-for-mobile">
            <vs-col col-lg="12">
              <table class="badaso-table">
                <thead>
                  <th class="badaso-table__th" />
                  <th class="badaso-table__th">
                    {{ $t("crud.add.header.field") }}
                  </th>
                  <th class="badaso-table__th">
                    {{ $t("crud.add.header.visibility") }}
                  </th>
                  <th class="badaso-table__th badaso-table__th--sm">
                    {{ $t("crud.add.header.inputType") }}
                  </th>
                  <th class="badaso-table__th--sm">
                    {{ $t("crud.add.header.displayName") }}
                  </th>
                  <th class="badaso-table__th--sm">
                    {{ $t("crud.add.header.optionalDetails") }}
                  </th>
                </thead>
                <draggable v-model="crudData.rows" tag="tbody">
                  <tr v-for="(field, index) in crudData.rows" :key="index">
                    <td>
                      <vs-icon icon="drag_indicator" class="is-draggable" />
                    </td>
                    <td :data="field.field">
                      <strong>{{ field.field }}</strong>
                      <br />
                      <span>
                        {{ $t("crud.add.body.type") }} {{ field.type }}
                      </span>
                      <br />
                      <span>
                        {{ $t("crud.add.body.required.title") }}
                        <span v-if="field.required">{{
                          $t("crud.add.body.required.yes")
                        }}</span
                        ><span v-else>{{
                          $t("crud.add.body.required.no")
                        }}</span>
                      </span>
                      <br />
                      <span
                        v-for="err in errors[`rows.${index}.field`]"
                        :key="err"
                        class="is-error"
                        >{{ err }}</span
                      >
                    </td>
                    <td>
                      <vs-checkbox
                        v-model="field.browse"
                        class="crud-management__field-visibility"
                      >
                        {{ $t("crud.add.body.browse") }}
                      </vs-checkbox>
                      <vs-checkbox
                        v-model="field.read"
                        class="crud-management__field-visibility"
                      >
                        {{ $t("crud.add.body.read") }}
                      </vs-checkbox>
                      <vs-checkbox
                        v-model="field.edit"
                        class="crud-management__field-visibility"
                      >
                        {{ $t("crud.add.body.edit") }}
                      </vs-checkbox>
                      <vs-checkbox
                        v-model="field.add"
                        class="crud-management__field-visibility"
                      >
                        {{ $t("crud.add.body.add") }}
                      </vs-checkbox>
                      <vs-checkbox
                        v-model="field.delete"
                        class="crud-management__field-visibility"
                      >
                        {{ $t("crud.add.body.delete") }}
                      </vs-checkbox>
                    </td>
                    <td>
                      <badaso-select
                        v-model="field.type"
                        size="12"
                        :items="componentList"
                        :alert="errors[`rows.${index}.type`]"
                      />
                    </td>
                    <td>
                      <badaso-text
                        v-model="field.displayName"
                        :placeholder="$t('crud.add.body.displayName')"
                        :alert="errors[`rows.${index}.displayName`]"
                      />
                    </td>
                    <td>
                      <badaso-code-editor
                        v-if="field.type !== 'relation'"
                        v-model="field.details"
                      />
                      <vs-button
                        v-if="field.type === 'relation'"
                        color="primary"
                        type="relief"
                        @click.stop
                        @click="openRelationSetup(field)"
                      >
                        {{ $t("crud.add.body.setRelation") }}
                      </vs-button>
                      <vs-popup
                        :title="$t('crud.add.body.setRelation')"
                        :active.sync="field.setRelation"
                      >
                        <vs-row>
                          <badaso-select
                            v-model="relation.relationType"
                            size="12"
                            :items="
                              field.relationType
                                ? relationOtherTypes
                                : relationTypes
                            "
                            :label="$t('crud.add.body.relationType')"
                          />
                          <vs-col
                            vs-lg="12"
                            class="crud-management__relation-destination"
                          >
                            <vs-select
                              v-model="relation.destinationTable"
                              :label="$t('crud.add.body.destinationTable')"
                              width="100%"
                              @input="changeTable"
                            >
                              <vs-select-item
                                v-for="(item, index) in destinationTables"
                                :key="index"
                                :value="item.value ? item.value : item"
                                :text="item.label ? item.label : item"
                              />
                            </vs-select>
                          </vs-col>
                          <badaso-select
                            v-model="relation.destinationTableColumn"
                            size="12"
                            :items="destinationTableColumns"
                            :label="$t('crud.add.body.destinationTableColumn')"
                          />
                          <badaso-select
                            v-model="relation.destinationTableDisplayColumn"
                            size="12"
                            :items="destinationTableColumns"
                            :label="
                              $t('crud.add.body.destinationTableDisplayColumn')
                            "
                          />
                          <badaso-select-multiple
                            v-model="relation.destinationTableDisplayMoreColumn"
                            size="12"
                            :label="
                              $t(
                                'crud.add.body.destinationTableDisplayMoreColumn'
                              )
                            "
                            :items="destinationTableColumns"
                          />
                        </vs-row>
                        <vs-row vs-type="flex" vs-justify="space-between">
                          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                            <vs-button
                              class="crud-management__button--block"
                              color="danger"
                              type="relief"
                              @click="field.setRelation = false"
                            >
                              {{ $t("crud.add.body.cancelRelation") }}
                            </vs-button>
                          </vs-col>
                          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                            <vs-button
                              class="crud-management__button--block"
                              color="primary"
                              type="relief"
                              @click="saveRelation(field)"
                            >
                              {{ $t("crud.add.body.saveRelation") }}
                            </vs-button>
                          </vs-col>
                        </vs-row>
                      </vs-popup>
                      <vs-button
                        v-if="
                          field.relationType == 'belongs_to_many' ||
                          field.relationType == 'has_one' ||
                          field.relationType == 'has_many'
                        "
                        color="danger"
                        type="relief"
                        @click="dropItemOtherRelation(index)"
                      >
                        <vs-icon icon="delete" />
                      </vs-button>
                    </td>
                  </tr>
                  <tr>
                    <td />
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.stop
                      @click="openRelationSetupManytomany()"
                    >
                      {{ $t("crud.add.body.setOtherRelation") }}
                    </vs-button>
                    <vs-popup
                      :title="$t('crud.add.body.setRelation')"
                      :active.sync="setOtherRelation"
                    >
                      <vs-row>
                        <badaso-select
                          v-model="otherRelation.relationType"
                          size="12"
                          :items="relationOtherTypes"
                          :label="$t('crud.add.body.relationType')"
                        />
                        <vs-col
                          vs-lg="12"
                          class="crud-management__relation-destination"
                        >
                          <vs-select
                            v-model="otherRelation.destinationTable"
                            :label="$t('crud.add.body.destinationTable')"
                            width="100%"
                            @input="changeTableManytomany"
                          >
                            <vs-select-item
                              v-for="(item, index) in destinationTables"
                              :key="index"
                              :value="item.value ? item.value : item"
                              :text="item.label ? item.label : item"
                            />
                          </vs-select>
                        </vs-col>
                        <badaso-select
                          v-model="otherRelation.destinationTableColumn"
                          size="12"
                          :items="destinationTableColumns"
                          :label="$t('crud.add.body.destinationTableColumn')"
                        />
                        <badaso-select
                          v-model="otherRelation.destinationTableDisplayColumn"
                          size="12"
                          :items="destinationTableColumns"
                          :label="
                            $t('crud.add.body.destinationTableDisplayColumn')
                          "
                        />
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
                                    v-model="
                                      relationManytomanyAdvance.destinationTableManytomany
                                    "
                                    :label="
                                      $t(
                                        'crud.add.body.destinationTableManytomany'
                                      )
                                    "
                                    width="100%"
                                  >
                                    <vs-select-item
                                      v-for="(item, index) in destinationTables"
                                      :key="index"
                                      :value="item.value ? item.value : item"
                                      :text="item.label ? item.label : item"
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
                            type="relief"
                            @click="cancelRelationManytomany"
                          >
                            {{ $t("crud.add.body.cancelRelation") }}
                          </vs-button>
                        </vs-col>
                        <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                          <vs-button
                            class="crud-management__button--block"
                            color="primary"
                            type="relief"
                            @click="saveRelationManytomany()"
                          >
                            {{ $t("crud.add.body.saveRelation") }}
                          </vs-button>
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
                  v-for="(field, index) in crudData.rows"
                  :key="index"
                  class="crud-management__mobile-row"
                >
                  <vs-col col-lg="12">
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
                            v-for="err in errors[`rows.${index}.field`]"
                            :key="err"
                            class="is-error"
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
                          >
                            {{ $t("crud.add.body.browse") }}
                          </vs-checkbox>
                          <vs-checkbox
                            v-model="field.read"
                            class="crud-management__field-visibility"
                          >
                            {{ $t("crud.add.body.read") }}
                          </vs-checkbox>
                          <vs-checkbox
                            v-model="field.edit"
                            class="crud-management__field-visibility"
                          >
                            {{ $t("crud.add.body.edit") }}
                          </vs-checkbox>
                          <vs-checkbox
                            v-model="field.add"
                            class="crud-management__field-visibility"
                          >
                            {{ $t("crud.add.body.add") }}
                          </vs-checkbox>
                          <vs-checkbox
                            v-model="field.delete"
                            class="crud-management__field-visibility"
                          >
                            {{ $t("crud.add.body.delete") }}
                          </vs-checkbox>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          {{ $t("crud.add.header.inputType") }}
                          <badaso-select
                            v-model="field.type"
                            size="12"
                            :items="componentList"
                            :alert="errors[`rows.${index}.type`]"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          {{ $t("crud.add.body.displayName") }}
                          <badaso-text
                            v-model="field.displayName"
                            :placeholder="$t('crud.add.body.displayName')"
                            :alert="errors[`rows.${index}.displayName`]"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          {{ $t("crud.add.header.optionalDetails") }}
                          <badaso-code-editor
                            v-if="field.type !== 'relation'"
                            v-model="field.details"
                          />
                          <vs-button
                            v-else-if="field.relationType == 'belongs_to_many'"
                            color="danger"
                            type="relief"
                            @click="dropItemOtherRelation(index)"
                          >
                            <vs-icon icon="delete" />
                          </vs-button>
                          <vs-button
                            v-else-if="field.relationType !== 'belongs_to_many'"
                            color="primary"
                            type="relief"
                            @click.stop
                            @click="openRelationSetup(field)"
                          >
                            {{ $t("crud.add.body.setRelation") }}
                          </vs-button>
                          <vs-popup
                            :title="$t('crud.add.body.setRelation')"
                            :active.sync="field.setRelation"
                          >
                            <vs-row>
                              <badaso-select
                                v-model="relation.relationType"
                                size="12"
                                :items="relationTypes"
                                :label="$t('crud.add.body.relationType')"
                              />
                              <vs-col
                                v-if="
                                  relation.relationType == 'belongs_to_many'
                                "
                                vs-lg="12"
                                class="crud-management__relation-destination"
                              >
                                <vs-select
                                  v-model="relation.destinationTableManytomany"
                                  :label="
                                    $t(
                                      'crud.add.body.destinationTableManytomany'
                                    )
                                  "
                                  width="100%"
                                >
                                  <vs-select-item
                                    v-for="(item, index) in destinationTables"
                                    :key="index"
                                    :value="item.value ? item.value : item"
                                    :text="item.label ? item.label : item"
                                  />
                                </vs-select>
                              </vs-col>
                              <vs-col
                                vs-lg="12"
                                class="crud-management__relation-destination"
                              >
                                <vs-select
                                  v-model="relation.destinationTableManytomany"
                                  :label="$t('crud.add.body.destinationTable')"
                                  width="100%"
                                  @input="changeTable"
                                >
                                  <vs-select-item
                                    v-for="(item, index) in destinationTables"
                                    :key="index"
                                    :value="item.value ? item.value : item"
                                    :text="item.label ? item.label : item"
                                  />
                                </vs-select>
                              </vs-col>
                              <badaso-select
                                v-model="relation.destinationTableColumn"
                                size="12"
                                :items="destinationTableColumns"
                                :label="
                                  $t('crud.add.body.destinationTableColumn')
                                "
                              />
                              <badaso-select
                                v-model="relation.destinationTableDisplayColumn"
                                size="12"
                                :items="destinationTableColumns"
                                :label="
                                  $t(
                                    'crud.add.body.destinationTableDisplayColumn'
                                  )
                                "
                              />
                              <badaso-select-multiple
                                v-model="
                                  relation.destinationTableDisplayMoreColumn
                                "
                                size="12"
                                :items="destinationTableColumns"
                                :label="
                                  $t(
                                    'crud.add.body.destinationTableDisplayMoreColumn'
                                  )
                                "
                              />
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
                                >
                                  {{ $t("crud.add.body.saveRelation") }}
                                </vs-button>
                              </vs-col>
                              <vs-col
                                vs-lg="2"
                                vs-type="flex"
                                vs-align="flex-end"
                              >
                                <vs-button
                                  color="danger"
                                  @click="field.setRelation = false"
                                >
                                  {{ $t("crud.add.body.cancelRelation") }}
                                </vs-button>
                              </vs-col>
                            </vs-row>
                          </vs-popup>
                        </td>
                      </tr>
                    </table>
                  </vs-col>
                </vs-row>
                <vs-button
                  color="primary"
                  type="relief"
                  @click.stop
                  @click="openRelationSetupManytomany()"
                >
                  {{ $t("crud.add.body.setOtherRelation") }}
                </vs-button>
                <vs-popup
                  :title="$t('crud.add.body.setOtherRelation')"
                  :active.sync="setOtherRelation"
                >
                  <vs-row>
                    <badaso-select
                      v-model="otherRelation.relationType"
                      size="12"
                      :items="relationOtherTypes"
                      :label="$t('crud.add.body.relationType')"
                    />
                    <vs-col
                      vs-lg="12"
                      class="crud-management__relation-destination"
                    >
                      <vs-select
                        v-model="otherRelation.destinationTable"
                        :label="$t('crud.add.body.destinationTable')"
                        width="100%"
                        @input="changeTableManytomany"
                      >
                        <vs-select-item
                          v-for="(item, index) in destinationTables"
                          :key="index"
                          :value="item.value ? item.value : item"
                          :text="item.label ? item.label : item"
                        />
                      </vs-select>
                    </vs-col>
                    <badaso-select
                      v-model="otherRelation.destinationTableColumn"
                      size="12"
                      :items="destinationTableColumns"
                      :label="$t('crud.add.body.destinationTableColumn')"
                    />
                    <badaso-select
                      v-model="otherRelation.destinationTableDisplayColumn"
                      size="12"
                      :items="destinationTableColumns"
                      :label="$t('crud.add.body.destinationTableDisplayColumn')"
                    />
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
                              v-model="
                                relationManytomanyAdvance.destinationTableManytomany
                              "
                              :label="
                                $t('crud.add.body.destinationTableManytomany')
                              "
                              width="100%"
                            >
                              <vs-select-item
                                v-for="(item, index) in destinationTables"
                                :key="index"
                                :value="item.value ? item.value : item"
                                :text="item.label ? item.label : item"
                              />
                            </vs-select>
                          </vs-col>
                        </vs-row>
                      </badaso-collapse-item>
                    </badaso-collapse>
                  </vs-row>
                  <vs-row vs-type="flex" vs-justify="space-between">
                    <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                      <vs-button
                        color="primary"
                        @click="saveRelationManytomany()"
                      >
                        {{ $t("crud.add.body.saveRelation") }}
                      </vs-button>
                    </vs-col>
                    <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                      <vs-button
                        color="danger"
                        @click="cancelRelationManytomany"
                      >
                        {{ $t("crud.add.body.cancelRelation") }}
                      </vs-button>
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
                <vs-icon icon="save" /> {{ $t("crud.add.button") }}
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
  name: "CrudManagementAdd",
  components: {
    draggable,
  },
  data: () => ({
    errors: {},
    breadcrumb: [],
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
    relationTypes: [],
    relationOtherTypes: [],
    destinationTables: [],
    destinationTableColumns: [],
    setOtherRelation: false,
    otherRelation: {
      relationType: "",
      destinationTable: "",
      destinationTableColumn: "",
      destinationTableDisplayColumn: "",
      destinationTableDisplayMoreColumn: "",
    },
    relationManytomanyAdvance: {
      destinationTableManytomany: "",
    },
    itemKey: "",
    relation: {
      relationType: "",
      destinationTable: "",
      destinationTableColumn: "",
      destinationTableManytomany: "",
      destinationTableDisplayColumn: "",
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
        value: "asc",
      },
      {
        label: this.$t("crud.edit.field.orderDirection.value.descending"),
        value: "desc",
      },
    ];
    this.crudData.name = this.$route.params.tableName;
    this.crudData.displayNameSingular = this.$helper.generateDisplayName(
      this.$route.params.tableName
    );
    this.crudData.displayNamePlural = this.$helper.generateDisplayNamePlural(
      this.$route.params.tableName
    );
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
      field.destinationTableDisplayMoreColumn =
        this.relation.destinationTableDisplayMoreColumn;
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
        destinationTableManytomany: this.otherRelation
          .destinationTableManytomany
          ? this.otherRelation.destinationTableManytomany
          : "",
        destinationTableColumn: this.otherRelation.destinationTableColumn
          ? this.otherRelation.destinationTableColumn
          : "",
        destinationTableDisplayColumn: this.otherRelation
          .destinationTableDisplayColumn
          ? this.otherRelation.destinationTableDisplayColumn
          : "",
      };
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
        !this.crudData.rows.some(existKeyAutomatic) &&
        this.otherRelation.relationType != ""
      ) {
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
        .add(this.crudData)
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
      this.$api.badasoTable
        .read({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          const fieldList = response.data.tableFields;
          this.tableColumns = fieldList;
          this.fieldList = fieldList.map((field) => {
            return {
              label: field.name,
              value: field.name,
            };
          });
          this.crudData.rows = fieldList.map((field) => {
            if (["id"].includes(field.name)) {
              return {
                field: field.name,
                type: field.type,
                displayName: this.$helper.generateDisplayName(field.name),
                required: field.isNotNull,
                browse: false,
                read: false,
                edit: false,
                add: false,
                delete: false,
                details: "{}",
                order: 1,
                setRelation: false,
              };
            }
            if (
              ["created_at", "updated_at", "deleted_at"].includes(field.name)
            ) {
              return {
                field: field.name,
                type: field.type,
                displayName: this.$helper.generateDisplayName(field.name),
                required: field.isNotNull,
                browse: true,
                read: true,
                edit: false,
                add: false,
                delete: false,
                details: "{}",
                order: 1,
                setRelation: false,
              };
            } else {
              return {
                field: field.name,
                type: field.type,
                displayName: this.$helper.generateDisplayName(field.name),
                required: field.isNotNull,
                browse: true,
                read: true,
                edit: true,
                add: true,
                delete: true,
                details: "{}",
                order: 1,
                setRelation: false,
              };
            }
          });
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
        accept: () => this.$delete(this.crudData.rows, key),
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
  },
};
</script>

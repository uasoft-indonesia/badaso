<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <vs-breadcrumb :items="breadcrumb"></vs-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add</h3>
          </div>
          <vs-row>
            <badaso-text v-model="text" size="6"></badaso-text>
            <badaso-password v-model="password" size="6"></badaso-password>
            <badaso-textarea v-model="textarea" size="6"></badaso-textarea>
            <badaso-checkbox v-model="checkBoxes" size="6" :items="items"></badaso-checkbox>
            <badaso-search v-model="search" size="6"></badaso-search>
            <badaso-number v-model="number" size="6"></badaso-number>
            <badaso-url v-model="url" size="6"></badaso-url>
            <badaso-time v-model="time" size="6"></badaso-time>
            <badaso-date v-model="date" size="6"></badaso-date>
            <badaso-datetime v-model="datetime" size="6"></badaso-datetime>
            <badaso-select v-model="singleSelect" size="6" :items="items"></badaso-select>
            <badaso-radio v-model="radio" size="6" :items="items"></badaso-radio>
            <badaso-select-multiple v-model="multipleSelect" size="6" :items="items"></badaso-select-multiple>
            <badaso-switch size="6" v-model="switch1"></badaso-switch>
            <badaso-slider size="6" v-model="slider"></badaso-slider>
            <badaso-editor size="12" v-model="editor"></badaso-editor>
            <badaso-tags size="6" v-model="tags"></badaso-tags>
            <badaso-hidden v-model="hidden"></badaso-hidden>
            <badaso-upload-image size="6" v-model="uploadedImage"></badaso-upload-image>
            <badaso-upload-image-multiple size="6" v-model="multipleUploadedImage"></badaso-upload-image-multiple>
            <badaso-upload-file size="6" v-model="uploadedFile"></badaso-upload-file>
            <badaso-upload-file-multiple size="6" v-model="multipleUploadedFile"></badaso-upload-file-multiple>
            <badaso-color-picker size="6" v-model="colors"></badaso-color-picker>
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
import BadasoText from "../../components/BadasoText";
import BadasoPassword from "../../components/BadasoPassword";
import BadasoTextarea from "../../components/BadasoTextarea";
import BadasoCheckbox from "../../components/BadasoCheckbox";
import BadasoSearch from "../../components/BadasoSearch";
import BadasoNumber from "../../components/BadasoNumber";
import BadasoUrl from "../../components/BadasoUrl";
import BadasoTime from "../../components/BadasoTime";
import BadasoDate from "../../components/BadasoDate";
import BadasoDatetime from "../../components/BadasoDatetime";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoSelectMultiple from "../../components/BadasoSelectMultiple";
import BadasoRadio from "../../components/BadasoRadio";
import BadasoSwitch from "../../components/BadasoSwitch";
import BadasoSlider from "../../components/BadasoSlider";
import BadasoEditor from "../../components/BadasoEditor";
import BadasoTags from "../../components/BadasoTags";
import BadasoColorPicker from "../../components/BadasoColorPicker";
import BadasoUploadImage from "../../components/BadasoUploadImage";
import BadasoUploadImageMultiple from "../../components/BadasoUploadImageMultiple";
import BadasoUploadFile from "../../components/BadasoUploadFile";
import BadasoUploadFileMultiple from "../../components/BadasoUploadFileMultiple";
import BadasoHidden from "../../components/BadasoHidden";

export default {
  name: "Browse",
  components: {
    BadasoText,
    BadasoPassword,
    BadasoTextarea,
    BadasoCheckbox,
    BadasoSearch,
    BadasoNumber,
    BadasoUrl,
    BadasoTime,
    BadasoDate,
    BadasoDatetime,
    BadasoSelect,
    BadasoSelectMultiple,
    BadasoRadio,
    BadasoSwitch,
    BadasoSlider,
    BadasoEditor,
    BadasoTags,
    BadasoColorPicker,
    BadasoUploadImage,
    BadasoUploadImageMultiple,
    BadasoUploadFile,
    BadasoUploadFileMultiple,
    BadasoHidden,
  },
  data: () => ({
    breadcrumb: [
      {
        title: "Dashboard",
        url: "dashboard",
      },
      {
        title: "Link 1",
        url: "link-1",
      },
      {
        title: "Link 2",
        disabled: true,
      },
      {
        title: "Active",
        active: true,
      },
    ],
    text: "",
    password: "",
    textarea: "",
    checkBoxes: [],
    number: "",
    search: "",
    url: "",
    time: "",
    date: "",
    datetime: "",
    singleSelect: "",
    multipleSelect: [],
    radio: "",
    switch1: false,
    slider: 0,
    editor: "",
    tags: "",
    colors: "#000000",
    uploadedImage: {},
    multipleUploadedImage: [],
    uploadedFile: {},
    multipleUploadedFile: [],
    hidden: '',
    items: [
      {
        label: "Text 1",
        value: "Value 1",
      },
      {
        label: "Text 2",
        value: "Value 2",
      },
      {
        label: "Text 3",
        value: "Value 3",
      },
    ],
  }),
  methods: {
    submitForm() {
      let data = {
        text: this.text,
        password: this.password,
        textarea: this.textarea,
        checkBoxes: this.checkBoxes,
        number: this.number,
        search: this.search,
        url: this.url,
        time: this.time,
        date: this.date,
        datetime: this.datetime,
        singleSelect: this.singleSelect,
        multipleSelect: this.multipleSelect,
        radio: this.radio,
        switch1: this.switch1,
        slider: this.slider,
        editor: this.editor,
        tags: this.tags,
        colors: this.colors,
        uploadedImage: this.uploadedImage,
        multipleUploadedImage: this.multipleUploadedImage,
      };
      console.log(JSON.stringify(data));
    },
  },
};
</script>
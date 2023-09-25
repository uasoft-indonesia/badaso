export const label = "English";

export default {
  button: {
    close: "Close",
    submit: "Submit",
  },
  vuelidate: {
    required: "* {0} is required.",
    requiredIf: "* {0} is required.",
    integer: "* {0} must be integer.",
    rowsRequired: "* Please fill in the field.",
    maxLength: "* {field} must have at most {length} letters.",
    alphaNum: "* {0} is not alphanumeric.",
    alphaNumAndUnderscoreValidator:
      "* {0} only alphanumeric and underscore (_) allowed.",
    unique: "* {0} must be unique.",
    distinct: "* Only one {0} is allowed.",
    requiredPrimary: "* Only {0} is allowed.",
  },
  login: {
    title: "Dashboard",
    subtitle: "Welcome back, please login to your account.",
    field: {
      email: "Email",
      password: "Password",
    },
    rememberMe: "Remember me?",
    forgotPassword: "Forgot password",
    button: "Login",
    createAccount: {
      text: "Don't have an account?",
      link: "Create an account",
    },
  },

  register: {
    title: "Register Here",
    subtitle: "Please fill in the form below.",
    field: {
      name: "Name",
      username: "Username",
      phone: "Phone",
      address: "Address",
      email: "Email",
      password: "Password",
      passwordConfirmation: "Password Confirmation",
      gender: "Gender",
    },
    button: "Register",
    existingAccount: {
      text: "Do you have an account?",
      link: "Login",
    },
    gender: {
      man: "Man",
      woman: "Woman",
    },
  },

  forgotPassword: {
    title: "Forgot Password",
    subtitle: "Please provide an email to send reset password mail.",
    field: {
      email: "Email",
    },
    button: "Send",
    createAccount: {
      text: "Don't have an account?",
      link: "Create an account",
    },
    message: {
      success:
        "An email has been sent to the address you have provided. Please follow the link in the email to complete your password reset request.",
      error:
        "An error has occured. Please check again the email that you provided. If the problem persists, please contact us for further assistance.",
    },
  },

  resetPassword: {
    title: "Reset Password",
    subtitle: "Please provide new password.",
    field: {
      password: "New Password",
      passwordConfirmation: "New Password Confirmation",
    },
    button: "Reset Password",
    createAccount: {
      text: "Don't have an account?",
      link: "Create an account",
    },
    message: {
      success:
        "Password reset successful. You can now login with the new password. You will now be redirected to login page.",
      error:
        "An error has occured. Please check again the password and password confirmation that you provided. If the problem persists, please contact us for further assistance.",
    },
  },

  verifyEmail: {
    title: "Email Verification",
    failed: "Email Verification in progress ....",
    button: "Verify",
    request: "Resend",
    field: {
      token: "Token",
    },
    message: {
      inProgress: "Email Verification in progress ....",
      success:
        "An email has been sent to the address you have provided. Please follow the link in the email to complete your email verification.",
      error: "Email verification failed.",
    },
  },

  sidebar: {
    dashboard: "Dashboard",
    mainMenu: "Main Menu",
    configurationMenu: "Configuration Menu",
  },

  myProfile: {
    title: "My Profile",
    username: "Username",
    logout: "Logout",
    profile: "Profile",
    email: "Email",
    password: "Password",
    oldPassword: "Current Password",
    newPassword: "New Password",
    newPasswordConfirmation: "New Password Confirmation",
    name: "Name",
    avatar: "Avatar",
    phone: "Phone Number",
    address: "Address",
    gender: "Gender",
    additionalInfo: "Additional info(optional)",
    token: "Verification Code",
    buttons: {
      updateProfile: "Update Profile",
      updateEmail: "Update Email",
      verifyEmail: "Verify Email",
      changePassword: "Change Password",
    },
  },

  404: {
    title: "Opps, Sorry",
    subtitle: "The page you were looking for was not found.",
    button: "Go Home",
  },

  action: {
    bulkDelete: "Bulk Delete",
    bulkRestore: "Bulk Restore",
    showTrash: "Show Trash",
    add: "Add",
    edit: "Edit",
    delete: {
      title: "Confirm",
      text: "Are you sure?",
      accept: "Accept",
      cancel: "Cancel",
    },
    restore: {
      title: "Confirm",
      text: "Are you sure restore?",
      accept: "Accept",
      cancel: "Cancel",
    },
    addItem: "Add Item",
    roles: "Roles",
    sort: "Sort",
    rollbackMigration: {
      title: "Rollback Migration",
      text: "Are you sure?",
      accept: "Accept",
      cancel: "Cancel",
    },
    exportToExcel: "Export to .xls",
    exportToPdf: "Export to .pdf",
  },

  alert: {
    success: "Success",
    danger: "Danger",
    error: "Error",
  },

  activityLog: {
    title: "Activity Log",
    warning: {
      notAllowed: "You're not allowed to browse Activity Log.",
    },
    header: {
      logName: "Log Name",
      causerType: "Causer Type",
      causerId: "Causer Id",
      causerName: "Causer Name",
      subjectType: "Subject Type",
      subjectId: "Subject Id",
      description: "Description",
      dateLogged: "Date Logged",
      action: "Action",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    detail: {
      title: "Detail Activity Log",
      causer: {
        title: "Detail Causer",
      },
      subject: {
        title: "Detail Subject",
      },
      properties: {
        title: "Properties",
      },
    },
  },

  site: {
    action: "Action",
    deletedImage: {
      title: "Deleted Image",
      text: "The selected image was successfully deleted",
    },
    configUpdated: "Config Updated",
    add: {
      title: "Add Site Configuration",
      field: {
        displayName: {
          title: "Display Name",
          placeholder: "Example: Display Name",
        },
        key: {
          title: "Key",
          placeholder: "Example: key_sample",
        },
        type: {
          title: "Type",
          placeholder: "Type",
        },
        group: {
          title: "Group",
          placeholder: "Group",
        },
        options: {
          title: "Options",
          description:
            "Options is required for Checkbox, Radio, Select, Select-multiple. Example: ",
          example: `{"items": [{"label":"This is label","value":"this_is_value"}] }`,
        },
      },
      button: "Save",
    },
    edit: {
      multiple: "Update Configurations",
    },
    maintenanceMode: "Maintenance setting is readonly.",
  },

  crud: {
    title: "CRUD",
    data: {
      switchDataRecycle: "Show Recycle Data",
      switchDataNormal: "Show Normal Data",
    },
    help: {
      generatePermissions:
        "Will generate permission for the CRUD created. The generated permissions is: browse_{table_name}, read_{table_name}, edit_{table_name}, add_{table_name}, delete_{table_name} and maintenance_{table_name}.",
      serverSide:
        "Set the pagination on browse to server side or client side. If you have small data, just turn this to Off, vice versa.",
      createSoftDelete:
        "Switch this to On if you want feature like recycle bin. You can recover the deleted data. Will create soft delete if the table is supported.",
      activeEventNotificationTitle:
        "This will show notification on right sidebar if the below action event is set. Please configure firebase push notification before using this feature.",
      modelName:
        "Fill this input if you want to override the CRUD Model. For example: App\\Models\\User.",
      controllerName:
        "Fill this input if you want to override the CRUD Controller. For example: App\\Http\\Controller\\HomeController. You can override one of the following methods: browse, all, read, edit, add, delete, restore, deleteMultiple, restoreMultiple, sort or setMaintenanceState.",
    },
    warning: {
      notAllowed: "You're not allowed to browse CRUD.",
      idNotAllowed: "Dont change name id for anything",
    },
    header: {
      table: "Table",
      action: "Action",
    },
    body: {
      button: "Add CRUD to this table",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    add: {
      title: {
        table: "Add CRUD for {tableName}",
        field: "Add CRUD Fields for {tableName}",
        advance: "Advanced Setting",
      },
      field: {
        tableName: {
          title: "Table Name",
          placeholder: "Table Name",
        },
        generatePermissions: "Generate Permissions",
        createSoftDelete: "Create Soft Delete",
        createSoftDeleteNote:
          "Note: if you create soft delete, automatically we created model and migration for soft delete",
        serverSide: "Server Side",
        displayNameSingular: {
          title: "Display Name(Singular)",
          placeholder: "Display Name(Singular)",
        },
        displayNamePlural: {
          title: "Display Name(Plural)",
          placeholder: "Display Name(Plural)",
        },
        urlSlug: {
          title: "URL Slug (must be unique)",
          placeholder: "URL Slug (must be unique)",
        },
        icon: {
          title: "Icon",
          placeholder: "Icon",
        },
        modelName: {
          title: "Model Name",
          placeholder: "Model Name",
        },
        controllerName: {
          title: "Controller Name",
          placeholder: "Controller Name",
        },
        orderColumn: {
          title: "Order Column",
          placeholder: "Order Column",
        },
        orderDisplayColumn: {
          title: "Order Display Column",
          placeholder: "Order Display Column",
          description:
            "<p class='text-muted'>Order Column will be filled with numbers to sort data if this field is set</p>",
        },
        orderDirection: {
          title: "Order Direction",
          value: {
            ascending: "Ascending",
            descending: "Descending",
          },
          placeholder: "Order Direction",
        },
        defaultServerSideSearchField: {
          title: "Default Server Side Search Field",
          placeholder: "Default Server Side Search Field",
        },
        description: {
          title: "Description",
          placeholder: "Description",
        },
      },
      header: {
        field: "Field",
        visibility: "Visibility",
        inputType: "Input Type",
        displayName: "Display Name",
        optionalDetails: "Optional Details",
      },
      body: {
        type: "Type:",
        required: {
          title: "Required:",
          yes: "Yes",
          no: "No",
        },
        browse: "Browse",
        read: "Read",
        edit: "Edit",
        add: "Add",
        delete: "Delete",
        displayName: "Display Name",
        setRelation: "Set Relation",
        setOtherRelation: "Set Other Relation",
        relationType: "Relation Type",
        destinationTable: "Destination Table",
        destinationTableManytomany: "Destination Table Many To Many",
        destinationTableColumn: "Destination Column",
        destinationTableDisplayColumn: "Destination Column To Display",
        saveRelation: "Save",
        cancelRelation: "Cancel",
      },
      button: "Save",
    },
    edit: {
      title: {
        table: "Edit CRUD for {tableName}",
        field: "Edit CRUD Fields for {tableName}",
        advance: "Advanced Setting",
      },
      field: {
        tableName: {
          title: "Table Name",
          placeholder: "Table Name",
        },
        generatePermissions: "Generate Permissions",
        serverSide: "Server Side",
        displayNameSingular: {
          title: "Display Name(Singular)",
          placeholder: "Display Name(Singular)",
        },
        displayNamePlural: {
          title: "Display Name(Plural)",
          placeholder: "Display Name(Plural)",
        },
        urlSlug: {
          title: "URL Slug (must be unique)(readonly)",
          placeholder: "URL Slug (must be unique)",
        },
        icon: {
          title: "Icon",
          placeholder: "Icon",
        },
        modelName: {
          title: "Model Name",
          placeholder: "Model Name",
        },
        controllerName: {
          title: "Controller Name",
          placeholder: "Controller Name",
        },
        orderColumn: {
          title: "Order Column",
          placeholder: "Order Column",
        },
        orderDisplayColumn: {
          title: "Order Display Column",
          placeholder: "Order Display Column",
          description:
            "<p class='text-muted'>Order Column will be filled with numbers to sort data if this field is set</p>",
        },
        orderDirection: {
          title: "Order Direction",
          value: {
            ascending: "Ascending",
            descending: "Descending",
          },
          placeholder: "Order Direction",
        },
        defaultServerSideSearchField: {
          title: "Default Server Side Search Field",
          placeholder: "Default Server Side Search Field",
        },
        description: {
          title: "Description",
          placeholder: "Description",
        },
        activeEventNotification: {
          title: "Active Event Notification",
          label: {
            onCreate: "On Create",
            onRead: "On Read",
            onUpdate: "On Update",
            onDelete: "On Delete",
            onCreateTitle: "Title Message Event On Create",
            onCreateMessage: " Message Event On Create",
            onReadTitle: "Title Message Event On Read",
            onReadMessage: " Message Event On Read",
            onUpdateTitle: "Title Message Event On Update",
            onUpdateMessage: " Message Event On Update",
            onDeleteTitle: "Title Message Event On Delete",
            onDeleteMessage: " Message Event On Delete",
          },
        },
      },
      header: {
        field: "Field",
        visibility: "Visibility",
        inputType: "Input Type",
        displayName: "Display Name",
        optionalDetails: "Optional Details",
      },
      body: {
        type: "Type:",
        required: {
          title: "Required:",
          yes: "Yes",
          no: "No",
        },
        browse: "Browse",
        read: "Read",
        edit: "Edit",
        add: "Add",
        delete: "Delete",
        displayName: "Display Name",
        setRelation: "Set Relation",
        setOtherRelation: "Set Other Relation",
        relationType: "Relation Type",
        destinationTable: "Destination Table",
        destinationTableManytomany: "Destination Table Many To Many",
        destinationTableColumn: "Destination Column",
        destinationTableDisplayColumn: "Destination Column To Display",
        saveRelation: "Save",
        cancelRelation: "Cancel",
      },
      button: "Save",
    },
  },

  menu: {
    title: "Menu",
    options: {
      showHeader: "Show Header Menu",
      expand: "Expand",
    },
    warning: {
      notAllowedToBrowse: "You're not allowed to browse Menu",
      notAllowedToAdd: "You're not allowed to add Menu",
      notAllowedToEdit: "You're not allowed to edit Menu",
    },
    help: {
      key: "You can set this key to be default menu in .env file. Also, you can register new menu on .env by input value.",
    },
    header: {
      key: "Key",
      displayName: "Display Name",
      action: "Action",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    add: {
      title: "Add Menu",
      field: {
        key: {
          title: "Key",
          placeholder: "menu_key",
        },
        displayName: {
          title: "Display Name",
          placeholder: "Display Name",
        },
        icon: {
          title: "Icon",
          placeholder: "Icon",
        },
      },
      button: "Save",
    },
    edit: {
      title: "Edit Menu",
      field: {
        key: {
          title: "Key",
          placeholder: "menu_key",
        },
        displayName: {
          title: "Display Name",
          placeholder: "Display Name",
        },
      },
      button: "Save",
    },
    builder: {
      title: "Menu Item",
      popup: {
        add: {
          title: "Add Menu Item",
          field: {
            title: "Title",
            url: "Url",
            target: {
              title: "Target",
              value: {
                thisTab: "This Tab",
                newTab: "New Tab",
              },
            },
            icon: {
              title: "Icon",
              description:
                "Use&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>material design icon</a>",
            },
          },
          button: {
            add: "Add",
            cancel: "Cancel",
          },
        },
        edit: {
          title: "Edit Menu Item",
          field: {
            title: "Title",
            url: "Url",
            target: {
              title: "Target",
              value: {
                thisTab: "This tab",
                newTab: "New tab",
              },
            },
            icon: {
              title: "Icon",
              description:
                "Use&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>material design icon</a>",
            },
          },
          button: {
            edit: "Edit",
            cancel: "Cancel",
          },
        },
      },
    },
    permission: {
      title: "Permissions",
      header: {
        key: "Key",
        description: "Description",
      },
      button: "Set selected permissions for menu",
      success: {
        title: "Success",
        text: "Permissions has been set",
      },
    },
  },

  user: {
    title: "User",
    header: {
      name: "Name",
      email: "Email",
      action: "Action",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    help: {
      emailVerified:
        "Switch this to on to automatically verified the email of created user",
    },
    add: {
      title: "Add User",
      field: {
        name: {
          title: "Name",
          placeholder: "Name",
        },
        username: {
          title: "Username",
          placeholder: "Username",
        },
        phone: {
          title: "Phone Number",
          placeholder: "Phone Number",
        },
        address: {
          title: "Address",
          placeholder: "Address",
        },
        email: {
          title: "Email",
          placeholder: "Email",
        },
        password: {
          title: "Password",
          placeholder: "Password",
        },
        emailVerified: {
          title: "Is Email Verified",
          placeholder: "Is Email Verified",
        },
        avatar: {
          title: "Avatar",
          placeholder: "Avatar",
        },
        gender: {
          title: "Gender",
          placeholder: "Gender",
        },
        additionalInfo: {
          title: "Additional Info (JSON)",
          placeholder: "Additional Info (JSON)",
          invalid: "Additional Info is invalid",
        },
      },
      button: "Save",
    },
    edit: {
      title: "Edit User",
      field: {
        name: {
          title: "Name",
          placeholder: "Name",
        },
        username: {
          title: "Username",
          placeholder: "Username",
        },
        phone: {
          title: "Phone Number",
          placeholder: "Phone Number",
        },
        address: {
          title: "Address",
          placeholder: "Address",
        },
        gender: {
          title: "Gender",
          placeholder: "Gender",
        },
        email: {
          title: "Email",
          placeholder: "Email",
        },
        password: {
          title: "Password",
          placeholder: "Leave blank if unchanged",
        },
        emailVerified: {
          title: "Is Email Verified",
          placeholder: "Is Email Verified",
        },
        avatar: {
          title: "Avatar",
          placeholder: "New Avatar",
        },
        additionalInfo: {
          title: "Additional Info (JSON)",
          placeholder: "Additional Info (JSON)",
          invalid: "Additional Info is invalid",
        },
      },
      button: "Save",
    },
    detail: {
      title: "Detail User",
      avatar: "Avatar",
      name: "Name",
      username: "Username",
      phone: "Phone Number",
      address: "Address",
      gender: "Gender",
      email: "Email",
      additionalInfo: "Additional Info",
      emailVerified: "Is Email Verified",
    },
    roles: {
      title: "Roles",
      header: {
        name: "Name",
        description: "Description",
        action: "Action",
      },
      button: "Set selected roles for user",
      success: {
        title: "Success",
        text: "Roles has been set",
      },
    },
    gender: {
      man: "Man",
      woman: "Woman",
    },
  },

  role: {
    title: "Role",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse Role",
      notAllowedToAdd: "You're not allowed to add Role",
      notAllowedToEdit: "You're not allowed to edit Role",
      notAllowedToBrowsePermission:
        "You're not allowed to browse Roles Permissions",
    },
    header: {
      name: "Name",
      displayName: "Display Name",
      description: "Description",
      action: "Action",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    add: {
      title: "Add Role",
      field: {
        name: {
          title: "Name",
          placeholder: "Name",
        },
        displayName: {
          title: "Display Name",
          placeholder: "Display Name",
        },
        description: {
          title: "Description",
          placeholder: "Description",
        },
      },
      button: "Save",
    },
    edit: {
      title: "Edit Role",
      field: {
        name: {
          title: "Name",
          placeholder: "Name",
        },
        displayName: {
          title: "Display Name",
          placeholder: "Display Name",
        },
        description: {
          title: "Description",
          placeholder: "Description",
        },
      },
      button: "Save",
    },
    detail: {
      title: "Detail Role",
      name: "Name",
      displayName: "Display Name",
      description: "Description",
      button: {
        edit: "Edit",
        permission: "Permission",
      },
    },
    permission: {
      title: "Permissions",
      header: {
        key: "Key",
        description: "Description",
      },
      button: "Set selected permissions for role",
      success: {
        title: "Success",
        text: "Permissions has been set",
      },
    },
  },

  permission: {
    title: "Permission",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse Permission",
      notAllowedToAdd: "You're not allowed to add Permission",
      notAllowedToEdit: "You're not allowed to edit Permission",
      notAllowedToRead: "You're not allowed to read Permission",
    },
    help: {
      alwaysAllow:
        "After the permission is created, it will assign to every role that created after the permission",
      isPublic: "Permissions will be publicly available",
    },
    header: {
      key: "Key",
      description: "Description",
      tableName: "Table Name",
      alwaysAllow: "Always Allow",
      isPublic: "Is Public",
      action: "Action",
      rolesCanSeeAllData: "Roles Can See All Data",
      fieldIdentifyRelatedUser: "Field Identify Related User",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    add: {
      title: "Add Permission",
      field: {
        key: {
          title: "Key",
          placeholder: "Key",
        },
        alwaysAllow: "Always Allow",
        isPublic: "Is Public",
        description: {
          title: "Description",
          placeholder: "Description",
        },
        tableName: {
          title: "Table Name",
          placeholder: "Table Name",
        },
      },
      button: "Save",
    },
    edit: {
      title: "Edit Permission",
      field: {
        key: {
          title: "Key",
          placeholder: "Key",
        },
        alwaysAllow: "Always Allow",
        isPublic: "Is Public",
        description: {
          title: "Description",
          placeholder: "",
        },
        tableName: {
          title: "Table Name",
          placeholder: "Table Name",
        },
        rolesCanSeeAllData: {
          title: "Role can see all data",
          placeholder: "Role can see all data",
        },
        fieldIdentifyRelatedUser: {
          title: "Column for identify user related data",
          placeholder: "Column for identify user related data",
        },
      },
      button: "Save",
    },
    detail: {
      title: "Detail Permission",
      key: "Key",
      description: "Description",
      tableName: "Table Name",
      alwaysAllow: {
        title: "Always Allow",
        yes: "Yes",
        no: "No",
      },
      isPublic: {
        title: "Is Public",
        yes: "Yes",
        no: "No",
      },
      button: "Edit",
      rolesCanSeeAllData: "Roles Can See All Data",
      fieldIdentifyRelatedUser: "Field Identify Related User",
    },
  },

  crudGenerated: {
    warning: {
      notAllowedToBrowse: "You're not allowed to browse {tableName}",
      notAllowedToAdd: "You're not allowed to add {tableName}",
      notAllowedToEdit: "You're not allowed to edit {tableName}",
      notAllowedToRead: "You're not allowed to read {tableName}",
    },
    header: {
      action: "Action",
    },
    footer: {
      descriptionTitle: "Registries",
      descriptionConnector: "of",
      descriptionBody: "Pages",
    },
    add: {
      title: "Add {tableName}",
      button: "Save",
    },
    edit: {
      title: "Edit {tableName}",
      button: "Update",
    },
    detail: {
      title: "Detail {tableName}",
      button: "Edit",
    },
    sort: {
      title: "Sort {tableName}",
    },
    maintenanceDialog: {
      title: "Setting",
      switch: "Maintenance Mode",
      button: "Save",
    },
  },
  keyIssue: {
    title: "License Issues",
    message:
      "Sorry, Badaso cannot be used because there is an issue on your license",
    listTitle: "Here are some of the problems that can occur with a license:",
    licenseEmpty: "License Empty",
    licenseEmptyDescription:
      "You haven't entered BADASO_LICENSE_KEY in .env. Please fill in before you can use Badaso. For more complete instructions, please see here.",
    licenseInvalid: "License Invalid",
    licenseInvalidDescription:
      "BADASO_LICENSE_KEY was not found. Please make sure it is the same as what you get on Badaso Dashboard. For more complete instructions, please see here.",
    licenseUsersExpired: "Active Period Expires",
    licenseUsersExpiredDescription:
      "Your active period has expired. Please add your active period to Badaso Dashboard so that your license can be used again. For more complete instructions, please see here.",
  },
  authorizationIssue: {
    title: "Session Expired",
    subtitle: "Sorry, cannot continue request because",
    message: "Authorization Failed, token expired or empty",
  },
  database: {
    browse: {
      title: "Database",
      addButton: "Add Table",
      alterButton: "Alter Table",
      rollbackButton: "Rollback Migration",
      dropButton: "Drop Table",
      goBackButton: "Go Back",
      deleteMigrationButton: "Delete Migration File",
      migrateButton: "Migrate",
      warning: {
        title: "Migration Not Sync",
        notAllowed:
          "Before you can use the Database Management, you should migrate the file that not migrated yet or you could delete the migration file. Here is a list of the migration files that haven't been migrated:",
        empty: "You must delete this generated CRUD first on CRUD Management.",
      },
      fieldNotSupport: {
        title: "Database Error",
        text: "There's unsupported data type in table, please see the supported data type in badaso documentation",
        tableList: "List of unsupported tables :",
        button: {
          visitDocs: "Visit Documentation",
        },
      },
    },
    add: {
      title: "Add Table",
      field: {
        table: "Table Name",
      },
      row: {
        title: "Add Table Field",
        subtitle: "Please read the {0} before you create the migration.",
        field: {
          timestamp: "Timestamp",
          tableName: "Table Name",
          fieldName: "Field Name",
          fieldType: "Field Type",
          fieldLength: "Length/Value",
          fieldDefault: "Default",
          asDefined: "User Defined Default Value",
          fieldNull: "Nullable",
          fieldIndex: "Index",
          fieldAttribute: "Unsigned",
          fieldIncrement: "Auto Increment",
          action: "Action",
          add: "Add",
        },
      },
      error: {
        fieldName: "Field name is required.",
        fieldType: "Field type is required.",
        tableName: "Table name is required.",
        fieldLength: "Field length is required.",
      },
      footer: {
        descriptionTitle: "Registries",
        descriptionConnector: "of",
        descriptionBody: "Pages",
      },
      button: "Save",
    },
    edit: {
      title: "Alter Table",
      field: {
        table: "Table Name",
      },
      row: {
        title: "Alter Table Field",
        field: {
          timestamp: "Timestamp",
          tableName: "Table Name",
          fieldName: "Field Name",
          fieldType: "Field Type",
          fieldLength: "Length",
          fieldDefault: "Default",
          asDefined: "User Defined Default Value",
          fieldNull: "Nullable",
          fieldIndex: "Index",
          fieldAttribute: "Unsigned",
          fieldIncrement: "Auto Increment",
          action: "Action",
          add: "Add",
        },
        drop: "Are you sure want to delete this field?",
      },
      warning: {
        title: "IMPORTANT",
        content:
          'Only the following column types can be "changed": Big Integer, BLOB, Boolean, Date, Datetime, Decimal, Float, Integer, JSON, Long Text, Medium Text, Set, Small Integer, Varchar, Text and Time. Also, every field that you change, it\'ll be recorded when you submit the alter table. If you make some mistakes, you can refresh this page to reset your changes.',
        crud: "Make sure the table has not been generated with CRUD Management if you want to edit or drop it.",
        notAllowed: "You're not allowed to edit.",
        fieldAttUnsigned:
          "Foreign key constraint is incorrectly formed. {0} to visit docs.",
        visitDocs: "Click here",
      },
      error: {
        fieldName: "Field name is required.",
        fieldType: "Field type is required.",
        tableName: "Table name is required.",
        fieldLength: "Field length is required.",
      },
      footer: {
        descriptionTitle: "Registries",
        descriptionConnector: "of",
        descriptionBody: "Pages",
      },
      button: "Save",
    },
    rollback: {
      title: "Rollback",
      label: "Enter rollback step",
      checkbox: "Delete Migration File?",
      invalid: "Please select the migration that you want to rollback.",
    },
    warning: {
      docs: "* Please read the {0} before using this feature.",
      exists: "The {0} field already exists",
      invalid:
        "Request is invalid. Please check the fields or table name if it's valid or not.",
      empty: "Request is invalid. No changes were made.",
      errorOnRequest: "Request is invalid.",
    },
    migration: {
      header: {
        migration: "Migration Name",
      },
      button: {
        rollback: "Rollback Migration",
      },
    },
  },
  fileManager: {
    title: "File Manager",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse file manager",
    },
    URL: {
      label: "Paste an image URL here",
      placeholder: "URL",
      descriptionText:
        "If your URL is correct, you'll see an image preview here. Large images may take a few minutes to appear. Only accept PNG and JPEG.",
      invalid: "Image is not valid",
    },
  },
  imageManager: {
    title: "Image Manager",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse image manager",
    },
  },
  firebase: {
    title: "Firebase",
    feature: "Feature",
    features: {
      firebaseCloudMessage: "Firebase Cloud Message",
    },
    form: {
      apiKey: "API Key",
      authDomain: "Auth Domain",
      projectId: "Project Id",
      storageBucket: "Storage Bucket",
      messagingSenderId: "Message Sender",
      appId: "App Id",
      measurementId: "Measurement Id",
      serverKey: "Server Key",
    },
  },
  logViewer: {
    title: "Log Viewer",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse log viewer",
    },
  },
  apidocs: {
    title: "API Documentation",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse api documentation.",
    },
  },
  notification: {
    notification: "Notification",
    detailMessage: "Detail Message",
  },
  noInternetAccess:
    "Data cannot load because internet of you not connected. Please to you connect internet again!",
  offlineFeature: {
    dataPending: "Data Pending...",
    dataUpdatePending: "Data Update Pending...",
    dataPendingAdd: {
      title: "Data Pending",
    },
    dataPendingEdit: {
      title: "Show Data Edit Pending",
    },
    crudGenerator: {
      deleteDataPending: "Delete Data Pending",
    },
  },
  softDelete: {
    crudGenerator: {
      restore: "Restore",
    },
  },
};

<template>
  <button
    ref="dropdown"
    v-bind="$attrs"
    class="badaso-dropdown__container"
    type="button"
    @click.stop
    v-on="listeners"
  >
    <slot />
  </button>
</template>

<script>
import { ref, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue';

export default {
  name: "BadasoDropdown",
  props: {
    vsTriggerClick: {
      default: false,
      type: Boolean,
    },
    vsTriggerContextmenu: {
      default: false,
      type: Boolean,
    },
    color: {
      default: "primary",
      type: String,
    },
    vsCustomContent: {
      default: false,
      type: Boolean,
    },
    vsDropRight: {
      default: false,
      type: Boolean,
    },
  },
  setup(props, { emit }) {
    const dropdown = ref(null);
    const vsDropdownVisible = ref(false);
    const rightx = ref(false);

    const listeners = computed(() => ({
      contextmenu: (evt) =>
        props.vsTriggerContextmenu ? clickToogleMenu(evt, true) : {},
      click: (evt) => {
        if (!props.vsTriggerContextmenu) {
          clickToogleMenu(evt);
        }

        if (dropdown.value === evt.target) {
          emit("click");
        }
      },
      mouseout: (evt) => toggleMenu("out", evt),
      mouseover: (evt) => toggleMenu("over", evt),
    }));

    const clickx = (evt) => {
      const dropdownMenu = document.querySelector(".vs-dropdown--menu");
      if (
        (props.vsTriggerClick || props.vsCustomContent) &&
        vsDropdownVisible.value
      ) {
        if (
          evt.target !== dropdown.value &&
          !dropdown.value.contains(evt.target)
        ) {
          if (!evt.target.closest(".vs-dropdown--menu")) {
            vsDropdownVisible.value = false;
            document.removeEventListener("click", clickx);
          }
        }
      }
    };

    const changeColor = () => {
      const child = dropdown.value?.children || [];
      child.forEach((item) => {
        if (item.classList.contains("dropdown")) {
          item.color = props.color;
        }
      });
    };

    const changePositionMenu = () => {
      const dropdownMenu = document.querySelector(".vs-dropdown--menu");
      const scrollTopx = window.pageYOffset || document.documentElement.scrollTop;
      if (
        dropdown.value.getBoundingClientRect().top + 300 >= window.innerHeight
      ) {
        nextTick(() => {
          dropdownMenu.style.top =
            dropdown.value.getBoundingClientRect().top -
            dropdownMenu.clientHeight -
            7 +
            scrollTopx + "px";
          dropdownMenu.classList.add("notHeight");
        });
      } else {
        dropdownMenu.classList.remove("notHeight");
        dropdownMenu.style.top =
          dropdown.value.getBoundingClientRect().top +
          dropdown.value.clientHeight +
          scrollTopx - 5 + "px";
      }

      nextTick(() => {
        const w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        if (
          dropdown.value.getBoundingClientRect().left +
            dropdownMenu.offsetWidth >=
          w - 25
        ) {
          // this.rightx = true
        }

        if (
          dropdown.value.getBoundingClientRect().right <
          dropdownMenu.clientWidth + 25
        ) {
          dropdownMenu.style.left =
            dropdownMenu.clientWidth +
            dropdown.value.getBoundingClientRect().left + "px";
          rightx.value = true;
          return;
        }
        dropdownMenu.style.left =
          dropdown.value.getBoundingClientRect().left +
          (props.vsDropRight
            ? dropdownMenu.clientWidth
            : dropdown.value.clientWidth) + "px";
      });
    };

    const clickToogleMenu = (evt) => {
      if (evt.type === "contextmenu") {
        evt.preventDefault();
      }
      const dropdownMenu = document.querySelector(".vs-dropdown--menu");
      if (props.vsTriggerClick || props.vsTriggerContextmenu) {
        if (
          vsDropdownVisible.value &&
          !evt.target.closest(".vs-dropdown--menu")
        ) {
          vsDropdownVisible.value = false;
        } else {
          vsDropdownVisible.value = true;
          window.addEventListener("click", () => {
            if (
              !evt.target.closest(".vs-con-dropdown") &&
              !evt.target.closest(".vs-dropdown--menu")
            ) {
              vsDropdownVisible.value = false;
            }
          });
        }
      }

      emit("click");
    };

    const toggleMenu = (typex, evt) => {
      const dropdownMenu = document.querySelector(".vs-dropdown--menu");
      if (!props.vsTriggerClick && !props.vsTriggerContextmenu) {
        if (typex === "over") {
          vsDropdownVisible.value = true;
        } else {
          if (!evt.relatedTarget.classList.contains("vs-dropdown-menu")) {
            vsDropdownVisible.value = false;
          }
        }
      }
    };

    onMounted(() => {
      changeColor();
      document.addEventListener("click", clickx);
    });

    onBeforeUnmount(() => {
      document.removeEventListener("click", clickx);
    });

    return {
      dropdown,
      vsDropdownVisible,
      rightx,
      listeners,
      clickx,
      changeColor,
      changePositionMenu,
      clickToogleMenu,
      toggleMenu
    };
  },
};
</script>

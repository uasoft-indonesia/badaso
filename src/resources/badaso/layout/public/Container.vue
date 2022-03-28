<template>
  <div>
    <router-view :key="$route.path"></router-view>
  </div>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Container",
};
</script>

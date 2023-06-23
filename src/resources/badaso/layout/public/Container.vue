<template>
  <div>
    <router-view :key="$route.path" />
  </div>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Container",
};
</script>

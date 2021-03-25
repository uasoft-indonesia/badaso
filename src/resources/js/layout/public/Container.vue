<template>
  <div class="blank_layout">
      <router-view  :key="$route.path"></router-view>
  </div>
</template>

<style>
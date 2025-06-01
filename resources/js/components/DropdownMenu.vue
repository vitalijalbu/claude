<template>
  <el-dropdown>
    <span class="el-dropdown-link">
      Dropdown Menu
    </span>
    <template #dropdown>
      <el-dropdown-menu>
        <el-dropdown-item>Option 1</el-dropdown-item>
        <el-dropdown-item>Option 2</el-dropdown-item>
        <el-dropdown-item>Option 3</el-dropdown-item>
      </el-dropdown-menu>
    </template>
  </el-dropdown>
</template>

<script setup>
// Nessun setup JS richiesto
</script>

<style scoped>
.el-dropdown-link {
  cursor: pointer;
  color: #409EFF;
}
</style>

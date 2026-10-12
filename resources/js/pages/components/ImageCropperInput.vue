<script setup>
import { ref } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'

const img = ref('https://images.unsplash.com/photo-1600984575359-310ae7b6bdf2?w=800')
const cropperRef = ref(null)

function crop() {
  const { canvas } = cropperRef.value.getResult()
  canvas.toBlob((blob) => {
    // upload du blob (FormData → Laravel)
  }, 'image/jpeg')
}
</script>

<template>
  <Cropper
    ref="cropperRef"
    class="cropper"
    :src="img"
    :stencil-props="{ aspectRatio: 1 / 1 }"
  />
  <button @click="crop">Valider</button>
</template>

<!-- <style>
.cropper {
  height: 400px;
  width: 400px;
  background: #ddd;
}
</style> -->

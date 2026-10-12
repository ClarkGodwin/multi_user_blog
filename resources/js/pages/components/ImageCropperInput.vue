<!-- ImageCropInput.vue -->
<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'
import Input from '@/components/ui/input/Input.vue'

defineOptions({ inheritAttrs: false }) // name, required, class... go to the Input

interface Props {
  aspectRatio?: number
  quality?: number
  name: string
  id?: string
}

const props = withDefaults(defineProps<Props>(), {
  aspectRatio: 1,
  quality: 0.9,
})

const inputRef = ref<InstanceType<typeof Input> | null>(null)
const dialogRef = ref<HTMLDialogElement | null>(null)
const cropperRef = ref<InstanceType<typeof Cropper> | null>(null)
const src = ref<string | null>(null)
const previewUrl = ref<string | null>(null)

let original: { name: string; type: string } = { name: 'image', type: 'image/jpeg' }

// The ref points to the shadcn wrapper; the real <input> is its root element
function getInput(): HTMLInputElement | null {
  return (inputRef.value?.$el as HTMLInputElement | undefined) ?? null
}

function onChange(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (!file) return

  original = { name: file.name, type: file.type }
  if (src.value) URL.revokeObjectURL(src.value)
  src.value = URL.createObjectURL(file)
  dialogRef.value?.showModal()
}

function save() {
  const canvas = cropperRef.value?.getResult()?.canvas
  const input = getInput()
  if (!canvas || !input) return

  const mime = original.type === 'image/png' ? 'image/png' : 'image/jpeg'
  const ext = mime === 'image/png' ? 'png' : 'jpg'

  canvas.toBlob(
    (blob: Blob | null) => {
      const input = getInput()
      if (!blob || !input) return

      const file = new File([blob], `${original.name.replace(/\.\w+$/, '')}.${ext}`, { type: mime })
      const dt = new DataTransfer()
      dt.items.add(file)
      input.files = dt.files // the form now submits the cropped file

      if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
      previewUrl.value = URL.createObjectURL(blob)
      dialogRef.value?.close()
    },
    mime,
    props.quality,
  )
}

function clearInput() {
  const input = getInput()
  if (input) input.value = ''
}

function cancel() {
  clearInput()
  dialogRef.value?.close()
}

onBeforeUnmount(() => {
  if (src.value) URL.revokeObjectURL(src.value)
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
})
</script>

<template>
  <div>
    <Input
      ref="inputRef"
      type="file"
      :name="name"
      :id="id"
      accept="image/*"
      v-bind="$attrs"
      @change="onChange"
    />

    <!-- <Input ref="inputRef" type="file" :name="name" :id="id" accept="image/*" v-bind="$attrs" @change="onChange" /> -->

    <img v-if="previewUrl" :src="previewUrl" class="preview" alt="Cropped preview" />

    <dialog ref="dialogRef" class="crop-dialog" @cancel="clearInput">
      <Cropper
        v-if="src"
        ref="cropperRef"
        class="cropper"
        :src="src"
        :stencil-props="{ aspectRatio: props.aspectRatio }"
      />
      <div class="actions">
        <button type="button" @click="cancel">Cancel</button>
        <button type="button" @click="save">Save</button>
      </div>
    </dialog>
  </div>
</template>

<style scoped>
.crop-dialog {
    width: min(90vw, 600px);
    padding: 1rem;
    border: 0;
    border-radius: 8px;
}

.cropper {
    height: 60vh;
    background: #ddd;
}

.actions {
    display: flex;
    justify-content: flex-end;
    gap: .5rem;
    margin-top: 1rem;
}

.preview {
    display: block;
    max-width: 150px;
    margin-top: .5rem;
}
</style>

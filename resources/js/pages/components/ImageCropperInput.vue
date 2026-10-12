<!-- ImageCropInput.vue -->
<script setup>
import { ref, onBeforeUnmount } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'

defineOptions({ inheritAttrs: false }) // name, required, etc. go to the <input>

const props = defineProps({
    aspectRatio: { type: Number, default: 1 },
    quality: { type: Number, default: 0.9 },
    name: String,
})

const inputRef = ref(null)
const dialogRef = ref(null)
const cropperRef = ref(null)
const src = ref(null)
const previewUrl = ref(null)
let original = { name: 'image', type: 'image/jpeg' }

function onChange(e) {
    const file = e.target.files[0]
    if (!file) return
    original = { name: file.name, type: file.type }
    if (src.value) URL.revokeObjectURL(src.value)
    src.value = URL.createObjectURL(file)
    dialogRef.value.showModal()
}

function save() {
    const { canvas } = cropperRef.value.getResult()
    const mime = original.type === 'image/png' ? 'image/png' : 'image/jpeg'
    const ext = mime === 'image/png' ? 'png' : 'jpg'

    canvas.toBlob((blob) => {
        const file = new File([blob], `${original.name.replace(/\.\w+$/, '')}.${ext}`, { type: mime })
        const dt = new DataTransfer()
        dt.items.add(file)
        inputRef.value.files = dt.files // the form now submits the cropped file

        if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = URL.createObjectURL(blob)
        dialogRef.value.close()
    }, mime, props.quality)
}

function clearInput() {
    inputRef.value.value = ''
}

function cancel() {
    clearInput()
    dialogRef.value.close()
}

onBeforeUnmount(() => {
    if (src.value) URL.revokeObjectURL(src.value)
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
})
</script>

<template>
    <div>
        <input ref="inputRef" type="file" :name="name" accept="image/*" v-bind="$attrs" @change="onChange" />

        <img v-if="previewUrl" :src="previewUrl" class="preview" alt="Cropped preview" />

        <dialog ref="dialogRef" class="crop-dialog" @cancel="clearInput">
            <Cropper v-if="src" ref="cropperRef" class="cropper" :src="src" :stencil-props="{ aspectRatio }" />
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

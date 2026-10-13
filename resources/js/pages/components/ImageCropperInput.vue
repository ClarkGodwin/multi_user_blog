<!-- ImageCropInput.vue -->
<script setup lang="ts">
import { ref, onBeforeUnmount, computed } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'

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

const isDialogOpen = ref(false)
let keepFileOnClose = false

const dialogOpen = computed({
    get: () => isDialogOpen.value,
    set: (open: boolean) => {
        isDialogOpen.value = open

        if (!open) {
            if (!keepFileOnClose) clearInput()
            keepFileOnClose = false
        }
    },
})

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
    dialogOpen.value = true
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
            keepFileOnClose = true
            dialogOpen.value = false
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
    dialogOpen.value = false
}

onBeforeUnmount(() => {
    if (src.value) URL.revokeObjectURL(src.value)
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
})
</script>

<template>
    <div>
        <Input ref="inputRef" type="file" :name="name" :id="id" accept="image/*" v-bind="$attrs" @change="onChange" />

        <Dialog v-model:open="dialogOpen">
            <DialogContent class="w-[min(92vw,48rem)] max-w-none gap-5 p-4 sm:p-6">
                <DialogHeader>
                    <DialogTitle>Crop your image</DialogTitle>
                    <DialogDescription>
                        Adjust the crop, then save to use this image.
                    </DialogDescription>
                </DialogHeader>

                <div class="h-[min(60vh,32rem)] min-h-64 w-full overflow-hidden rounded-md bg-muted">
                    <Cropper
                        v-if="src"
                        ref="cropperRef"
                        :src="src"
                        :stencil-props="{ aspectRatio: props.aspectRatio }"
                        class="h-full w-full"
                    />
                </div>
                <DialogFooter>
                    <Button variant="outline" @click="cancel">
                        Cancel
                    </Button>
                    <Button type="submit" @click="save">
                        Save
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>

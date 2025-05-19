<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue';
import { ImageIcon, X, CheckCircle, Eye, LoaderCircle, UploadCloud } from 'lucide-vue-next';
import VueEasyLightbox from 'vue-easy-lightbox';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip'
import apiService from '@/services/apiService';
import { toastError } from '@/services/toasterService'; // Adjust the path if your toast utility is elsewhere

interface UploadedMedia {
    id: number;
    uuid: string;
}

const props = defineProps<{
    temporaryUuid: string | null,
    deficiencyId: number, // Add this prop
}>();

const emit = defineEmits(['uploadStatusChange', 'uuidAssigned']);

const dragover = ref(false);
const imageUrls = ref<string[]>([]);
const files = ref<File[]>([]);
const visible = ref(false);
const index = ref(0);
const loading = ref<boolean[]>([]);
const uploadedMedia = ref<(UploadedMedia | null)[]>([]);

// Method to check if there are any unuploaded images
const hasUnuploadedImages = () => {
    return files.value.some((_, index) => !uploadedMedia.value[index]);
};

const hasImages = () => files.value.length > 0;

// Remove getUploadStatus since we don't need it anymore
defineExpose({
    hasUnuploadedImages,
    hasImages
});

const updateStatus = () => {
    emit('uploadStatusChange', {
        hasImages: files.value.length > 0,
        hasUnuploadedImages: files.value.some((_, index) => !uploadedMedia.value[index])
    });
};

// Process and upload files immediately
const processFiles = async (newFiles: File[]) => {
    try {
        const validFiles = newFiles.filter(file => file.type.startsWith('image/'));
        const newUrls = validFiles.map(file => URL.createObjectURL(file));

        imageUrls.value = [...imageUrls.value, ...newUrls];
        files.value = [...files.value, ...validFiles];
        loading.value = [...loading.value, ...new Array(validFiles.length).fill(false)];
        uploadedMedia.value = [...uploadedMedia.value, ...new Array(validFiles.length).fill(null)];

        updateStatus();
        // Remove auto-upload section
    } catch (error) {
        console.error('Error processing files:', error);
    }
};

const uploadFile = async (index: number) => {
    if (loading.value[index] || uploadedMedia.value[index]) return;

    loading.value[index] = true;
    try {
        const formData = new FormData();
        formData.append('file', files.value[index]);
        formData.append('purpose', 'deficiency_photo');
        if (props.temporaryUuid) formData.append('uuid', props.temporaryUuid);

        const response = await apiService.post(route('media.upload-temporarily'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.media_id) {
            uploadedMedia.value[index] = {
                id: response.media_id,
                uuid: response.uuid
            };
            if (!props.temporaryUuid) {
                emit('uuidAssigned', response.uuid);
            }
            updateStatus();
        } else {
            console.error('Upload failed: No media ID returned');
        }
    } catch (error: any) {
        toastError(error?.response.data.message);
    } finally {
        loading.value[index] = false;
        updateStatus();
    }
};

const removeImage = (index: number) => {
    try {
        if (imageUrls.value[index]) {
            URL.revokeObjectURL(imageUrls.value[index]);
        }
        imageUrls.value.splice(index, 1);
        files.value.splice(index, 1);
        loading.value.splice(index, 1);
        uploadedMedia.value.splice(index, 1);

        updateStatus();
    } catch (error) {
        console.error('Error removing image:', error);
    }
};

const onDrop = (e: DragEvent) => {
    e.preventDefault();
    dragover.value = false;
    const files = Array.from(e.dataTransfer?.files || []);
    processFiles(files);
};

const onFileSelect = (e: Event) => {
    const files = Array.from((e.target as HTMLInputElement).files || []);
    processFiles(files);
};

const showImg = (idx: number, event: Event) => {
    event.preventDefault(); // Prevent form submission
    index.value = idx;
    visible.value = true;
};

const handleHide = () => {
    visible.value = false;
};

// Cleanup URLs when component is destroyed
onBeforeUnmount(() => {
    imageUrls.value.forEach(url => URL.revokeObjectURL(url));
});

// Modify ID handling for file input
const inputId = `image-upload-${props.deficiencyId}`;
</script>

<template>
    <div class="space-y-4">
        <div @dragover.prevent="dragover = true" @dragleave.prevent="dragover = false" @drop="onDrop"
            class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors"
            :class="{ 'border-primary bg-primary/5': dragover, 'border-gray-300': !dragover }">
            <input :id="inputId" type="file" multiple accept="image/*" class="hidden" @change="onFileSelect">
            <label :for="inputId" class="cursor-pointer">
                <ImageIcon class="mx-auto h-12 w-12 text-gray-400" />
                <p class="mt-2 text-sm text-gray-600">
                    Drop images here or click to upload
                </p>
            </label>
        </div>

        <div v-if="imageUrls.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <div v-for="(url, index) in imageUrls" :key="index" class="relative group aspect-square"
                :class="{ 'ring-2 ring-green-500 ring-offset-2': uploadedMedia[index] }">
                <img :src="url" class="w-full h-full object-cover rounded-lg" alt="Preview"
                    @error="(e: any) => e.target.src = 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1 1%22/>'">

                <!-- Hover Overlay -->
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                    <div class="flex gap-3">
                        <TooltipProvider>
                            <!-- View Button -->
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <button type="button" @click="(e) => showImg(index, e)"
                                        class="p-2 bg-blue-500/90 hover:bg-blue-600 text-white rounded-full transition-colors">
                                        <Eye class="h-4 w-4 sm:h-5 sm:w-5" />
                                    </button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>View Image</p>
                                </TooltipContent>
                            </Tooltip>

                            <!-- Upload Button - Hide if already uploaded -->
                            <Tooltip v-if="!uploadedMedia[index]">
                                <TooltipTrigger asChild>
                                    <button type="button" @click="uploadFile(index)"
                                        class="p-2 bg-primary/90 hover:bg-primary text-white rounded-full transition-colors">
                                        <UploadCloud class="h-4 w-4 sm:h-5 sm:w-5" />
                                    </button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Upload Image</p>
                                </TooltipContent>
                            </Tooltip>

                            <!-- Remove Button -->
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <button type="button" @click.prevent="removeImage(index)"
                                        class="p-2 bg-red-500/90 hover:bg-red-600 text-white rounded-full transition-colors">
                                        <X class="h-4 w-4 sm:h-5 sm:w-5" />
                                    </button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Remove Image</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div v-if="loading[index]"
                    class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg">
                    <LoaderCircle class="h-6 w-6 animate-spin text-white" />
                </div>

                <!-- Upload Success Badge -->
                <div v-if="uploadedMedia[index]"
                    class="absolute top-2 left-2 bg-green-500 text-white rounded-full p-1.5">
                    <CheckCircle class="h-3 w-3 sm:h-4 sm:w-4" />
                </div>
            </div>
        </div>

        <div v-if="files.length > 0 && files.some((_, index) => !uploadedMedia[index])"
            class="text-sm text-amber-600 mt-2">
            Warning: Some images are not uploaded yet
        </div>

        <vue-easy-lightbox :visible="visible" :imgs="imageUrls" :index="index" @hide="handleHide" />
    </div>
</template>

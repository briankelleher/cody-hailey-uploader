<template>
    <div class="main-app">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-12">
                    <h1>Hailey &amp; Cody</h1>
                    <p>Upload your favorite pictures of you and the couple!</p>
                    <file-uploader></file-uploader>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import FileUploader from './components/FileUploader.vue';
</script>
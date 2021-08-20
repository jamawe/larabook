<template>
  <div>
    <img 
      src="https://images.unsplash.com/photo-1624455375091-9746420d4809?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80" alt="User background image" 
      ref="userImage"
      class="object-cover w-full">
  </div>
</template>

<script>
import Dropzone from 'dropzone';

export default {
  name: 'UploadableImage',

  props: [
    'imageWidth',
    'imageHeight',
    'location',
  ],

  data() {
    return {
      dropzone: null,
    }
  },

  mounted() {
    this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
  },

  computed: {
    settings() {
      return {
        paramName: 'image',
        url: '/api/user-images',
        accepetedFiles: 'image/*',
        params: {
          'width': this.imageWidth,
          'height': this.imageHeight,
          'location': this.location,
        },
        headers: {
          'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
        },
        success: (e, res) => {
          alert('Uploaded!');
        }
      };
    }
  }
}
</script>
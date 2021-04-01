<template>
  <div class="cropperContainer">
    <h1>Upload cropped image to server</h1>
    <label class="label" data-toggle="tooltip" title="Change your avatar">
      <img
        class="rounded"
        id="avatar"
        src="https://avatars0.githubusercontent.com/u/3456749?s=160"
        alt="avatar"
      />
      <input
        type="file"
        class="sr-only"
        id="input"
        name="image"
        accept="image/*"
      />
    </label>
    <div
      class="modal fade"
      id="modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Crop the image</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img
                id="image"
                src="https://avatars0.githubusercontent.com/u/3456749"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button type="button" class="btn btn-primary" id="crop">
              Crop
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.cropperContainer {
  margin: 20px auto;
  max-width: 640px;
}

img {
  max-width: 100%;
}

.cropper-view-box,
.cropper-face {
  border-radius: 50%;
}
@import "https://unpkg.com/bootstrap@4.6.0/dist/css/bootstrap.min.css";
@import "../../plugins/cropper/cropper.css";
</style>
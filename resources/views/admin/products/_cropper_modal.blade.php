<!-- =========================================================================
     MORAIA PRODUCT IMAGE CROPPER MODAL (1:1 ASPECT RATIO)
     Allows cropping any uploaded image to 1:1 while preserving original on disk
========================================================================= -->
<div id="moraiaCropperModal" class="cropper-modal-overlay" style="display: none;" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="cropper-modal-container">
        <!-- Modal Header -->
        <div class="cropper-modal-header">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="cropper-title-badge">Aspecto 1:1 Cuadrado</span>
                    <h3 class="cropper-modal-title">Ajustar Encuadre de la Fotografía</h3>
                </div>
                <p class="cropper-modal-subtitle">
                    Arrastra y haz zoom para encuadrar lo que se verá en la tienda. El archivo original se conservará intacto.
                </p>
            </div>
            <button type="button" class="cropper-btn-close" id="cropperBtnClose" title="Cerrar sin guardar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Cropping Viewport -->
        <div class="cropper-modal-body">
            <div class="cropper-viewport-wrap">
                <img id="cropperTargetImage" src="" alt="Imagen a recortar">
            </div>

            <!-- Toolbar Controls -->
            <div class="cropper-toolbar">
                <div class="cropper-toolbar-group">
                    <button type="button" class="cropper-tool-btn" id="cropZoomIn" title="Acercar (+)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                        </svg>
                        <span>Zoom +</span>
                    </button>
                    <button type="button" class="cropper-tool-btn" id="cropZoomOut" title="Alejar (-)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM7.5 10.5h6" />
                        </svg>
                        <span>Zoom -</span>
                    </button>
                </div>

                <div class="cropper-toolbar-divider"></div>

                <div class="cropper-toolbar-group">
                    <button type="button" class="cropper-tool-btn" id="cropRotateLeft" title="Girar 90° antihorario">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        <span>Girar -90°</span>
                    </button>
                    <button type="button" class="cropper-tool-btn" id="cropRotateRight" title="Girar 90° horario">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                        </svg>
                        <span>Girar +90°</span>
                    </button>
                </div>

                <div class="cropper-toolbar-divider"></div>

                <div class="cropper-toolbar-group">
                    <button type="button" class="cropper-tool-btn" id="cropReset" title="Restablecer posición inicial">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>Centrar</span>
                    </button>
                </div>

                <div class="cropper-toolbar-hint">
                    💡 Usa la rueda del mouse o pellizca para zoom
                </div>
            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="cropper-modal-footer">
            <div class="cropper-footer-info">
                <span class="cropper-dot-status"></span>
                <span>Proporción fija: <strong>1:1 (Cuadrado para catálogo)</strong></span>
            </div>
            <div class="cropper-footer-btns">
                <button type="button" class="btn btn-outline btn-sm" id="cropperBtnCancel">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="cropperBtnApply" style="font-weight: 700; padding: 0.5rem 1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right: 4px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>Aplicar Recorte 1:1</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Cropper Modal Styles */
.cropper-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: rgba(15, 23, 42, 0.78);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    animation: cropperFadeIn 0.2s ease-out;
}

@keyframes cropperFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.cropper-modal-container {
    background: #FFFFFF;
    width: 100%;
    max-width: 800px;
    border-radius: var(--radius-md, 12px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: 92vh;
    animation: cropperZoomIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes cropperZoomIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.cropper-modal-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--color-border, #E5E0DC);
    background: #FAF7F5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.cropper-title-badge {
    background: var(--color-primary-dark, #A95058);
    color: #FFFFFF;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 2px 7px;
    border-radius: 9999px;
}

.cropper-modal-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--color-text, #242020);
    margin: 0;
}

.cropper-modal-subtitle {
    font-size: 0.75rem;
    color: var(--color-text-muted, #7A7270);
    margin: 3px 0 0 0;
}

.cropper-btn-close {
    background: transparent;
    border: none;
    color: var(--color-text-muted, #7A7270);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

.cropper-btn-close:hover {
    background: #EAE3DE;
    color: var(--color-text, #242020);
}

.cropper-modal-body {
    padding: 0;
    background: #141417;
    position: relative;
    overflow: hidden;
}

.cropper-viewport-wrap {
    height: 420px;
    max-height: 52vh;
    width: 100%;
    background-color: #141417;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.cropper-viewport-wrap img {
    max-width: 100%;
    max-height: 100%;
    display: block;
}

.cropper-toolbar {
    padding: 0.6rem 1rem;
    background: #1E1E24;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.cropper-toolbar-group {
    display: flex;
    align-items: center;
    gap: 4px;
}

.cropper-toolbar-divider {
    width: 1px;
    height: 20px;
    background: rgba(255, 255, 255, 0.12);
    margin: 0 4px;
}

.cropper-tool-btn {
    background: rgba(255, 255, 255, 0.08);
    color: #F4F0EB;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: var(--radius-xs, 6px);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.3rem 0.6rem;
    display: flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.cropper-tool-btn:hover {
    background: var(--color-primary-dark, #A95058);
    color: #FFFFFF;
    border-color: var(--color-primary-dark, #A95058);
}

.cropper-toolbar-hint {
    margin-left: auto;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
}

.cropper-modal-footer {
    padding: 0.85rem 1.25rem;
    background: #FFFFFF;
    border-top: 1px solid var(--color-border, #E5E0DC);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.cropper-footer-info {
    font-size: 0.75rem;
    color: var(--color-text-muted, #7A7270);
    display: flex;
    align-items: center;
    gap: 6px;
}

.cropper-dot-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #38A169;
    box-shadow: 0 0 0 2px rgba(56, 161, 105, 0.2);
}

.cropper-footer-btns {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Gallery Card Action Badges & Crop Buttons */
.sortable-image-card {
    position: relative;
    aspect-ratio: 1 / 1;
    background-color: #FAF6F4;
    border: 2px solid var(--color-border, #E5E0DC);
    border-radius: var(--radius-xs, 6px);
    overflow: hidden;
    cursor: grab;
    user-select: none;
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sortable-image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover !important;
    display: block;
    pointer-events: none;
}

.card-btn-crop {
    position: absolute;
    bottom: 5px;
    right: 5px;
    height: 24px;
    padding: 0 6px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.95);
    color: var(--color-primary-dark, #A95058);
    border: 1px solid var(--color-border, #E5E0DC);
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 0.65rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    transition: all 0.15s ease;
    z-index: 6;
}

.card-btn-crop:hover {
    background: var(--color-primary-dark, #A95058);
    color: #FFFFFF;
    border-color: var(--color-primary-dark, #A95058);
    transform: translateY(-1px);
}

.card-badge-cropped {
    position: absolute;
    bottom: 5px;
    left: 28px;
    background: rgba(56, 161, 105, 0.92);
    color: #FFFFFF;
    font-size: 0.58rem;
    font-weight: 700;
    padding: 1px 4px;
    border-radius: 3px;
    display: none;
    align-items: center;
    gap: 2px;
    z-index: 4;
}

/* Custom styling for Cropper container */
.cropper-view-box,
.cropper-face {
    border-radius: 0;
}
.cropper-line, .cropper-point {
    background-color: #E28D95 !important;
}
.cropper-view-box {
    outline: 2px solid #E28D95 !important;
    outline-color: rgba(226, 141, 149, 0.9) !important;
}
</style>

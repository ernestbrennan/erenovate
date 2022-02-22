export function messengerTemplate() {
    return `<div class="dz-preview dz-file-preview">
                <div class="dz-inner-box">
                    <div class="dz-info">
                        <div class="dz-filename">
                            <div class="dz-progressbar__box">
                                <div class="dz-img-box">
                                    <div class="lds-ring">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                    <img src="/img/icon/one-file_gray.svg" alt="file"/>
                                </div>
                            </div>
                            <span class="dz-filename__text" data-dz-name></span>
                        </div>
                        <div class="dz-size">
                            <span data-dz-size></span>
                        </div>
                    </div>
                    <div v-tooltip="remove file" class="dz-remove-box">
                        <span class="dropzone-tooltip">Remove file</span>
                        <img src="/img/icon/remove_gray.svg" alt="Click me to remove the file." data-dz-remove />
                    </div>
                </div>
            </div>`;
}

export function materialFileAttachments() {
    return `<div class="dz-preview dz-file-preview">
                <div class="dz-inner-box">
                    <div class="dz-info">
                        <div class="dz-filename">
                            <div class="dz-progressbar__box" data-dz-uploadprogress>
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <img src="/img/icon/one-file_gray.svg" alt="file"/>
                            </div>
                            <span class="dz-filename__text" data-dz-name></span>
                        </div>
                        <div class="dz-size">
                            <span data-dz-size></span>
                        </div>
                    </div>
                    <div class="dz-option-box">
                        <img class="dz-remove-target" src="/img/icon/stop__sm_gray.svg" alt="Click me to remove the file." data-dz-remove />
                    </div>
                </div>
            </div>`;
}

export function fileAttachments() {
    return`<div class="dz-preview dz-file-preview">
                <div class="dz-inner-box">
                    <div class="dz-info">
                        <div class="dz-filename">
                            <div class="dz-progressbar__box" data-dz-uploadprogress>
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <img src="/img/icon/one-file_gray.svg" alt="file"/>
                            </div>
                            <span class="dz-filename__text" data-dz-name></span>
                        </div>
                        <div class="dz-size">
                            <span data-dz-size></span>
                        </div>
                    </div>
                    <div class="dz-remove-box">
                        <img src="/img/icon/close_gray.svg" alt="Click me to remove the file." data-dz-remove />
                    </div>
                </div>
            </div>`;
}

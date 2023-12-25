import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dialog']

    open() {
        this.dialogTarget.showModal()
    }

    close() {
        document.querySelector('#default-user-search').value = ''
        this.dialogTarget.close()
    }

    clickOutside(event) {
        if (event.target === this.dialogTarget) {
            this.close()
        }
    }
}

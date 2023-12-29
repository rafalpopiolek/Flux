import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dialog']

    open() {
        document.body.classList.add('overflow-hidden', 'blur-sm');
        this.dialogTarget.showModal()
    }

    close() {
        document.querySelector('#default-user-search').value = ''
        document.body.classList.remove('overflow-hidden', 'blur-sm');
        this.dialogTarget.close()
    }

    clickOutside(event) {
        if (event.target === this.dialogTarget) {
            this.close()
        }
    }
}

import Popover from 'stimulus-popover'

export default class extends Popover {
    connect() {
        super.connect()
    }

    disconnect() {
        super.disconnect()
        this.cardTarget.remove()
    }
}
